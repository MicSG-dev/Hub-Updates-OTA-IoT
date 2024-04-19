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

// Parâmetro POST email-recover
isset($_POST["email"]) ? $email_cadastro = $_POST["email"] : $email_cadastro = null;

// Parâmetro POST code
isset($_POST["user"]) ? $username_cadastro = strtolower($_POST["user"]) : $username_cadastro = null;

// Parâmetro POST pass
isset($_POST["name"]) ? $nome_cadastro = $_POST["name"] : $nome_cadastro = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

if ($mode == "solicitar-acesso") {

    if (strlen($nome_cadastro) < 3 || strlen($nome_cadastro) > 256) {
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

        if (!emailEstaCadastradoNoSistema($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrado em outra conta
            if (!jaExisteSolicitacaoCadastro($host, $username, $password, $database, $email_cadastro)) { // verifica se o email já esta cadastrada em outra solicitação de novo acesso
                registrarSolicitacaoNovoCadastro($host, $username, $password, $database, $email_cadastro, $nome_cadastro, $username_cadastro);
            }
        }

        http_response_code(200);
        echo ("OK");
    }
} else {
    echo ($pageHtml);
}
