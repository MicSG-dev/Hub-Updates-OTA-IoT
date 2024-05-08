# Hub Updates OTA IoT
[![wakatime](https://wakatime.com/badge/user/cd636938-f610-42ec-a8d5-990354cad321/project/018ec445-4e84-4d84-bd22-6ccec88679c4.svg)](https://wakatime.com/@sistemas_micsg)

Repositório do Hub de Atualizações OTA para dispositivos IoT remotos.

## Linguagens utilizadas
[![Group](https://github.com/MicSG-dev/Hub-Updates-OTA-IoT/assets/71986598/a4635ce8-a536-4ce2-9e28-bf992052c0c2)](#)

- **Back-end**: PHP;
- **Front-end**: HTML, JS, CSS e Framework Bootstrap;
- **Banco de de Dados**: MySQL;
- **Firmware Dispositivos IoT de Exemplo**: C, C++ e Framework Arduino;
- **Biblioteca para uso em Dispositivos IoT**: C, C++ e Framework Arduino.

## Dependências externas utilizadas


### Back-end
- Biblioteca php-jwt da firebase, Google: https://github.com/firebase/php-jwt
- Biblioteca php-encryption da defuse: https://github.com/defuse/php-encryption

### Front-end
- Biblioteca Chart.js da chartjs: https://github.com/chartjs/Chart.js


## Instruções para preparar este sistema para uso
### Bibliotecas PHP
Ao fazer download via .zip ou via git clone, deve-se instalar os packages composer. Para isso , siga o seguinte passo a passo:
1. Com o projeto baixado em seu computador, abra o terminal cmd dentro da pasta do projeto;
2. Navegue até a pasta Server-Files/private:
```
cd .\Server-Files\private\
```
3. Atualize os packages via Composer (caso não tenha o Composer em seu computador, faça o download no link oficial: https://getcomposer.org/download/):
```
composer update
```
Com os passos anteriores realizados, seu projeto conterá todos os arquivos necessários para o funcionamento do projeto.
### Credenciais do Sistema
Antes de iniciar o projeto no servidor, é necessário alterar as credenciais gerais do sistema. Para isso, siga o passo a passo à seguir:
1. Navegue até a pasta Server-Files/private:
```
cd .\Server-Files\private\
```
2. Abra o arquivo `credentials.php`;
3. Dentro do arquivo, altere o valor das variáveis que armazenam as credenciais:
```
    // OBRIGATÓRIO - ALTERE OS VALORES DAS SEGUINTES VARIÁVEIS
    $host = "localhost"; // o host do banco de dados
    $username = "micsg-tests"; // o username do banco de dados
    $password = "micsg-tests"; // a senha do banco de dados
    $pepperHash = "micsg-tests"; // o pepper para geração de hash das senhas dos users (altere para qualquer termo, mas que seja SEGURO)
    $chaveJwt = "micsg-tests"; // a chave secreta utilizada para assinar os tokens JWT (altere para qualquer termo, mas que seja SEGURO)
    $chaveCrypto = "SUBSTITUA_ESTE_VALOR_AQUI"; // a chave secreta utilizada para criptografar os hash das senhas dos users (para gerar um valor válido siga os passos da documentação)
    
    // OPCIONAL - ALTERE OS VALORES DAS SEGUINTES VARIÁVEIS
    $versaoSistema = "1.0"; // a versão do sistema (possibilita o ADMIN do servidor apagar as tabelas do banco de dados, ou quando fazer alguma alteração significativa, e, quando incrementar a versão do sistema, os usuários que estejam logados anteriormente estarão 'deslogados')
    $emailDemoAccount = "demo-hub@email.com"; // o e-mail da conta inicial para o gerente poder criar a conta dele
    $senhaDemoAccount = "senha-demo-hub"; // a senha da conta inicial para o gerente poder criar a conta dele (mínimo de 12 caracteres, máximo de 4096 caracteres)
```
### Gerar key de Criptografia da biblioteca defuse/php-encryption
Antes de iniciar o projeto no servidor, é necessário gerar e salvar em uma variável a key de Criptografia. Para isso, siga o passo á seguir:
1. Navegue até a pasta Server-Files/private (caso ainda não esteja nela):
```
cd .\Server-Files\private\
```
2. Execute o comando à seguir para gerar uma chave de criptografia aleatória e à imprime na saída do prompt de comando:
```
vendor/bin/generate-defuse-key
```
3. Com a chave de criptografia gerada, copie ela e cole o seu valor na variável `$chaveCrypto` que está dentro do arquivo `credentials.php` (na pasta Server-Files/private):
```
$chaveCrypto = "SUBSTITUA_ESTE_VALOR_AQUI"; // a chave secreta utilizada para criptografar os hash das senhas dos users (para gerar um valor válido siga os passos da documentação)
```

Com os passos anteriores realizados, seu projeto está pronto para funcionar.