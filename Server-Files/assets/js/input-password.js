window.addEventListener('load', ()=>{
    let btnsViewPassword = this.document.getElementsByClassName('view-password');
    for (let index = 0; index < btnsViewPassword.length; index++) {
        const btn = btnsViewPassword[index];
        let inputPassword = btn.parentElement.childNodes[0];
        let iconElement = btn.childNodes[0];
        btn.addEventListener('click', () => {

            var idInterval = setInterval(() => {


                if (document.activeElement !== btn && document.activeElement !== inputPassword || inputPassword.type == "password") {
                    inputPassword.type = "password";
                    iconElement.classList.remove("fa-eye");
                    iconElement.classList.add("fa-eye-slash");
                    clearInterval(idInterval);
                }

            }, 100);

            if (inputPassword.type == "password") {
                inputPassword.type = "text";
                iconElement.classList.remove("fa-eye-slash");
                iconElement.classList.add("fa-eye");
                inputPassword.focus()
            } else {
                inputPassword.type = "password";
                iconElement.classList.remove("fa-eye");
                iconElement.classList.add("fa-eye-slash");
                inputPassword.focus()
            }

        });


    }
});