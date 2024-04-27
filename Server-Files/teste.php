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
isset($_POST["password"]) ? $password = $_POST["password"] : $password = null;
isset($_POST["senha_pre"]) ? $senha_pre = $_POST["senha_pre"] : $senha_pre = null;

if ($hash != null && $senha != null && $email != null) {
    $result = tentaFazerLogin($host, $username, $password, $database, $email, $senha, $chaveCrypto);
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
    
   echo ("<h1>PHP Tests</h1>");
   
    $senha1 = $senha_pre;
    echo("<b>Senha antes: </b>".$senha1."<br><br>");
    $cifherHash = converterSenhaParaHash($senha1,$chaveCrypto);
    echo("<b>Senha do código em cifrada: </b>".$cifherHash."<br><br>");
    $hash = null;
    
    try {
        $key = KeyCrypto::loadFromAsciiSafeString($chaveCrypto);
        $hash = Crypto::decrypt($cifherHash, $key);
        
    } catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $ex) {}
    
    echo("<b>Hash obtido após descriptografar: </b>".$hash."<br><br>");

    $senha = $password == null? "": $password;
    echo("<b>Resultado: </b>");
    if (password_verify(hash('sha384', $senha, true), $hash)) {
        echo "Login efetuado com sucesso!"."<br><br>";
    } else {
        echo "Usuário ou Senha Incorreta!"."<br><br>";
    }

    echo("<b>Senha do param HTTP: </b>".$password."<br><br>");
    echo("<b>Tamanho da Senha do param HTTP: </b>".mb_strlen($password)."<br><br>");

}

