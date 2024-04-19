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

// Parâmetro POST email
isset($_POST["email"]) ? $email_login = $_POST["email"] : $email_login = null;

// Parâmetro POST user
isset($_POST["password"]) ? $senha_login = strtolower($_POST["password"]) : $senha_login = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

//if($mode == )

echo ($pageHtml);
