<?php

$nomeArquivoHtml = __FILE__;
$nomeArquivoHtml = str_replace('.php','.html', $nomeArquivoHtml);
$pageHtml = file_get_contents($nomeArquivoHtml);

echo($pageHtml);