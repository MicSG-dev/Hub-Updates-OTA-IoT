window.addEventListener('load', () => {

    const carouselElement = document.querySelector('#carousel');
    carouselElement.classList.remove("slide");



    this.document.getElementById('gerar-codigo-redefinicao').addEventListener('click', () => {

        let form = document.getElementById('form-gerar-codigo');
        let formData = new FormData(form);
        let email = formData.get("email");


        if (email != "") {
            // fazer request POST na API
            // ...

            const req = new XMLHttpRequest();
            req.addEventListener('load', () => {
                console.log(this.responseText);
            });

            req.open("POST", "/recuperar-acesso");
            req.send();


            /*const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(1);*/
        }
    });

    this.document.getElementById('verificar-codigo-redefinicao').addEventListener('click', () => {
        // fazer request POST na API
        // ...

        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(2);
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

    this.document.getElementById('ja-tem-um-codigo').addEventListener('click', (event) => {
        event.preventDefault();
        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(1);
    });

    this.document.getElementById('nao-recebeu-codigo').addEventListener('click', (event) => {
        event.preventDefault();
        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(0);
    });



});