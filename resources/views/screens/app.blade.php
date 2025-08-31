<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="{{ asset('bootstrap.min.css') }}">

    {{--     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
 --}} <!-- Boxicons -->

    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->

    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="{{ asset('script.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--     <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 --}}
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


        $(document).ready(function() {
            $('.protected-route').on('click', function(event) {
                event.preventDefault(); // Evita que el enlace se ejecute inmediatamente

                // Simulación de si el usuario está autenticado o no
                var isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

                if (!isAuthenticated) {
                    // Si no está autenticado, mostramos el SweetAlert
                    Swal.fire({
                        title: 'Atención',
                        text: 'Debes estar autenticado para acceder a esta página.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Iniciar sesión',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirigir al login
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                } else {
                    // Si está autenticado, redirigir al enlace original
                    window.location.href = $(this).attr('href');
                }
            });
        });
    </script>

    <title>Tiro Libre</title>
    <link rel="icon" type="image/x-icon" href=" {{ URL::asset('\logos\logo tiro-libre circle.png') }} ">
</head>

<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="{{ route('start') }}" class="brand">
            <p style=" width: 12px; "></p>

            <img id="logo-img" src="{{ URL::asset('tirolibre.png') }}"
                data-light-img="{{ URL::asset('tirolibre.png') }}"
                data-dark-img="{{ URL::asset('tirolibre-dark.png') }}" width="250px" alt=""
                style="padding-left: 10px; padding-top: 30px;">
            <p style=" width: 12px; "></p>

        </a>
        <ul class="side-menu top">

            <li class="{{ Route::is('start') ? 'active' : '' }}">
                <a href="{{ route('start') }}">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Inicio</span>
                </a>
            </li>

            @if (auth()->check() && auth()->user()->hasRole('super-admin'))
                <!-- SuperAdmin: mostrar todo sin repetidos -->
                <li class="{{ Route::is('fields.index') ? 'active' : '' }}">
                    <a href="{{ route('fields.index') }}">
                        <i class='bx bxs-doughnut-chart'></i>
                        <span class="text">Predios Disponibles</span>
                    </a>
                </li>
                <li class="{{ Route::is('turns.index') ? 'active' : '' }}">
                    <a href="{{ route('turns.index') }}">
                        <i class='bx bxs-shopping-bag-alt'></i>
                        <span class="text">Mis Turnos</span>
                    </a>
                </li>
                <li class="{{ Route::is('room') ? 'active' : '' }}">
                    <a href="{{ route('room') }}">
                        <i class='bx bx-user'></i>
                        <span class="text">Salas</span>
                    </a>
                </li>
                <li class="{{ Route::is('owner-turns') ? 'active' : '' }}">
                    <a href="{{ route('owner-turns') }}">
                        <i class='bx bxs-calendar-plus'></i>
                        <span class="text">Cargar Turno</span>
                    </a>
                </li>
                <li class="{{ Route::is('turns.pending') ? 'active' : '' }}">
                    <a href="{{ route('turns.pending') }}">
                        <i class='bx bxs-bookmark-plus'></i>
                        <span class="text">Pendiente de Aprobacion</span>
                    </a>
                </li>
                <li class="{{ Route::is('fields.showMyFields') ? 'active' : '' }}">
                    <a href="{{ route('fields.showMyFields') }}">
                        <i class='bx bx-edit'></i>
                        <span class="text">Mis Canchas</span>
                    </a>
                </li>
                <li class="{{ Route::is('fields.create') ? 'active' : '' }}">
                    <a href="{{ route('fields.create') }}">
                        <i class='bx bxs-plus-circle'></i>
                        <span class="text">Registrar Cancha</span>
                    </a>
                </li>
                <li class="{{ Route::is('calendar.owner') ? 'active' : '' }}">
                    <a href="{{ route('calendar.owner') }}">
                        <i class='bx bxs-calendar'></i>
                        <span class="text">Calendario Canchero</span>
                    </a>
                </li>
                <li class="{{ Route::is('calendar.player') ? 'active' : '' }}">
                    <a href="{{ route('calendar.player') }}" class="protected-route">
                        <i class='bx bxs-calendar'></i>
                        <span class="text">Calendario Jugador</span>
                    </a>
                </li>
            @else
                <!-- Usuario no logueado -->
                @if (!auth()->check())
                    <li class="{{ Route::is('fields.index') ? 'active' : '' }}">
                        <a href="{{ route('fields.index') }}" class="protected-route">
                            <i class='bx bxs-doughnut-chart'></i>
                            <span class="text">Predios Disponibles</span>
                        </a>
                    </li>
                @endif

                <!-- Jugador -->
                @if (auth()->check() && auth()->user()->hasRole('player'))
                    <li class="{{ Route::is('fields.index') ? 'active' : '' }}">
                        <a href="{{ route('fields.index') }}">
                            <i class='bx bxs-doughnut-chart'></i>
                            <span class="text">Predios Disponibles</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('turns.index') ? 'active' : '' }}">
                        <a href="{{ route('turns.index') }}" class="protected-route">
                            <i class='bx bxs-shopping-bag-alt'></i>
                            <span class="text">Mis Turnos</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('room') ? 'active' : '' }}">
                        <a href="{{ route('room') }}" class="protected-route">
                            <i class='bx bx-user'></i>
                            <span class="text">Salas</span>
                        </a>
                    </li>
                @endif

                <!-- (Canchero) -->
                @if (auth()->check() && auth()->user()->hasRole('owner'))
                    <li class="{{ Route::is('owner-turns') ? 'active' : '' }}">
                        <a href="{{ route('owner-turns') }}">
                            <i class='bx bxs-calendar-plus'></i>
                            <span class="text">Gestionar Turnos</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('turns.pending') ? 'active' : '' }}">
                        <a href="{{ route('turns.pending') }}">
                            <i class='bx bxs-bookmark-plus'></i>
                            <span class="text">Solicitudes Pendientes</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('fields.showMyFields') ? 'active' : '' }}">
                        <a href="{{ route('fields.showMyFields') }}">
                            <i class='bx bx-edit'></i>
                            <span class="text">Mis Canchas</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('fields.create') ? 'active' : '' }}">
                        <a href="{{ route('fields.create') }}">
                            <i class='bx bxs-plus-circle'></i>
                            <span class="text">Registrar Cancha</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('calendar.owner') ? 'active' : '' }}">
                        <a href="{{ route('calendar.owner') }}" class="protected-route">
                            <i class='bx bxs-calendar'></i>
                            <span class="text">Calendario</span>
                        </a>
                    </li>
                @else
                    <li class="{{ Route::is('calendar.player') ? 'active' : '' }}">
                        <a href="{{ route('calendar.player') }}" class="protected-route">
                            <i class='bx bxs-calendar'></i>
                            <span class="text">Calendario</span>
                        </a>
                    </li>
                @endif
            @endif

            <li class="{{ Route::is('allTeam') ? 'active' : '' }}">
                <a href="{{ route('allTeam') }}">
                    <i class='bx bxs-map'></i>
                    <span class="text">Mapa</span>
                </a>
            </li>
            <li class="{{ Route::is('team') ? 'active' : '' }}">
                <a href="{{ route('team') }}">
                    <i class='bx bxs-group'></i>
                    <span class="text">Equipo de desarrollo</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            @if (auth()->check())
                <li>
                    <a href="{{ route('profile.edit') }}" class="protected-route">
                        <i class='bx bxs-cog'></i>
                        <span class="text">Configuración</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="logout protected-route">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Cerrar sesión</span>
                    </a>
                </li>
            @endif
            @if (!auth()->check())
                <li>
                    <a href="{{ route('login') }}" class="login">
                        <i class='bx bxs-log-in-circle'></i>
                        <span class="text">Iniciar sesión</span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
    <!-- SIDEBAR -->

    @if (Session::has('flash_notification'))
        @php
            $flash = Session::get('flash_notification');
            $message = $flash['message']; // No need to manually escape, Blade handles it
            $type = $flash['type'];
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                toastr["{{ $type }}"]("{!! $message !!}");
            });
        </script>
    @endif



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="{{ route('fields.search') }}" method="post">
                @csrf
                <div class="form-container">
                    <input class="input-filter" type="search" placeholder="¿Qué cancha estás buscando?"
                        name="search" id="search" value="{{ session('search') }}">
                    <input type="range" name="price" id="price" min="10000" max="50000"
                        step="100" value="{{ session('price', 50000) }}" />
                    <output class="price-output" for="price"></output>
                    <select id="capacity" name="capacity" class="form-select-filter">
                        <option value="">Todos</option>
                        <option value="8" {{ session('capacity') == 8 ? 'selected' : '' }}>F4</option>
                        <option value="10" {{ session('capacity') == 10 ? 'selected' : '' }}>F5</option>
                        <option value="12" {{ session('capacity') == 12 ? 'selected' : '' }}>F6</option>
                        <option value="14" {{ session('capacity') == 14 ? 'selected' : '' }}>F7</option>
                        <option value="16" {{ session('capacity') == 16 ? 'selected' : '' }}>F8</option>
                        <option value="18" {{ session('capacity') == 18 ? 'selected' : '' }}>F9</option>
                        <option value="20" {{ session('capacity') == 20 ? 'selected' : '' }}>F10</option>
                        <option value="22" {{ session('capacity') == 22 ? 'selected' : '' }}>F11</option>
                    </select>
                    <select id="type" name="type" class="form-select-filter">
                        <option value="">Todos</option>
                        <option value="Cesped sintetico"
                            {{ session('type') == 'Cesped sintetico' ? 'selected' : '' }}>Cesped sintetico</option>
                        <option value="Cesped natural" {{ session('type') == 'Cesped natural' ? 'selected' : '' }}>
                            Cesped natural</option>
                        <option value="Cemento" {{ session('type') == 'Cemento' ? 'selected' : '' }}>Cemento</option>
                        <option value="Tierra" {{ session('type') == 'Tierra' ? 'selected' : '' }}>Tierra</option>
                        <option value="Arena" {{ session('type') == 'Arena' ? 'selected' : '' }}>Arena</option>
                    </select>
                    <button type="submit" class="search-btn">
                        <i class='bx bx-search'></i>
                    </button>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const price = document.querySelector("#price");
                    const output = document.querySelector(".price-output");

                    if (price && output) {

                        output.textContent = '$' + price.value;

                        price.addEventListener("input", function() {

                            output.textContent = '$' + price.value;
                        });
                    } else {
                        console.error("No se pudo encontrar el elemento #price o .price-output");
                    }
                });
            </script>

            <div class="connection-status" id="connection-status">Estás Desconectado</div>
            <div class="overlay" id="overlay">Conexión perdida. Por favor, reconecta.</div>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            {{--             <a href="{{ route('notification') }}" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">1</span>
            </a> --}}
            <a href="{{ route('profile.edit') }}" class="profile protected-route ">

                @if (auth()->check())
                    @php
                        $userPhoto = Auth::user()->photo;
                        if (empty($userPhoto)) {
                            $userPhoto = 'people.png';
                        }
                    @endphp
                    <img src="{{ URL::asset($userPhoto) }}" alt="User Photo"
                        style="width: 52px; height: 52px; border-radius: 50%; box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.5);">
            </a>
            @endif
        </nav>



        <!-- NAVBAR -->
        <main>
            @yield('content')
            <footer
                class="footer
                    @if (in_array(Route::currentRouteName(), [
                            'team',
                            'allTeam',
                            'calendar.owner',
                            'calendar.player',
                            'fields.create',
                            'turns.pending',
                            'room',
                            'start',
                            '/start',
                            'owner-turns',
                            'room.show',
                            'room.create',
                            'fields.edit',
                            'fields.show',
                        ])) footer-centered
                    @elseif (in_array(Route::currentRouteName(), [
                            'fields.showMyFields',
                            'turns.index',
                            'fields.index',
                            'fields.search',
                            'userCoordinates',
                        ]))
                        footer-not-centered @endif
">
                <!-- Contenido del footer -->
                {{--  <img src="https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fmedia.giphy.com%2Fmedia%2FyoJC2B1xflgnXixilG%2Fgiphy.gif&f=1&nofb=1&ipt=374eb1be4a77779c217deec17cea96206466091cfde0d3e070323b74c19e04f8&ipo=images"
                        alt=""> --}}
                <p class="mb-1">© 2024 Tiro Libre. Todos los derechos reservados.</p>
                <p class="mb-0"><a href="#">Política de Privacidad</a> | <a href="#">Términos de
                        Servicio</a></p>
            </footer>
        </main>
        <style>
            /* Estilos para el footer centrado */
            .footer-centered {
                position: relative;
                background-color: var(--grey);
                text-align: center;
                padding: 20px 0;
                transition: margin-left 0.3s, width 0.3s;
            }

            /* Estilos para el footer no centrado */
            .footer-not-centered {
                position: relative;
                margin-left: 280px;
                width: calc(100% - 280px);
                background-color: var(--grey);
                text-align: center;
                padding: 20px 0;
                transition: margin-left 0.3s, width 0.3s;
            }

            .connection-status {
                display: none;
                /* Ocultar por defecto */
                color: red;
                font-weight: bold;
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 1001;
                /* Asegurar que esté por encima del overlay */
            }

            .overlay {
                display: none;
                /* Ocultar por defecto */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.75);
                color: white;
                text-align: center;
                font-size: 24px;
                z-index: 1000;
                /* Debajo del mensaje de conexión */
                padding-top: 20%;
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const connectionStatus = document.getElementById('connection-status');
                const overlay = document.getElementById('overlay');

                function checkInternetConnection() {
                    fetch('https://www.google.com/', {
                            mode: 'no-cors'
                        })
                        .then(() => {
                            connectionStatus.style.display = 'none'; // Conectado
                            overlay.style.display = 'none'; // Ocultar overlay
                        })
                        .catch(() => {
                            connectionStatus.style.display = 'block'; // Desconectado
                            overlay.style.display = 'block'; // Mostrar overlay
                        });
                }

                function updateConnectionStatus() {
                    if (navigator.onLine) {
                        checkInternetConnection(); // Verificar acceso a Internet
                    } else {
                        connectionStatus.style.display = 'block'; // Desconectado
                        overlay.style.display = 'block'; // Mostrar overlay
                    }
                }

                // Verificar el estado de conexión cada 1 segundos
                setInterval(updateConnectionStatus, 1000);

                // Escuchar eventos de conexión
                window.addEventListener('online', updateConnectionStatus);
                window.addEventListener('offline', updateConnectionStatus);

                // Llamar a la función inicialmente
                updateConnectionStatus();
            });
        </script>

</body>
