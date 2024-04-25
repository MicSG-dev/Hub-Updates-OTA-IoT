<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');
require('./private/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$nomeArquivoHtml = basename(__FILE__);
$prePath = str_replace($nomeArquivoHtml, "", __FILE__);
$interPath = "private\\html\\";

$fullPath = str_replace('.php', '.html', $prePath . $interPath . $nomeArquivoHtml);
$pageHtml = file_get_contents($fullPath);

executarFuncoesDeTodasPaginas($host, $username, $password, $database);

// Parâmetro POST email
isset($_POST["email"]) ? $email_login = $_POST["email"] : $email_login = null;

// Parâmetro POST user
isset($_POST["password"]) ? $senha_login = $_POST["password"] : $senha_login = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

$token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;

if ($mode == "fazer-login") {

    if (!estaLogado($token, $chaveJwt)) {
        if (strlen($senha_login) < 6 || strlen($senha_login) > 80) {
            http_response_code(400);
            echo ("SENHA");
        } else if (!filter_var($email_login, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo ("EMAIL");
        } else {

            $resultadoLogin = tentaFazerLogin($host, $username, $password, $database, $email_login, $senha_login);

            if ($resultadoLogin != -1) {

                $payload = [
                    "name" => $resultadoLogin["nome"],
                    "role" => $resultadoLogin["cargo_id"],
                    "sub" => $resultadoLogin["username"],
                    "exp" => time() + 60 * 60 * 2, // 2 horas
                    "version" => $versaoSistema
                ];

                $token = JWT::encode($payload, $chaveJwt, 'HS256');

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
                    http_response_code(200);
                    echo ("OK");
                } else {
                    http_response_code(400);
                    echo ("DEMO_REDEF");
                }
            } else {
                http_response_code(400);
                echo ("FAILED_LOGIN");
            }
        }
    } else {
        http_response_code(400);
        echo ("JA_LOGADO");
    }
} else {
    if (estaLogado($token, $chaveJwt) && !estaNaTokenBlackList($host, $username, $password, $database, $token)) {
        $infoJwt = getInfoTokenJwt($token, $chaveJwt);
        if ($infoJwt["sub"] == "demo") {
            header("Location: /novo-cadastro");
        }else{
            header("Location: /");
        }
        
        die();
    } else {
        echo ($pageHtml);
    }
}
