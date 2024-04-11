<?php

$nomeArquivoHtml = basename(__FILE__ );
$prePath = str_replace($nomeArquivoHtml,"",__FILE__);
$interPath = "private\\html\\";
$fullPath = $prePath . $interPath . $nomeArquivoHtml;

$fullPath = str_replace('.php','.html', $fullPath);
$pageHtml = file_get_contents($fullPath);

echo($pageHtml);