window.addEventListener('load', () => {

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

    document.getElementById('mostrar-ajuda-dispositivos-cadastrados').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-dispositivos-cadastrados').focus();
    });

    document.getElementById('mostrar-ajuda-grupo-dispositivos').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-grupo-dispositivos').focus();
    });

    document.getElementById('mostrar-ajuda-dispositivos-atualizados').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-dispositivos-atualizados').focus();
    });

    document.getElementById('mostrar-ajuda-atualizacoes-pendentes').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-atualizacoes-pendentes').focus();
    });
});

