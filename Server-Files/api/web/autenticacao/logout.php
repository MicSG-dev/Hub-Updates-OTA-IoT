<?php
define('database-acesso-privado-rv$he', TRUE);
$profundidadePastaAtual = 3;
$pastaInicial = implode("/", array_slice(explode("\\", __DIR__), 0, -$profundidadePastaAtual));

include_once ($pastaInicial . '/private/credentials.php');
include_once ($pastaInicial . '/private/database.php');
include_once ($pastaInicial . '/private/vendor/autoload.php');
include_once ($pastaInicial . '/private/utils/general.php');

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // obtém HTTP Cookie de chave 'key' contendo o token JWT
    $token = isset($_COOKIE["key"]) ?
        $_COOKIE["key"] :
        null;

    setcookie("key", "");
    if ($token != null) {
        addTokenToBlackList($host, $username, $password, $database, $token, $chaveJwt);
    }

    $response = new stdClass();
    $response->status = "OK";
    $json = json_encode($response);

    sendjson(200, $json);
} else {
    $response = new stdClass();
    $response->error = "ERRO_METODO_HTTP";
    $json = json_encode($response);
    sendjson(400, $json);
}
