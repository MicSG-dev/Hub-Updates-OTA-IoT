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

        $query = "CREATE TABLE IF NOT EXISTS `hub_updates_ota_iot`.`redefinir_senha` 
        (`EMAIL` VARCHAR(256) NOT NULL , 
        `COD_REDEF` VARCHAR(6) NOT NULL , 
        `TIME_REDEF` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        PRIMARY KEY (`EMAIL`)) ENGINE = InnoDB;
        ";
        $mysqli->query($query);
        $mysqli->close();
    }


    function salvarCodigoRedefinicaoSenha($host, $username, $password, $database, $codigoSeisDigitos, $email_recuperacao)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (SAVE_CODE_REDEF)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (SAVE_CODE_REDEF)");
        }


        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM redefinir_senha WHERE email = (?)");
        $stmt->bind_param("s", $email_recuperacao);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();


        if ($row["COUNT(*)"] != 1) {
            $stmt = $mysqli->prepare("INSERT INTO redefinir_senha(email, cod_redef) VALUES (?, ?)");
            $stmt->bind_param("ss", $email_recuperacao, $codigoSeisDigitos);
            $stmt->execute();
        }
    }

    function emailEstaCadastradoNoSistema()
    {
        //verificar se email esta cadastrado

        return true;
    }

    function getTimeRedef($host, $username, $password, $database, $email_recover){
       
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (SAVE_CODE_REDEF)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (SAVE_CODE_REDEF)");
        }


        $stmt = $mysqli->prepare("SELECT time_redef FROM redefinir_senha WHERE email = (?)");
        $stmt->bind_param("s", $email_recover);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($stmt->affected_rows != 0){
            $timeRedef = $row['time_redef'];
        }else{
            $timeRedef = "0"; // Se não tem nenhuma conta para recuperar acesso, o usuário está liberado para gerar um novo código.
        }
        
        return $timeRedef;
    }

    function estaJaParaRedefinir($host, $username, $password, $database, $email_recover){
        $result = null;

        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (SAVE_CODE_REDEF)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (SAVE_CODE_REDEF)");
        }

        $stmt = $mysqli->prepare("SELECT * FROM redefinir_senha WHERE email = (?)");
        $stmt->bind_param("s", $email_recover);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if($stmt->affected_rows != 0){
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    function regenerateCodeRecover($host, $username, $password, $database, $codigo, $email_recover){
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (REGEN_CODE_REDEF) ");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (REGEN_SAVE_CODE_REDEF) ");
        }
        
        $stmt = $mysqli->prepare("UPDATE redefinir_senha set cod_redef = (?) WHERE email = (?)");
        $stmt->bind_param("ss", $codigo, $email_recover);
        $stmt->execute();
        
    }
}
