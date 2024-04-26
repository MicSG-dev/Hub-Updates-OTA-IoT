<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');
require('./private/vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key as KeyCrypto;


isset($_GET["senha"]) ? $senha = $_GET["senha"] : $senha = null;
isset($_GET["hash"]) ? $hash = $_GET["hash"] : $hash = null;
isset($_GET["email"]) ? $email = $_GET["email"] : $email = null;

if ($hash != null && $senha != null && $email != null) {
    $result = tentaFazerLogin($host, $username, $password, $database, $email, $senha, $pepperHash);
    if ($result != -1) {
        echo ("Logado!");
    }else{
        echo ("Nao Logado :(");
    }
} else if ($senha != null && $hash != null) {

    $senhaComPepper = hash_hmac("sha384", $senha, $pepperHash);
    
    if (password_verify($senhaComPepper, $hash)) {
        echo "Login efetuado com sucesso!";
    } else {
        echo "Usuário ou Senha Incorreta!";
    }
} 
else if ($senha != null) {
    echo ("senha: $senha<br>");

    $senhaComPepper = hash_hmac("sha384", $senha, $pepperHash);

    $options = [
        'cost' => 11,
    ];

    $hash = password_hash($senhaComPepper, PASSWORD_ARGON2ID, $options);
    echo ("hash: $hash<br>");

    if (password_verify($senhaComPepper, $hash)) {
        echo "Login efetuado com sucesso!";
    } else {
        echo "Usuário ou Senha Incorreta!";
    }
}else {
    echo ("PHP Tests<br>");
    $key = KeyCrypto::createNewRandomKey();
    print_r($key);
}

