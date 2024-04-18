<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');

$nomeArquivoHtml = basename(__FILE__);
$prePath = str_replace($nomeArquivoHtml, "", __FILE__);
$interPath = "private\\html\\";

$fullPath = str_replace('.php', '.html', $prePath . $interPath . $nomeArquivoHtml);
$pageHtml = file_get_contents($fullPath);

executarFuncoesDeTodasPaginas($host, $username, $password, $database);

// verificar se usuário ESTA ou não logado. Se estiver logado, redirecionar ele para home. Se NÃO estiver logado, continuar.

// Parâmetro POST email-recover
isset($_POST["email-recover"]) ? $email_recover = $_POST["email-recover"] : $email_recover = null;

// Parâmetro POST code
isset($_POST["code"]) ? $code_recover = $_POST["code"] : $code_recover = null;

// Parâmetro POST pass
isset($_POST["pass"]) ? $senha = $_POST["pass"] : $senha = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;


if ($email_recover != null && $mode == "generate-code") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {

        // verificar se email esta cadastrado
        $temCadastro = emailEstaCadastradoNoSistema($host, $username, $password, $database, $email_recover);


        // se email esta cadastrado, salvar no BD um código aleatório de 6 dígitos e enviar por email para o USER

        if (!estaJaParaRedefinir($host, $username, $password, $database, $email_recover)) {
            if ($temCadastro) {
                $codigo = gerarCodigoRedefinicaoSenha();
                salvarCodigoRedefinicaoSenha($host, $username, $password, $database, $codigo, $email_recover);
            } else {
                salvarCodigoRedefinicaoSenha($host, $username, $password, $database, null, $email_recover);
            }
        }



        http_response_code(200);
        echo ("OK");
    } else {
        http_response_code(400);
        echo ("Email invalido");
    }
} else if ($email_recover != null && $mode == "time-next-generate-code") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {
        $timeRedef = getTimeRedef($host, $username, $password, $database, $email_recover);
        if ($timeRedef != "0") {

            $datetimeInitial = new DateTime($timeRedef, new DateTimeZone('America/Sao_Paulo'));
            $datetimeInitial->setTimezone(new DateTimeZone('UTC'));

            $datetimeEnd = new DateTime();
            $datetimeEnd->setTimezone(new DateTimeZone('UTC'));

            $interval = $datetimeEnd->getTimestamp() - $datetimeInitial->getTimestamp();

            $total_seconds = 2 * 60;

            $result = $total_seconds - $interval;
            if ($result < 0) {
                $result = 0;
            }

            $m = intval($result / 60);
            $s = $result % 60;
        } else {
            $m = 0;
            $s = 0;
        }

        http_response_code(200);
        $json_obj = new stdClass();
        $json_obj->m = $m;
        $json_obj->s = $s;
        $json_obj = json_encode($json_obj);
        echo $json_obj;
    } else {
        http_response_code(400);
        echo ("Email invalido");
    }
} else if ($email_recover != null && $mode == "regenerate-code") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {

        // verificar se email esta cadastrado
        $temCadastro = emailEstaCadastradoNoSistema($host, $username, $password, $database, $email_recover);

        // se email esta cadastrado, salvar no BD um código aleatório de 6 dígitos e enviar por email para o USER

        if (estaJaParaRedefinir($host, $username, $password, $database, $email_recover)) {

            $timeRedef = getTimeRedef($host, $username, $password, $database, $email_recover);

            if ($timeRedef != "0") {

                $datetimeInitial = new DateTime($timeRedef, new DateTimeZone('America/Sao_Paulo'));
                $datetimeInitial->setTimezone(new DateTimeZone('UTC'));

                $datetimeEnd = new DateTime();
                $datetimeEnd->setTimezone(new DateTimeZone('UTC'));

                $interval = $datetimeEnd->getTimestamp() - $datetimeInitial->getTimestamp();

                $total_seconds = 2 * 60;

                $result = $total_seconds - $interval;
                if ($result < 0) {
                    $result = 0;
                }

                $m = intval($result / 60);
                $s = $result % 60;
            } else {
                $m = 0;
                $s = 0;
            }

            if ($m == 0 && $s == 0) {
                // regerar codigo


                if ($temCadastro) {
                    $codigo = gerarCodigoRedefinicaoSenha();
                    regenerateCodeRecover($host, $username, $password, $database, $codigo, $email_recover);
                } else {
                    regenerateCodeRecover($host, $username, $password, $database, null, $email_recover);
                }
            }
        }
        http_response_code(200);
        echo ("OK");
    } else {
        http_response_code(400);
        echo ("Email invalido");
    }
} else if ($code_recover != null && $mode == "verify-code") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {

        if (verificarCodigoRedefinicaoSenha($host, $username, $password, $database, $code_recover, $email_recover)) {
            http_response_code(200);
            echo ("OK");
        } else {
            http_response_code(400);
            echo ("CODE");
        }
    } else {
        http_response_code(400);
        echo ("EMAIL");
    }
} else if ($mode == "cancel-recover") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {
        if (verificarCodigoRedefinicaoSenha($host, $username, $password, $database, $code_recover, $email_recover)) {
            efetuarCancelamentoCodigoRedefinicao($host, $username, $password, $database, $code_recover, $email_recover);
            http_response_code(200);
            echo ("OK");
        } else {
            http_response_code(400);
            echo ("ERROR_CANCEL");
        }
    } else {
        http_response_code(400);
        echo ("ERROR_CANCEL");
    }
} else if ($mode == "redef-password") {

    if (senhaEForte($senha)) {
        if (redefinirSenha($host, $username, $password, $database, $code_recover, $email_recover, $senha)) {
            http_response_code(200);
            echo ("OK");
        } else {
            http_response_code(400);
            echo ("Email e/ou código inválido");
        }
    } else {
        http_response_code(400);
        echo ("PASS_WEAK");
    }
} else {

    echo ($pageHtml);
}

function gerarCodigoRedefinicaoSenha()
{
    $code = null;

    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $qtdCaracteres = strlen($caracteres);

    for ($i = 0; $i < 6; $i++) {
        $code .= $caracteres[random_int(0, $qtdCaracteres - 1)];
    }

    return $code;
}

function senhaEForte($senha)
{

    if (strlen($senha) < 6) {
        return false;
    }
    return true;
}
