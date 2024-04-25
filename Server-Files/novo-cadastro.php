<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');

$nomeArquivoHtml = basename(__FILE__);
$prePath = str_replace($nomeArquivoHtml, "", __FILE__);
$interPath = "private\\html\\";

$fullPath = str_replace('.php', '.html', $prePath . $interPath . $nomeArquivoHtml);
$pageHtml = file_get_contents($fullPath);

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount);

// Parâmetro POST email
isset($_POST["email"]) ? $email_cadastro = $_POST["email"] : $email_cadastro = null;

// Parâmetro POST user
isset($_POST["user"]) ? $username_cadastro = strtolower($_POST["user"]) : $username_cadastro = null;

// Parâmetro POST name
isset($_POST["name"]) ? $nome_cadastro = $_POST["name"] : $nome_cadastro = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

// Parâmetro POST password
$password_cadastro = isset($_POST["password"]) ? $_POST["password"] : null;

$token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;

if ($mode == "solicitar-acesso") {

    $infoJwt = getInfoTokenJwt($token, $chaveJwt);

    if (estaLogado($token, $chaveJwt) && $infoJwt["sub"] != "demo") {
        http_response_code(400);
        echo ("JA_LOGADO");
    } else if (strlen($nome_cadastro) < 3 || strlen($nome_cadastro) > 256) {
        http_response_code(400);
        echo ("NOME");
    } else if (strlen($username_cadastro) < 3 || strlen($username_cadastro) > 26) {
        http_response_code(400);
        echo ("USER");
    } else if (!filter_var($email_cadastro, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo ("EMAIL");
    } else if (usernameJaExiste($host, $username, $password, $database, $username_cadastro)) {
        http_response_code(400);
        echo ("USER_EXISTS");
    } else {


        if ($infoJwt["sub"] == "demo") {

            atualizarUsuarioDemoParaGerente($host, $username, $password, $database, $email_cadastro, $password_cadastro, $nome_cadastro, $username_cadastro);

            setcookie("key", "");
            if ($token != null) {
                addTokenToBlackList($host, $username, $password, $database, $token, $chaveJwt);
            }
        } else {
            if (!emailEstaCadastradoNoSistema($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrado em outra conta
                if (!jaExisteSolicitacaoCadastro($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrada em outra solicitação de novo acesso
                    registrarSolicitacaoNovoCadastro($host, $username, $password, $database, $email_cadastro, $nome_cadastro, $username_cadastro,$password_cadastro);
                }
            }
        }

        http_response_code(200);
        echo ("OK");
    }
} else {

    if (estaLogado($token, $chaveJwt)) {
        $infoJwt = getInfoTokenJwt($token, $chaveJwt);
        if ($infoJwt["sub"] == "demo") {
            $pageHtml = replaceSubstrByIdHtml($pageHtml, "Cadastrar Gerente", "titulo-pagina", "</h2>");
            $pageHtml = replaceSubstrByIdHtml($pageHtml, "Como não há contas no sistema, você se tornará um usuário Gerente. Preencha os dados abaixo e, assim que concluído, você receberá um e-mail de confirmação.", "subtitulo-pagina", "</p>");

            $pageHtml = replaceSubstrByIdHtml($pageHtml, "<a class=\"btn btn-primary btn-sm\" role=\"button\" href=\"/logout\" style=\"margin-bottom: 5px;\">Fazer cadastro mais tarde</a>", "fazer-cadastro-depois", "</div>");
            $pageHtml = replaceSubstrByIdHtml($pageHtml, "", "voltar-home", "</div>");
            echo ($pageHtml);
        } else {
            header("Location: /");
            die();
        }
    } else {
        echo ($pageHtml);
    }
}

function replaceSubstrByIdHtml($str, $replace, $idHtml, $endTagHtml)
{
    $inicioPosicaoStr = strpos($str, "id=\"$idHtml\"");
    if ($inicioPosicaoStr == 0) {
        return $str;
    }

    $inicioPosicaoStr = strpos($str, ">", $inicioPosicaoStr) + 1;

    $fimPosicaoStr = strpos($str, $endTagHtml, $inicioPosicaoStr);

    $length = $fimPosicaoStr - $inicioPosicaoStr;

    if ($fimPosicaoStr == 0) {
        return $str;
    }

    return substr_replace($str, $replace, $inicioPosicaoStr, $length);
}
