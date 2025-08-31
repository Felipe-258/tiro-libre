
document.addEventListener('DOMContentLoaded', function () {
    const dropdownBtn = document.querySelector('.dropdown-btn'); // Asume que este es el botón para desplegar
    const dropdownContent = document.querySelector('.dropdown-content'); // El contenido desplegable

    dropdownBtn.addEventListener('click', () => {
        dropdownContent.classList.toggle('show');
    });
});
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('#content nav .bx-menu');

    // Function to check if the device is mobile
    function isMobile() {
        return window.innerWidth < 500; // Adjust threshold as needed
    }

    // Function to update sidebar visibility
    function updateSidebarVisibility() {
        if (isMobile()) {
            console.log('Using mobile');
            sidebar.classList.add('hide'); // Hide sidebar on mobile
        } else {
            console.log('Not mobile');
            sidebar.classList.remove('hide'); // Show sidebar on larger screens
        }
    }

    // Toggle sidebar on menu button click
    menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hide');
    });

    // Initial check for sidebar visibility
    updateSidebarVisibility();

    // Check sidebar visibility on window resize
    window.addEventListener('resize', updateSidebarVisibility);
});

// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {
    const switchMode = document.getElementById('switch-mode');
    const logoImg = document.getElementById('logo-img'); // Referencia a la imagen del logo
    const lightImg = logoImg.getAttribute('data-light-img'); // Obtén la URL de la imagen light
    const darkImg = logoImg.getAttribute('data-dark-img'); // Obtén la URL de la imagen dark

    // Recupera el estado del modo de la sesión
    const darkMode = sessionStorage.getItem('darkMode') === 'true';

    // Aplica el modo oscuro y cambia la imagen si está almacenado en la sesión
    if (darkMode) {
        document.body.classList.add('dark');
        switchMode.checked = true;
        logoImg.src = darkImg; // Cambia la imagen al modo oscuro
    }

    switchMode.addEventListener('change', function () {
        if (this.checked) {
            document.body.classList.add('dark');
            sessionStorage.setItem('darkMode', 'true');
            logoImg.src = darkImg; // Cambia la imagen al modo oscuro
        } else {
            document.body.classList.remove('dark');
            sessionStorage.setItem('darkMode', 'false');
            logoImg.src = lightImg; // Vuelve a la imagen original
        }
    });
});

/* togglePhotos */
document.getElementById('togglePhotos').addEventListener('click', function () {
    var photosContainer = document.getElementById('photosContainer');
    if (photosContainer.style.display === 'none') {
        photosContainer.style.display = 'block';
    } else {
        photosContainer.style.display = 'none';
    }
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
})


const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
    if (window.innerWidth < 576) {
        e.preventDefault();
        searchForm.classList.toggle('show');
        if (searchForm.classList.contains('show')) {
            searchButtonIcon.classList.replace('bx-search', 'bx-x');
        } else {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
        }
    }
})



if (window.innerWidth < 768) {
    sidebar.classList.add('hide');
} else if (window.innerWidth > 576) {
    searchButtonIcon.classList.replace('bx-x', 'bx-search');
    searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
    if (this.innerWidth > 576) {
        searchButtonIcon.classList.replace('bx-x', 'bx-search');
        searchForm.classList.remove('show');
    }
})



document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.delete-room').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío por defecto

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará la sala de manera permanente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Enviar el formulario si se confirma
                }
            });
        });
    });
});

$(document).ready(function () {
    $('#toggle-button').click(function () {
        $('#sidebar').toggleClass('hide');
    });
});

