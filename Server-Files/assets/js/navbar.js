window.addEventListener('load', () => {

    let subPageTitle = document.body.getAttribute('data-title');
    document.title = subPageTitle + " - " + document.title;

    let elNavItens = document.getElementById('navbarToggler').getElementsByTagName('ul')[0].children;
    
    for (let index = 0; index < elNavItens.length; index++) {
        const element = elNavItens[index];
        
        if(element.innerText == subPageTitle){
            element.firstChild.classList.add('active');
            element.firstChild.setAttribute('href', '#');
            return;
        }else if(["Configurações",].includes(subPageTitle) && element.getElementsByClassName('dropdown').length != 0){
            element.getElementsByClassName('dropdown')[0].firstChild.classList.add('active')
        }   
    }

});