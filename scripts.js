window.addEventListener("load", (event) => {
    //Inicializar AOS
    AOS.init();

});


// Esperar a que el DOM esté cargado
document.addEventListener('DOMContentLoaded', function () {
    // Obtener el botón del menú hamburguesa
    var navbarToggler = document.querySelector('.navbar-toggler');

    // Obtener todos los enlaces del menú
    var navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    // Agregar un controlador de eventos clic a cada enlace del menú
    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            // Verificar si el menú hamburguesa está abierto
            if (navbarToggler.getAttribute('aria-expanded') === 'true') {
                // Cerrar el menú hamburguesa
                navbarToggler.click();
            }
        });
    });

});


// Función para cambiar el elemento activo en el menú
function setActiveNav() {
    const sections = document.querySelectorAll("section[id]");
    const navbarItems = document.querySelectorAll(".navbar-nav .nav-item");

    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute("id");

        if (window.scrollY >= sectionTop - sectionHeight / 2) {
            navbarItems.forEach(item => {
                item.classList.remove("active");
            });

            const activeItem = document.querySelector(`.navbar-nav .nav-item a[href="#${sectionId}"]`);
            if (activeItem) {
                activeItem.parentElement.classList.add("active");
            }
        }
    });
}

// Evento de desplazamiento para cambiar el elemento activo en el menú
window.addEventListener("scroll", setActiveNav);
window.addEventListener("DOMContentLoaded", setActiveNav);


// Go to Top Button
const scrollToTop = () => {
    let showBtn = false;

    const handleScroll = () => {
        if (window.scrollY > 200) {
            showBtn = true;
            toTopButton.style.display = 'block'; // Muestra el botón
        } else {
            showBtn = false;
            toTopButton.style.display = 'none'; // Oculta el botón
        }
    };

    window.addEventListener("scroll", handleScroll);

    const goToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    };

    // Crear el elemento de flecha en JavaScript
    const toTopButton = document.createElement('div');
    toTopButton.className = 'to-top';
    toTopButton.innerHTML = `
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="to-top" height="1em"
            width="1em" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M3 19h18a1.002 1.002 0 0 0 .823-1.569l-9-13c-.373-.539-1.271-.539-1.645 0l-9 13A.999.999 0 0 0 3 19zm9-12.243L19.092 17H4.908L12 6.757z">
            </path>
        </svg>
    `;

    // Agregar el botón al documento
    document.body.appendChild(toTopButton);

    toTopButton.addEventListener('click', goToTop);
};

// scrollToTop(); // Llamada para activar el comportamiento

