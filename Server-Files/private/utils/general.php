<?php
function sendjson($httpCode, $json)
{
    if ($httpCode < 100 || $httpCode > 599) {
        throw new Exception("Código HTTP informado está fora do intervalo permitido.");
    } else if (json_decode($json) == null || json_decode($json) == false) {
        throw new Exception("JSON informado é inválido");
    } else {
        http_response_code($httpCode);
        header("Content-Type: application/json; charset=utf-8;");
        echo ($json);
    }
}