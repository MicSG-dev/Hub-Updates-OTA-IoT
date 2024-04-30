window.addEventListener('load', () => {
    document.getElementById('backToTop').style.visibility = "hidden";

    let subPageTitle = document.body.getAttribute('data-title');
    document.title = subPageTitle + " - " + document.title;

    window.addEventListener('scroll', function () {

        if (scrollY >= 20) {
            document.getElementById('backToTop').style.visibility = "visible"
        } else {
            document.getElementById('backToTop').style.visibility = "hidden";
        }
    });
});

