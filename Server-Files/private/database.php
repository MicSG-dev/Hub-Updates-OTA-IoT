<?php
if (!defined('database-acesso-privado-rv$he')) {
    // Acesso direto não permitido.
    die("Acesso direto ao \"private/database\" não permitido.");
} else {


    function verificarIntegridadeDatabaseSeNaoExistir($host, $username, $password)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password);
        } catch (mysqli_sql_exception) {
            echo ("<script> alert('Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: \\n\\nErro de Credenciais no Banco de Dados (DB).');</script>");
            exit();
        }


        if ($mysqli->connect_errno) {
            echo ("<script> console.error('O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: \\n\\nErro de conexão ao Banco de Dados (DB).');</script>");
        }

        $query = "CREATE DATABASE IF NOT EXISTS hub_updates_ota_iot;";
        $mysqli->query($query);
        $mysqli->close();
    }


    function verificarIntegridadeTabelaRedefinicaoSenha($host, $username, $password)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password);
        } catch (mysqli_sql_exception) {
            echo ("<script> alert('Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: \\n\\nErro de Credenciais no Banco de Dados (REDEF_PASS).');</script>");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("<script> console.error('O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: \\n\\nErro de conexão ao Banco de Dados (REDEF_PASS).');</script>");
        }

        // Executar Query aqui
        // ...
        // ...
    }
}
