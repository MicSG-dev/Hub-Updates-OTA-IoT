window.addEventListener('load', () => {
    
    Array.prototype.forEach.call(document.getElementsByClassName('ajuda-secao'),(element) => {
        console.log(element)
        let popover = bootstrap.Popover.getOrCreateInstance(element);
        
        element.addEventListener("focus", () => {
            
            popover.show();
        });


        element.addEventListener("blur", () => {
            popover.hide();

        });

        element.addEventListener("click", (event) => {
            event.preventDefault();
            element.blur();
        });
    });
    


    

    
   
    
});