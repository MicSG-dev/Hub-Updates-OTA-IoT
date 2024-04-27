<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');
require('./private/vendor/autoload.php');

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

$token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;
setcookie("key", "");
if($token != null){
    addTokenToBlackList($host, $username, $password, $database, $token, $chaveJwt);
}

header("Location: /");
die();
