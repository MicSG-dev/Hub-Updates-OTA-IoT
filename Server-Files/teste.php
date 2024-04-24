<?php
define('database-acesso-privado-rv$he', TRUE);

require ('./private/database.php');
require ('./private/credentials.php');
require ('./private/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

isset($_GET["token"]) ? $token = $_GET["token"] : $token = null;

if ($token != null) {
    echo ("token: $token");
    try {
        $decoded = JWT::decode($token, new Key($chaveJwt, 'HS256'));
        print_r($decoded);
    } catch (ExpiredException $e) {
        // errors having to do with environmental setup or malformed JWT Keys
        echo ("</br></br>Expirado");
    } catch (SignatureInvalidException $e) {
        // errors having to do with environmental setup or malformed JWT Keys
        echo ("</br></br>Assinatura inválida");
    } catch (Exception $e) {
        // errors having to do with JWT signature and claims
        print_r("</br></br>" . $e);
    }
} else {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type,      Accept");
    header("Content-Type: application/json");

    $headers = getallheaders();
    //print_r($headers);
    echo (time());
    echo ("\n");
    
    $expire = new DateTime();
    $expire = new DateTime($expire->format('Y-m-d H:i:s'), new DateTimeZone('America/Sao_Paulo'));
    
    echo ($expire->format('U'));
}
