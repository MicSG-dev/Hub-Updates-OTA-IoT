<?php

$nomeArquivoHtml = basename(__FILE__ );
$prePath = str_replace($nomeArquivoHtml,"",__FILE__);
$interPath = "private\\html\\";

$fullPath = str_replace('.php','.html', $prePath . $interPath . $nomeArquivoHtml);
$pageHtml = file_get_contents($fullPath);

echo($pageHtml);