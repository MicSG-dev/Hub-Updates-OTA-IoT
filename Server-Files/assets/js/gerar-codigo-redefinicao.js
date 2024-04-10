window.addEventListener('load', ()=>{
    this.document.getElementById('gerar-codigo-redefinicao').addEventListener('click', ()=>{
        // fazer request POST na API
        // ...

        const carouselElement = document.querySelector('#carousel');
        const carousel = bootstrap.Carousel.getOrCreateInstance(carouselElement);
        carousel.to(1);
    });

    this.document.getElementById('form-redefinicao').addEventListener('submit', (event)=>{
        event.preventDefault();
    });
});