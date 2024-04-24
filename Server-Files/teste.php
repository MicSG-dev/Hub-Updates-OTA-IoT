<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/credentials.php');

isset($_GET["senha"]) ? $senha = $_GET["senha"] : $senha = null;

if ($senha != null) {
    echo ("senha: $senha<br>");

    $senhaComPepper = hash_hmac("sha256", $senha, $pepperHash);

    $options = [
        'cost' => 11,
    ];

    $hash = password_hash($senhaComPepper, PASSWORD_DEFAULT, $options);
    echo ("hash: $hash<br>");
} else {
   
    echo ("nada");
}
