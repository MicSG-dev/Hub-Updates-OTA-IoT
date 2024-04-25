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

executarFuncoesDeTodasPaginas($host, $username, $password, $database, $emailDemoAccount, $senhaDemoAccount);

$token = isset($_COOKIE["key"]) ? $_COOKIE["key"] : null;

if (estaLogado($token, $chaveJwt, $versaoSistema) && !estaNaTokenBlackList($host, $username, $password, $database, $token)) {
    $infoJwt = getInfoTokenJwt($token, $chaveJwt);
    if ($infoJwt["sub"] == "demo") {
        header("Location: /novo-cadastro");
        die();
    } else {
        echo ($pageHtml);
    }
} else {
    header("Location: /login");
    die();
}
