<?php

define('database-acesso-privado-rv$he', TRUE);
$profundidadePastaAtual = 3;
$pastaInicial = implode("/", array_slice(explode("\\", __DIR__), 0, -$profundidadePastaAtual));

include_once($pastaInicial . '/private/database.php');
include_once($pastaInicial . '/private/credentials.php');
include_once($pastaInicial . '/private/vendor/autoload.php');
include_once($pastaInicial . '/private/utils/general.php');

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Parâmetro POST email
    $email_cadastro = isset($_POST["email"]) ?
        $_POST["email"] :
        null;

    // Parâmetro POST user
    $username_cadastro = isset($_POST["user"]) ?
        strtolower($_POST["user"]) :
        null;

    // Parâmetro POST name
    $nome_cadastro = isset($_POST["name"]) ?
        $_POST["name"] :
        null;

    // Parâmetro POST mode
    $mode = isset($_POST["mode"]) ?
        $_POST["mode"] :
        null;

    // Parâmetro POST password
    $password_cadastro = isset($_POST["password"]) ?
        $_POST["password"] :
        null;

    // obtém HTTP Cookie de chave 'key' contendo o token JWT
    $token = isset($_COOKIE["key"]) ?
        $_COOKIE["key"] :
        null;

    $infoJwt = getInfoTokenJwt($token, $chaveJwt);
    $infoJwt = estaNaTokenBlackList($host, $username, $password, $database, $token) ? null : $infoJwt;
    
    if (!estaLogadoVersao2($infoJwt, $versaoSistema) || getParamInfoJwt($infoJwt, "sub") == "demo") {

        if ($nome_cadastro == null || mb_strlen($nome_cadastro) < 3 || mb_strlen($nome_cadastro) > 256) {

            $response = new stdClass();
            $response->error = "NOME";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if ($username_cadastro == null || strlen($username_cadastro) < 3 || strlen($username_cadastro) > 26 || !usernameEhValido($username_cadastro)) {

            $response = new stdClass();
            $response->error = "USER";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if ($email_cadastro == null || !filter_var($email_cadastro, FILTER_VALIDATE_EMAIL)) {

            $response = new stdClass();
            $response->error = "EMAIL";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if (usernameJaExiste($host, $username, $password, $database, $username_cadastro)) {

            $response = new stdClass();
            $response->error = "USER_EXISTS";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if ($password_cadastro == null) {

            $response = new stdClass();
            $response->error = "PASS";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if (mb_strlen($password_cadastro) < 12) {

            $response = new stdClass();
            $response->error = "PASS_MIN";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if (mb_strlen($password_cadastro) > 4096) {

            $response = new stdClass();
            $response->error = "PASS_MAX";
            $json = json_encode($response);
            sendjson(400, $json);
        } else {


            if (getParamInfoJwt($infoJwt, "sub") == "demo") {

                atualizarUsuarioDemoParaGerente($host, $username, $password, $database, $email_cadastro, $password_cadastro, $nome_cadastro, $username_cadastro, $chaveCrypto);

                setcookie("key", "");
                if ($token != null) {
                    addTokenToBlackList($host, $username, $password, $database, $token, $chaveJwt);
                }
            } else {
                if (!emailEstaCadastradoNoSistema($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrado em outra conta
                    if (!jaExisteSolicitacaoCadastro($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrada em outra solicitação de novo acesso
                        registrarSolicitacaoNovoCadastro($host, $username, $password, $database, $email_cadastro, $nome_cadastro, $username_cadastro, $password_cadastro, $chaveCrypto);
                    }
                }
            }

            $response = new stdClass();
            $response->status = "OK";
            $json = json_encode($response);
            sendjson(200, $json);
        }
    } else {

        $response = new stdClass();
        $response->error = "JA_LOGADO";
        $json = json_encode($response);
        sendjson(400, $json);
    }
} else {

    $response = new stdClass();
    $response->error = "ERRO_METODO_HTTP";
    $json = json_encode($response);
    sendjson(400, $json);
}
