<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Cambie su avatar') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualice su foto de perfil.") }}
        </p>
    </header>
    
    <!-- Include Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    <!-- Include Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    <style>
        .avatar-container {
            display: flex;
            flex-wrap: nowrap; /* Mantiene todo en una sola fila */
            overflow-x: auto; /* Habilita el desplazamiento horizontal */
            padding: 10px 0; /* Espacio vertical */
            gap: 12px; /* Espacio entre avatares */
            scroll-snap-type: x mandatory; /* Mejora la experiencia de desplazamiento */
        }
        .avatar-item {
            flex: 0 0 auto; /* Mantiene el tamaño de cada avatar */
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box; /* Incluye padding y margin en el tamaño total */
            scroll-snap-align: start; /* Alineación al iniciar el desplazamiento */
        }
        .avatar-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 5px;
        }
        .avatar-item button {
            margin-top: 5px; /* Espacio entre imagen y botón */
            width: 100%; /* Botón ocupa todo el ancho */
            display: flex; /* Usar flex para centrar el contenido */
            justify-content: center; /* Centrar horizontalmente */
        }
    </style>

    <div class="container">
        <div class="avatar-container">
            @foreach ($avatars as $avatar)
            <div class="avatar-item">
                <form action="{{ route('profile.selectAvatar', basename($avatar)) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <img src="{{ URL::asset('avatars/' . $avatar->getRelativePathname()) }}" alt="avatar">
                    <button type="submit" class="btn btn-success btn-lg" title="Elegir foto">
                        <i class='bx bx-check'></i> 
                    </button>
                </form>    
            </div>
            @endforeach
        </div>
    </div>
</section>
