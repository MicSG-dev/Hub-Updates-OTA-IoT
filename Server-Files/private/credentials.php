<?php

if (!defined('database-acesso-privado-rv$he')) {
    // Acesso direto não permitido.
    die("Acesso direto ao \"private/credentials\" não permitido.");
} else {

    $host = "localhost"; // o host do banco de dados
    $username = "micsg-tests"; // o username do banco de dados
    $password = "micsg-tests"; // a senha do banco de dados
    $pepperHash = "micsg-tests"; // o pepper para geração de hash das senhas dos users (altere para qualquer termo, mas que seja SEGURO)
    $chaveJwt = "micsg-tests"; // a chave secreta utilizada para assinar os tokens JWT (altere para qualquer termo, mas que seja SEGURO)
    $database = "hub_updates_ota_iot"; // (NÃO é necessário alterar) este é o nome da database no banco de dados 
}
