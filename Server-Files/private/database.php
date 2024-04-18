<?php
if (!defined('database-acesso-privado-rv$he')) {
    // Acesso direto não permitido.
    die("Acesso direto ao \"private/database\" não permitido.");
} else {

    function executarFuncoesDeTodasPaginas($host, $username, $password)
    {
        verificarIntegridadeDatabaseSeNaoExistir($host, $username, $password);
        verificarIntegridadeTabelaRedefinicaoSenha($host, $username, $password);
        verificarIntegridadeTabelaUsuarios($host, $username, $password);
    }

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
        `COD_REDEF` VARCHAR(6), 
        `TIME_REDEF` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        PRIMARY KEY (`EMAIL`)) ENGINE = InnoDB;
        ";
        $mysqli->query($query);
        $mysqli->close();
    }

    function verificarIntegridadeTabelaUsuarios($host, $username, $password)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password);
        } catch (mysqli_sql_exception) {
            echo ("<script> alert('Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: \\n\\nErro de Credenciais no Banco de Dados (USERBD_PASS).');</script>");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("<script> console.error('O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: \\n\\nErro de conexão ao Banco de Dados (USERBD_PASS).');</script>");
        }

        // Executar Query aqui
        // ...
        // ...

        $query = "CREATE TABLE IF NOT EXISTS `hub_updates_ota_iot`.`usuarios` 
        (`ID` INT NOT NULL AUTO_INCREMENT, 
        `NOME` VARCHAR(50) NOT NULL UNIQUE, 
        `EMAIL` VARCHAR(256) NOT NULL , 
        `DATA_INSCRICAO` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
        `SENHA` VARCHAR(80) NOT NULL , 
        PRIMARY KEY (`ID`)) ENGINE = InnoDB;
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


        if ($row["COUNT(*)"] == 0) {
            $stmt = $mysqli->prepare("INSERT INTO redefinir_senha(email, cod_redef) VALUES (?, ?)");
            $stmt->bind_param("ss", $email_recuperacao, $codigoSeisDigitos);
            $stmt->execute();
        }
    }

    function emailEstaCadastradoNoSistema($host, $username, $password, $database, $email)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (EMAIL_IN_DB_VERIFY)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (EMAIL_IN_DB_VERIFY)");
        }

        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM usuarios WHERE email = (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row["COUNT(*)"] == 1) {
            return true;
        }

        return false;
    }

    function getTimeRedef($host, $username, $password, $database, $email_recover)
    {

        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (GETIM_REDEF)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (GETIM_REDEF)");
        }


        $stmt = $mysqli->prepare("SELECT time_redef FROM redefinir_senha WHERE email = (?)");
        $stmt->bind_param("s", $email_recover);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($stmt->affected_rows != 0) {
            $timeRedef = $row['time_redef'];
        } else {
            $timeRedef = "0"; // Se não tem nenhuma conta para recuperar acesso, o usuário está liberado para gerar um novo código.
        }

        return $timeRedef;
    }

    function estaJaParaRedefinir($host, $username, $password, $database, $email_recover)
    {
        $result = null;

        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (VERI_EXIST_REDEF)");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (VERI_EXIST_REDEF)");
        }

        $stmt = $mysqli->prepare("SELECT * FROM redefinir_senha WHERE email = (?)");
        $stmt->bind_param("s", $email_recover);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($stmt->affected_rows != 0) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    function regenerateCodeRecover($host, $username, $password, $database, $codigo, $email_recover)
    {
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

        if($codigo != "" && $codigo != null){
            $stmt = $mysqli->prepare("UPDATE redefinir_senha set cod_redef = (?) WHERE email = (?)");
            $stmt->bind_param("ss", $codigo, $email_recover);
        }else{
            $stmt = $mysqli->prepare("UPDATE redefinir_senha set time_redef = CURRENT_TIMESTAMP() WHERE email = (?)");
            $stmt->bind_param("s", $email_recover);
        }
       
        
        $stmt->execute();
    }

    function verificarCodigoRedefinicaoSenha($host, $username, $password, $database, $codigo, $email_recover)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (VERI_COD_REF) ");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (VERI_COD_REF) ");
        }

        if ($codigo != "" && $codigo != null) {
            $stmt = $mysqli->prepare("SELECT * FROM redefinir_senha WHERE email = (?) AND cod_redef = (?)");
            $stmt->bind_param("ss", $email_recover, $codigo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($stmt->affected_rows == 0) {
                $stmt = $mysqli->prepare("DELETE FROM redefinir_senha WHERE email = (?)");
                $stmt->bind_param("s", $email_recover);
                $stmt->execute();
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    function efetuarCancelamentoCodigoRedefinicao($host, $username, $password, $database, $code_recover, $email_recover)
    {
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (CANCEL_COD_REDEF) ");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (CANCEL_COD_REDEF) ");
        }

        if($code_recover != "" && $code_recover != null){
            $stmt = $mysqli->prepare("DELETE FROM redefinir_senha WHERE email = (?) AND cod_redef = (?)");
            $stmt->bind_param("ss", $email_recover, $code_recover);
            $stmt->execute();
        }        
    }

    function redefinirSenha($host, $username, $password, $database, $code_recover, $email_recover, $senha){
        $mysqli = null;

        try {
            $mysqli = new mysqli($host, $username, $password, $database);
        } catch (mysqli_sql_exception) {
            echo ("Não foi possível continuar. Informe o seguinte erro ao Administrador do Sistema: Erro de Credenciais no Banco de Dados (CANCEL_COD_REDEF) ");
            exit();
        }

        if ($mysqli->connect_errno) {
            echo ("O sistema apresentou um erro. Informe ao Administrador do Sistema. ERRO: Erro de conexão ao Banco de Dados (CANCEL_COD_REDEF) ");
        }
        return false;
        /começar aqui
    }
}
