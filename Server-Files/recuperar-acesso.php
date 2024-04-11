<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');

$nomeArquivoHtml = basename(__FILE__ );
$prePath = str_replace($nomeArquivoHtml,"",__FILE__);
$interPath = "private\\html\\";
$fullPath = $prePath . $interPath . $nomeArquivoHtml;

$fullPath = str_replace('.php','.html', $fullPath);
$pageHtml = file_get_contents($fullPath);

verificarIntegridadeDatabaseSeNaoExistir($host, $username, $password);
verificarIntegridadeTabelaRedefinicaoSenha($host, $username, $password);

// verificar se usuário ESTA ou não logado. Se estiver logado, redirecionar ele para home. Se NÃO estiver logado, continuar.

isset($_POST["email-recover"]) ? $email_recover = $_POST["email-recover"] : $email_recover = null;

if ($email_recover != null) {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {
        // verificar se email esta cadastrado
        //  se estiver cadastrado, enviar um email de recuperação
        //  se não, não fazer nada
        http_response_code(200);
        echo ("OK");
    } else {
        http_response_code(400);
        echo ("Email invalido");
    }
} else {
    
    echo ($pageHtml);
}
