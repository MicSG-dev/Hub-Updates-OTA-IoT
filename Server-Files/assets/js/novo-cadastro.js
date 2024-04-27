window.addEventListener('load', () => {

    document.getElementById("form-novo-cadastro").addEventListener('submit', (event) => {
        event.preventDefault();

        let form = document.getElementById('form-novo-cadastro');
        let formData = new FormData(form);
        formData.set("mode", "solicitar-acesso");

        const req = new XMLHttpRequest();
        req.addEventListener('load', () => {

            if (req.status == 400) {

                let title, message, messageHtml;
                if (req.responseText == "EMAIL") {
                    title = "E-mail inválido";
                    message = "Não foi possível validar o e-mail informado. Por favor tente novamente, informando um e-mail válido.";
                } else if (req.responseText == "NOME") {
                    title = "Nome inválido";
                    message = "Não foi possível validar o nome informado. Por favor tente novamente, informando um nome com no mínimo 3 caracteres e com no máximo 256 caracteres.";
                } else if (req.responseText == "USER") {
                    title = "Username inválido";
                    messageHtml = "<p>Não foi possível validar o username informado. Por favor tente novamente, informando um username válido seguindo as seguintes regras: </p>" +
                        "<ul><li>não são permitidos caracteres Maiúsculos;</li>" +
                        "<li>são permitidos caracteres númericos;</li>" +
                        "<li>são permitidos pontos, desde que inseridos somente no meio do username, nunca no início ou final;</li>" +
                        "<li>são permitidos underlines, desde que inseridos somente no meio do username, nunca no início ou final;</li>" +
                        "<li>o username deve ter no mínimo 3 caracteres;</li>" +
                        "<li>o username deve ter no máximo 26 caracteres.</li></ul>";
                } else if (req.responseText == "USER_EXISTS") {
                    title = "Username não disponível";
                    message = "Não foi possível utilizar o username informado, pois o mesmo já está em uso ou sua utilização não é permitida. Por favor tente novamente, informando um username diferente.";
                } else if (req.responseText == "PASS") {
                    title = "Senha inexistente";
                    message = "Nenhuma senha foi fornecida. Tente novamente informando uma senha.";
                } else if (req.responseText == "PASS_MIN") {
                    title = "Senha fraca";
                    message = "A senha fornecida é muito fraca. Tente novamente informando uma com pelo menos 12 caracteres.";
                } else if (req.responseText == "PASS_MAX") {
                    title = "Senha muito grande";
                    message = "A senha fornecida é muito grande. Tente novamente informando uma com no máximo 4096 caracteres.";
                } else {
                    title = "Erro desconhecido";
                    message = "Erro desconhecido: Por favor, informe o suporte sobre este erro (CODE: RETURN_INVALID)";
                }

                document.getElementById("title-modal").innerText = title;
                if (messageHtml != null)
                    document.getElementById("content-modal").innerHTML = messageHtml;
                if (message != null)
                    document.getElementById("content-modal").innerText = message;
                bootstrap.Modal.getOrCreateInstance('#modal').show();

            } else if (req.status == 200) {
                document.getElementById("title-modal").innerText = "Solicitação de cadastro realizada";
                document.getElementById("content-modal").innerText = "Caso seu e-mail não já esteja cadastrado em nosso sistema a sua solicitação de cadastro em nosso sistema foi efetuada com sucesso. A resposta desta solicitação efetuada será enviada em seu e-mail assim que feita. Caso necessário, envie as informações solicitadas no e-mail.";
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