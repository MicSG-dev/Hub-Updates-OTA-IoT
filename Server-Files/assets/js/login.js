window.addEventListener('load', () => {
    document.getElementById("form-login").addEventListener("submit", (event) => {
        event.preventDefault();

        let form = document.getElementById('form-novo-cadastro');
        let formData = new FormData(form);
        formData.set("mode", "fazer-login");

        const req = new XMLHttpRequest();
        req.addEventListener('load', () => {

            if (req.status == 400) {

                let title, message;
                if (req.responseText == "EMAIL") {
                    title = "E-mail inválido";
                    message = "Não foi possível validar o e-mail informado. Por favor tente novamente, informando um e-mail válido.";
                } else if (req.responseText == "SENHA") {
                    title = "Senha inválida";
                    message = "Não foi possível validar a senha informada. Por favor tente novamente, informando uma senha com no mínimo 6 caracteres e com no máximo 80 caracteres.";
                } else {
                    title = "Erro desconhecido";
                    message = "Erro desconhecido: Por favor, informe o suporte sobre este erro (CODE: RETURN_INVALID)";
                }

                document.getElementById("title-modal").innerText = title;
                document.getElementById("content-modal").innerText = message;
                bootstrap.Modal.getOrCreateInstance('#modal').show();

            } else if (req.status == 200) {
                window.location.href = '/';
            }

        });

        req.open("POST", "/novo-cadastro", true);

        req.send(formData);
    });
});