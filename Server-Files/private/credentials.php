<?php

if (!defined('database-acesso-privado-rv$he')) {
    // Acesso direto não permitido.
    die("Acesso direto ao \"private/credentials\" não permitido.");
} else {

    // OBRIGATÓRIO - ALTERE OS VALORES DAS SEGUINTES VARIÁVEIS
    $host = "localhost"; // o host do banco de dados
    $username = "micsg-tests"; // o username do banco de dados
    $password = "micsg-tests"; // a senha do banco de dados
    $chaveJwt = "micsg-tests"; // a chave secreta utilizada para assinar os tokens JWT (altere para qualquer termo, mas que seja SEGURO)
    $chaveCrypto = "def000001389ee37713008a0af012baa3fe9ad6ef1d5a7d69903062a0e4ccee2c9e3f7bee7dff13ac891ab2a915d84d7878e6cf062a11f18a81839e32402916ca733edc4"; // a chave secreta utilizada para criptografar os hash das senhas dos users (para gerar um valor válido siga os passos da documentação neste link: https://github.com/MicSG-dev/Hub-Updates-OTA-IoT#gerar-key-de-criptografia-da-biblioteca-defusephp-encryption)
    
    // OPCIONAL - ALTERE OS VALORES DAS SEGUINTES VARIÁVEIS
    $versaoSistema = "1.0"; // a versão do sistema (possibilita o ADMIN do servidor apagar as tabelas do banco de dados, ou quando fazer alguma alteração significativa, e, quando incrementar a versão do sistema, os usuários que estejam logados anteriormente estarão 'deslogados')
    $emailDemoAccount = "demo-hub@email.com"; // o e-mail da conta inicial para o gerente poder criar a conta dele
    $senhaDemoAccount = "senha-demo-hub"; // a senha da conta inicial para o gerente poder criar a conta dele (mínimo de 12 caracteres, máximo de 4096 caracteres)

    // NÃO ALTERAR
    $database = "hub_updates_ota_iot"; // (NÃO é necessário alterar) este é o nome da database no banco de dados 
}
