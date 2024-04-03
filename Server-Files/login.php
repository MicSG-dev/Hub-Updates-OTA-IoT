<?php

$nomeArquivoHtml = str_replace('/','', $_SERVER['SCRIPT_NAME']);
$nomeArquivoHtml = str_replace('.php','.html', $nomeArquivoHtml);
$pageHtml = file_get_contents("./$nomeArquivoHtml");

echo($pageHtml);