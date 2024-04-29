window.addEventListener('load', () => {
    
    
    document.getElementById('mostrar-ajuda-grupo-atualizados').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-grupo-atualizados').focus();
    });

    
    document.getElementById('mostrar-ajuda-conexoes-devices').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('ajuda-conexoes-devices').focus();
    });
});