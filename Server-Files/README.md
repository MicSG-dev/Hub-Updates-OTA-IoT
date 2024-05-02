# Rascunho Hub Updates OTA IoT - Server-Files

- Back-end:
    - Alterar variável de controle de acesso da pasta private (define('database-acesso-privado-rv$he', TRUE);) para ser possível alterar seu valor em credentials.php
    - Testar login com token, esta apresentando erro (ver token na black list e fora tambem)
    - Adicionar uma verificação de e-mail com código para provar que é o usuário detentor do e-mail APÓS a solicitação de novo cadastro bem-sucedida;

- Front-end:
    - Corrigir inclusão incorreta da navbar em páginas não necessárias;
    - navbar.js: atualizar script pelo atual
    - general.js: atualizar script pelo atual

- Problemas no sistema à corrgir:
    - Ao fazer uma nova solitação de cadastro no sistema e tiver dentro das regras receberei um 'OK' (mesmo se o email tiver sido repetido, nas duas tabelas). Sem alterar nenhum dado e tentar pela segunda vez receberei um 'OK' se não tiver sido bem-sucedida a solicitação de cadastro anterior (por causa do e-mail) e receberei um 'USER_EXISTS' se a solicitacao anterior tiver sido bem-sucedida. Desta forma, esta é uma brecha de segurança para hackers verificarem se um e-mail tem ou não cadastro no sistema.
        - Solução: Impedir do usuário informar um username na solicitação de cadastro no sistema, devendo o mesmo criar um ao entrar na conta (se o mesmo for devidamente aprovado para ser cadastrado no sistema). Esta restrição também deve ser aplicada ao usuário inicial tipo 'demo'.