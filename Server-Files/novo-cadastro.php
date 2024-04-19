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
isset($_POST["user"]) ? $username_cadastro = $_POST["user"] : $username_cadastro = null;

// Parâmetro POST pass
isset($_POST["name"]) ? $nome_cadastro = $_POST["name"] : $nome_cadastro = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

if ($mode == "solicitar-acesso") {
    http_response_code(200);
    echo ("Solicitando acesso");
} else {
    echo ($pageHtml);
}
