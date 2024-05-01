<?php

define('database-acesso-privado-rv$he', TRUE);
$profundidadePastaAtual = 2;
$pastaInicial = implode("/", array_slice(explode("\\", __DIR__), 0, -$profundidadePastaAtual));

include_once($pastaInicial . '/private/database.php');
include_once($pastaInicial . '/private/credentials.php');
include_once($pastaInicial . '/private/vendor/autoload.php');
include_once($pastaInicial . '/private/utils/general.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount, $chaveCrypto);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Parâmetro POST email
    isset($_POST["email"]) ? $email_login = $_POST["email"] : $email_login = null;

    // Parâmetro POST user
    isset($_POST["password"]) ? $senha_login = $_POST["password"] : $senha_login = null;

    $token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;



    if (!estaLogado($token, $chaveJwt, $versaoSistema) || estaNaTokenBlackList($host, $username, $password, $database, $token)) {
        if ($senha_login == null) {

            $response = new stdClass();
            $response->error = "PASS";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if (mb_strlen($senha_login) < 12) {

            $response = new stdClass();
            $response->error = "PASS_MIN";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if (mb_strlen($senha_login) > 4096) {

            $response = new stdClass();
            $response->error = "PASS_MAX";
            $json = json_encode($response);
            sendjson(400, $json);
        } else if ($email_login == null || !filter_var($email_login, FILTER_VALIDATE_EMAIL)) {

            $response = new stdClass();
            $response->error = "EMAIL";
            $json = json_encode($response);
            sendjson(400, $json);
        } else {

            $resultadoLogin = tentaFazerLogin($host, $username, $password, $database, $email_login, $senha_login, $chaveCrypto);

            if ($resultadoLogin != -1) {

                $payload = [
                    "name" => $resultadoLogin["nome"],
                    "role" => $resultadoLogin["cargo_id"],
                    "sub" => $resultadoLogin["username"],
                    "exp" => time() + 60 * 60 * 2, // 2 horas
                    "version" => $versaoSistema
                ];

                $token = JWT::encode($payload, $chaveJwt, 'HS384');

                setcookie(
                    "key",
                    $token,
                    [
                        "expires" => time() + (60 * 60 * 24 * 365), // 1 ano (tempo do cookie tem de ser maior que o do JWT, sendo que este tempo não terá relevância no sistema e somente do JWT)
                        "path" => '/',
                        "domain" => '',
                        "secure" => false,
                        "httponly" => true, // previne ataques XSS
                        "samesite" => 'Strict' // previne ataques CSRF
                    ]

                );

                if ($resultadoLogin["username"] != "demo") {

                    $response = new stdClass();
                    $response->status = "OK";
                    $json = json_encode($response);
                    sendjson(200, $json);
                } else {

                    $response = new stdClass();
                    $response->error = "DEMO_REDEF";
                    $json = json_encode($response);
                    sendjson(400, $json);
                }
            } else {

                $response = new stdClass();
                $response->error = "FAILED_LOGIN";
                $json = json_encode($response);
                sendjson(400, $json);
            }
        }
    } else {
        $response = new stdClass();
        $response->status = "JA_LOGADO";
        $json = json_encode($response);
        sendjson(200, $json);
    }
} else {
    $response = new stdClass();
    $response->status = "ERRO_METODO_HTTP";
    $json = json_encode($response);
    sendjson(400, $json);
}
