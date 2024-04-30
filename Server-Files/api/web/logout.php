<?php
define('database-acesso-privado-rv$he', TRUE);
$profundidadePastaAtual = 2;
$pastaInicial = pathInitial($profundidadePastaAtual);

function pathInitial($depth)
{
    $exploded = explode("\\", __DIR__);
    for ($i = 0; $i < $depth; $i++) {
        array_pop($exploded);
    }
    return implode("\\", $exploded);
}


include ($pastaInicial . '/private/credentials.php');
include ($pastaInicial . '/private/database.php');
include ($pastaInicial . '/private/vendor/autoload.php');

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

$token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;
setcookie("key", "");
if ($token != null) {
    addTokenToBlackList($host, $username, $password, $database, $token, $chaveJwt);
}



$response = new stdClass();
$response->status = "OK";

$json = json_encode($response);

http_response_code(200);
header("Content-Type: application/json; charset=utf-8");
echo ($json);
die();