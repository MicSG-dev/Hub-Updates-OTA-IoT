<?php

$nomeArquivoHtml = __FILE__;
$nomeArquivoHtml = str_replace('.php','.html', $nomeArquivoHtml);
$pageHtml = file_get_contents($nomeArquivoHtml);

// verificar se usuário ESTA ou não logado. Se estiver logado, redirecionar ele para home. Se NÃO estiver logado, continuar.

isset($_POST["email-recover"]) ? $email_recover = $_POST["email-recover"] : $email_recover = null;

if($email_recover != null){    
    
    if(filter_var($email_recover, FILTER_VALIDATE_EMAIL)){
        // verificar se email esta cadastrado
        //  se estiver cadastrado, enviar um email de recuperação
        //  se não, não fazer nada
        http_response_code(200);
        echo("OK");
        
    }else{
        http_response_code(400);
        echo("Email invalido");
    }
}else{
    echo($pageHtml);
}
