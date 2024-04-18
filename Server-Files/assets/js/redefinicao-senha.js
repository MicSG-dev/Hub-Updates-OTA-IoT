window.addEventListener('load', () => {

    const carouselElement = document.querySelector('#carousel');
    carouselElement.classList.remove("slide");

    var idTimer;

    carouselElement.addEventListener("slid.bs.carousel", (event) => {
        if (event.to == 1) {
            document.getElementById("firstInputCode").focus();
            idTimer = setInterval(() => {

                let email = new FormData(document.getElementById('form-gerar-codigo')).get("email");

                if (email != "") {

                    let formData = new FormData();
                    formData.set("email-recover", email);
                    formData.set("mode", "time-next-generate-code");

                    const req = new XMLHttpRequest();
                    req.addEventListener('load', () => {

                        if (req.status == 200) {

                            const json_obj = JSON.parse(req.responseText);

                            document.getElementById("timeGenerateNewCode").innerText = (json_obj.m < 10 ? "0" + json_obj.m : json_obj.m) + ":" + (json_obj.s < 10 ? "0" + json_obj.s : json_obj.s);

                            if (json_obj.m == 0 && json_obj.s == 0) {
                                document.getElementById("nao-recebeu-codigo").classList.remove("disabled");
                            } else {
                                document.getElementById("nao-recebeu-codigo").classList.add("disabled");
                            }

                        } else if (req.status == 400) {
                            document.getElementById("title-modal").innerText = "E-mail inválido ou inexistente";
                            document.getElementById("content-modal").innerText = "Não foi possível validar o e-mail informado no campo anterior. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
                            bootstrap.Modal.getOrCreateInstance('#modal').show();
                            console.log("Email inválido");
                            clearInterval(idTimer);
                            document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 100);
                            });

                            console.log("Email inválido");
                        }

                    });

                    req.open("POST", "/recuperar-acesso", true);

                    req.send(formData);

                } else {

                    clearInterval(idTimer);
                    document.getElementById("title-modal").innerText = "E-mail inexistente - Erro de Sistema";
                    document.getElementById("content-modal").innerText = "Não foi possível identificar o e-mail informado no campo anterior. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
                    bootstrap.Modal.getOrCreateInstance('#modal').show();

                    document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    });

                    console.log("Email não encontrado");
                }

            }, 1000);
        } else {
            clearInterval(idTimer);
        }
    });

    this.document.getElementById('gerar-codigo-redefinicao').addEventListener('click', () => {

        let form = document.getElementById('form-gerar-codigo');
        let formData = new FormData(form);
        let email = formData.get("email");
        formData.set("email-recover", email);
        formData.set("mode", "generate-code");
        formData.delete("email");


        if (email != "") {
            // fazer request POST na API
            // ...

            const req = new XMLHttpRequest();
            req.addEventListener('load', () => {

                if (req.status == 200) {
                    //console.log("req.response : " + req.responseText);
                    //console.log("req.status: " + req.status);

                    const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
                    carousel.to(1);

                } else if (req.status == 400) {
                    document.getElementById("title-modal").innerText = "E-mail inválido";
                    document.getElementById("content-modal").innerText = "O E-mail é inválido. Por favor, corrija-o e tente novamente";
                    bootstrap.Modal.getOrCreateInstance('#modal').show();
                    console.log("Email inválido");
                }

            });

            req.open("POST", "/recuperar-acesso", true);

            req.send(formData);
        }
    });

    this.document.getElementById('verificar-codigo-redefinicao').addEventListener('click', () => {
        // fazer request POST na API
        // ...
        let email = new FormData(document.getElementById('form-gerar-codigo')).get("email");

        let code = "";
        let elementsPartitionInput = document.getElementsByClassName('partition_input');
        for (let index = 0; index < elementsPartitionInput.length; index++) {
            const value = elementsPartitionInput[index].value;
            code += value;
        }

        let formData = new FormData();
        formData.set("email-recover", email);
        formData.set("code", code);
        formData.set("mode", "verify-code");

        if (code != "") {
            const req = new XMLHttpRequest();

            req.addEventListener('load', () => {

                if (req.status == 200) {
                    //console.log("req.response : " + req.responseText);
                    //console.log("req.status: " + req.status);

                    const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
                    carousel.to(2);

                } else if (req.status == 400) {
                    if (req.responseText == "EMAIL") {
                        document.getElementById("title-modal").innerText = "E-mail inválido ou inexistente";
                        document.getElementById("content-modal").innerText = "Não foi possível validar o e-mail informado no campo anterior. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
                        bootstrap.Modal.getOrCreateInstance('#modal').show();

                        document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                            setTimeout(() => {
                                window.location.reload();
                            }, 100);
                        });

                        console.log("Email inválido");
                    } else if (req.responseText == "CODE") {
                        document.getElementById("title-modal").innerText = "Código inválido";
                        document.getElementById("content-modal").innerText = "Não foi possível comprovar que você realmente é proprietário da conta pois o código informado não é válido. A Recuperação de Senha foi cancelada. A página será recarregada, para nova tentativa de redefinição, quando esta mensagem for fechada.";
                        bootstrap.Modal.getOrCreateInstance('#modal').show();

                        document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                            setTimeout(() => {
                                window.location.reload();
                            }, 100);
                        });

                        console.log("Código inválido");
                    }

                }

            });

            req.open("POST", "/recuperar-acesso", true);

            req.send(formData);
        }else{
            document.getElementsByClassName('partition_input')[0].focus();
        }


    });

    this.document.getElementById('form-gerar-codigo').addEventListener('submit', (event) => {
        event.preventDefault();

    });

    this.document.getElementById('form-verificar-codigo').addEventListener('submit', (event) => {
        event.preventDefault();
    });

    this.document.getElementById('form-redefinir-senha').addEventListener('submit', (event) => {
        event.preventDefault();
    });



    this.document.getElementById('nao-recebeu-codigo').addEventListener('click', (event) => {
        event.preventDefault();

        let email = new FormData(document.getElementById('form-gerar-codigo')).get("email");

        if (email != "") {

            let formData = new FormData();
            formData.set("email-recover", email);
            formData.set("mode", "regenerate-code");

            const req = new XMLHttpRequest();
            req.addEventListener('load', () => {

                if (req.status == 400) {
                    document.getElementById("title-modal").innerText = "E-mail inválido ou Inexistente";
                    document.getElementById("content-modal").innerText = "Não foi possível validar o e-mail informado no campo anterior. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
                    bootstrap.Modal.getOrCreateInstance('#modal').show();

                    document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    });

                    console.log("Email inválido/inexistente");
                }

            });

            req.open("POST", "/recuperar-acesso", true);

            req.send(formData);

        } else {

            clearInterval(idTimer);
            document.getElementById("title-modal").innerText = "E-mail inexistente - Erro de Sistema";
            document.getElementById("content-modal").innerText = "Não foi possível identificar o e-mail informado no campo anterior. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
            bootstrap.Modal.getOrCreateInstance('#modal').show();

            document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            });

            console.log("Email não encontrado");
        }
    });

    this.document.getElementById('cancelar-redefinicao-senha').addEventListener('click', (event) => {
        event.preventDefault();

        let email = new FormData(document.getElementById('form-gerar-codigo')).get("email");

        let code = "";
        let elementsPartitionInput = document.getElementsByClassName('partition_input');
        for (let index = 0; index < elementsPartitionInput.length; index++) {
            const value = elementsPartitionInput[index].value;
            code += value;
        }

        let formData = new FormData();
        formData.set("email-recover", email);
        formData.set("code", code);
        formData.set("mode", "cancel-recover");

        const req = new XMLHttpRequest();
            req.addEventListener('load', () => {

                if(req.status == 200){
                    document.getElementById("title-modal").innerText = "Operação realizada com sucesso!";
                    document.getElementById("content-modal").innerText = "O cancelamento da Redefinição de Senha ocorreu com sucesso. O código gerado anteriormente foi invalidado e não será possível reutilizá-lo novamente para redefinir sua senha. Para refazer o processo de Redefinição de senha, reinicie o processo. Você será redirecionado para a página principal, quando esta mensagem for fechada.";
                    bootstrap.Modal.getOrCreateInstance('#modal').show();

                    document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                        window.location.href = '/';
                    });

                    console.log("Redefinição de senha cancelada com sucesso!");
                }
                else if (req.status == 400) {
                    document.getElementById("title-modal").innerText = "E-mail ou Código inválido/Inexistente";
                    document.getElementById("content-modal").innerText = "Não foi possível validar o e-mail e/ou código informado(s) anteriormente. Por favor tente novamente. A página será recarregada, para a nova tentativa, quando esta mensagem for fechada.";
                    bootstrap.Modal.getOrCreateInstance('#modal').show();

                    document.getElementById("modal").addEventListener("hide.bs.modal", () => {
                        setTimeout(() => {
                            window.location.reload();
                        }, 100);
                    });

                    console.log("Email inválido/inexistente");
                }

            });

            req.open("POST", "/recuperar-acesso", true);

            req.send(formData);

    });
});