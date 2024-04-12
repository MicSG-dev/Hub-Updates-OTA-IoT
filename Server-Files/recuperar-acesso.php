<?php
define('database-acesso-privado-rv$he', TRUE);

require('./private/database.php');
require('./private/credentials.php');

$nomeArquivoHtml = basename(__FILE__);
$prePath = str_replace($nomeArquivoHtml, "", __FILE__);
$interPath = "private\\html\\";

$fullPath = str_replace('.php', '.html', $prePath . $interPath . $nomeArquivoHtml);
$pageHtml = file_get_contents($fullPath);

verificarIntegridadeDatabaseSeNaoExistir($host, $username, $password);
verificarIntegridadeTabelaRedefinicaoSenha($host, $username, $password);

// verificar se usuário ESTA ou não logado. Se estiver logado, redirecionar ele para home. Se NÃO estiver logado, continuar.

// Parâmetro POST email-recover
isset($_POST["email-recover"]) ? $email_recover = $_POST["email-recover"] : $email_recover = null;

// Parâmetro POST mode
isset($_POST["mode"]) ? $mode = $_POST["mode"] : $mode = null;

if ($email_recover != null && $mode == "generate-code") {

    if (filter_var($email_recover, FILTER_VALIDATE_EMAIL)) {
       
        // verificar se email esta cadastrado
        $temCadastro = emailEstaCadastradoNoSistema();

        // se email esta cadastrado, salvar no BD um código aleatório de 6 dígitos e enviar por email para o USER
        // se não estiver cadastrado, não fazer nada
        if($temCadastro){
            $codigo = gerarCodigoRedefinicaoSenhaUnico();
            salvarCodigoRedefinicaoSenha($codigo, $email_recover);
        }

        http_response_code(200);
        echo ("OK");
    } else {
        http_response_code(400);
        echo ("Email invalido");
    }
} else {

    echo ($pageHtml);
}



