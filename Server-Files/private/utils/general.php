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

function replaceSubstrByIdHtml($str, $replace, $idHtml, $endTagHtml)
{
    $inicioPosicaoStr = strpos($str, "id=\"$idHtml\"");
    if ($inicioPosicaoStr == 0) {
        return $str;
    }

    $inicioPosicaoStr = strpos($str, ">", $inicioPosicaoStr) + 1;

    $fimPosicaoStr = strpos($str, $endTagHtml, $inicioPosicaoStr);

    $length = $fimPosicaoStr - $inicioPosicaoStr;

    if ($fimPosicaoStr == 0) {
        return $str;
    }

    return substr_replace($str, $replace, $inicioPosicaoStr, $length);
}

function usernameEhValido($username)
{
    if (preg_match('/^(?!.*[._]{2})[a-z0-9]+(?:[._][a-z0-9]+)*$/', $username)) {
        return true;
    }
    return false;
}

function getParamInfoJwt($infoJwt, $param)
{
    if ($infoJwt != null) {
        return $infoJwt[$param];
    }

    return "";
}
