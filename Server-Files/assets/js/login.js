window.addEventListener('load', () => {
    document.getElementById("form-login").addEventListener("submit", (event) => {
        event.preventDefault();

        let form = document.getElementById('form-login');
        let formData = new FormData(form);
        formData.set("mode", "fazer-login");

        const req = new XMLHttpRequest();
        req.addEventListener('load', () => {

            if (req.status == 400) {

                let title, message;
                if (req.responseText == "EMAIL") {
                    title = "E-mail inválido";
                    message = "Não foi possível validar o e-mail informado. Por favor tente novamente, informando um e-mail válido.";
                } else if (req.responseText == "PASS") {
                    title = "Senha inexistente";
                    message = "Nenhuma senha foi fornecida. Tente novamente informando uma senha.";
                } else if (req.responseText == "PASS_MIN") {
                    title = "Senha fraca";
                    message = "A senha fornecida é muito fraca. Tente novamente informando uma com pelo menos 12 caracteres.";
                } else if (req.responseText == "PASS_MAX") {
                    title = "Senha muito grande";
                    message = "A senha fornecida é muito grande. Tente novamente informando uma com no máximo 4096 caracteres.";
                } else if (req.responseText == "FAILED_LOGIN") {
                    title = "Login não efetuado";
                    message = "Não foi possível fazer login com o e-mail e a senha informados. Por favor tente novamente, informando a senha e e-mail corretos.";
                } else if (req.responseText == "JA_LOGADO") {
                    window.location.href = '/';
                } else if (req.responseText == "DEMO_REDEF") {
                    window.location.href = '/novo-cadastro';
                } else {
                    title = "Erro desconhecido";
                    message = "Erro desconhecido: Por favor, informe o suporte sobre este erro (CODE: RETURN_INVALID)";
                }

                if (title != null && message != null) {
                    document.getElementById("title-modal").innerText = title;
                    document.getElementById("content-modal").innerText = message;
                    bootstrap.Modal.getOrCreateInstance('#modal').show();
                }

            } else if (req.status == 200) {
                
                let params = new URLSearchParams(document.location.search);
                let urlRedirect = params.get("redirect");

                if(urlRedirect != null && urlRedirect[0] == "/"){
                    console.log(window.location.host + urlRedirect);
                    window.location.href = window.location.protocol + "//" +window.location.host + urlRedirect;
                }else{
                    window.location.href = '/';
                }
                
            }

        });

        req.open("POST", "/login", true);

        req.send(formData);
    });
});