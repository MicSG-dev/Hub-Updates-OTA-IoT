<?php

define('database-acesso-privado-rv$he', TRUE);
$profundidadePastaAtual = 3;
$pastaInicial = implode("/", array_slice(explode("\\", __DIR__), 0, -$profundidadePastaAtual));

include_once ($pastaInicial . '/private/database.php');
include_once ($pastaInicial . '/private/credentials.php');
include_once ($pastaInicial . '/private/vendor/autoload.php');
include_once ($pastaInicial . '/private/utils/general.php');

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

// Parâmetro POST email-recover
$email_recover = isset($_POST["email-recover"]) ?
    $_POST["email-recover"] :
    null;

if ($email_recover != null) {

}