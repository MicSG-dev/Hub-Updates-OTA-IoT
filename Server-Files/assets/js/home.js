window.addEventListener('load', () => {

    document.getElementById('backToTop').style.visibility = "hidden";

    document.getElementById('mostrar-ajuda-grupo-atualizados').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-grupo-atualizados').focus();
    });


    document.getElementById('mostrar-ajuda-conexoes-devices').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-conexoes-devices').focus();
    });

    document.getElementById('mostrar-ajuda-atualizacoes-dispositivos').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-atualizacoes-dispositivos').focus();
    });

    window.addEventListener('scroll', function () {

        if (scrollY >= 20) {
            document.getElementById('backToTop').style.visibility = "visible"
        } else {
            document.getElementById('backToTop').style.visibility = "hidden";
        }
    });
});

