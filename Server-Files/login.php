<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');

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

if ($mode == "fazer-login") {

    if (strlen($senha_login) < 6 || strlen($senha_login) > 80) {
        http_response_code(400);
        echo ("SENHA");
    } else if (!filter_var($email_login, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo ("EMAIL");
    } else {

        $resultadoLogin = tentaFazerLogin($host, $username, $password, $database, $email_login, $senha_login);

        if ($resultadoLogin != -1) {

            $chaveJwt;
            $payload = [
                "name" => $resultadoLogin["nome"],
                "role" => $cargo_id
            ];

            http_response_code(200);
            echo ("OK");
        } else {
            http_response_code(400);
            echo ("FAILED_LOGIN");
        }
    }
} else {
    echo ($pageHtml);
}
