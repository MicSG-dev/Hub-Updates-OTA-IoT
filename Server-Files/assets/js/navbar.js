window.addEventListener('load', () => {

    let subPageTitle = document.body.title;
    document.title = subPageTitle + " - " + document.title;

    let elNavItens = document.getElementById('navbarToggler').getElementsByTagName('ul')[0].children;
    console.log(elNavItens);
    
    for (let index = 0; index < elNavItens.length; index++) {
        const element = elNavItens[index];
        console.log(element.innerText);
        if(element.innerText == subPageTitle){
            element.firstChild.classList.add('active');
            return;
        }
    }

});