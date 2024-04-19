window.addEventListener('load', () => {
    document.getElementById("form-novo-cadastro").addEventListener('submit', (event) => {
        event.preventDefault();

        let form = document.getElementById('form-novo-cadastro');
        let formData = new FormData(form);
        formData.set("mode", "solicitar-acesso");
        
        const req = new XMLHttpRequest();
        req.addEventListener('load', () => {

            if (req.status == 400) {

                let title, message;
                if(req.responseText == "EMAIL"){
                    title = "E-mail inválido";
                    message = "Não foi possível validar o e-mail informado. Por favor tente novamente, informando um e-mail válido.";
                }else if(req.responseText == "NOME"){
                    title = "Nome inválido";
                    message = "Não foi possível validar o nome informado. Por favor tente novamente, informando um nome com no mínimo 3 caracteres e com no máximo 256 caracteres.";
                }else if(req.responseText == "USER"){
                    title = "Nome inválido";
                    message = "Não foi possível validar o username informado. Por favor tente novamente, informando um username com no mínimo 3 caracteres e com no máximo 26 caracteres.";
                }else if(req.responseText == "USER_EXISTS"){
                    title = "Username não disponível";
                    message = "Não foi possível utilizar o username informado, pois o mesmo já está em uso ou sua utilização não é permitida. Por favor tente novamente, informando um username diferente.";
                }else{
                    title = "Erro desconhecido";
                    message = "Erro desconhecido: Por favor, informe o suporte sobre este erro (CODE: RETURN_INVALID)";
                }

                document.getElementById("title-modal").innerText = title;
                document.getElementById("content-modal").innerText = message;
                bootstrap.Modal.getOrCreateInstance('#modal').show();

            } else if (req.status == 200) {
                document.getElementById("title-modal").innerText = "Solicitação de cadastro realizada";
                document.getElementById("content-modal").innerText = "Caso seu e-mail não já esteja cadastrado em nosso sistema a sua solicitação de cadastro em nosso sistema foi efetuada com sucesso. A resposta desta solicitação efetuada pelo nosso Gerente será enviada em seu e-mail assim que feita. Caso necessário, envie as informações solicitadas no e-mail. Caso confirmada a sua solicitação, você também receberá um e-mail solicitando a definição da senha de sua conta.";
                bootstrap.Modal.getOrCreateInstance('#modal').show();

                document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 100);
                });
            }

        });

        req.open("POST", "/novo-cadastro", true);

        req.send(formData);
    });
});