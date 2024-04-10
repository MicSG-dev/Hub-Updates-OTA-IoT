window.addEventListener('load', ()=>{

    const carouselElement = document.querySelector('#carousel');
    carouselElement.classList.remove("slide");
    


    this.document.getElementById('gerar-codigo-redefinicao').addEventListener('click', ()=>{
        // fazer request POST na API
        // ...

        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(1);
    });

    this.document.getElementById('verificar-codigo-redefinicao').addEventListener('click', ()=>{
        // fazer request POST na API
        // ...

        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(2);
    });

    this.document.getElementById('form-gerar-codigo').addEventListener('submit', (event)=>{
        event.preventDefault();
    });

    this.document.getElementById('form-verificar-codigo').addEventListener('submit', (event)=>{
        event.preventDefault();
    });

    this.document.getElementById('form-redefinir-senha').addEventListener('submit', (event)=>{
        event.preventDefault();
    });

});