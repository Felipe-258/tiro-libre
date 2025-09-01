@extends('screens.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="{{ asset('script.js') }}"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="card rounded shadow-sm mt-4">
        <h3 class="text-center">{{ $field->name }}</h3>
        <div class="card-body">

            <div class="container mt-3">
                <div class="row">
                    <!-- Columna Izquierda: Carrusel de Imágenes -->
                    <div class="col-md-6">
                        @if ($photos && count($photos) > 0)
                            <div id="fieldPhotosCarousel" class="carousel slide mb-4" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach ($photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}" class="d-block w-100"
                                                alt="{{ 'storage/' . $photo->photo_path }}">
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#fieldPhotosCarousel" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </a>
                                <a class="carousel-control-next" href="#fieldPhotosCarousel" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </a>
                            </div>
                        @else
                            <div class="text-center">
                                {{-- <video loop autoplay width="390px">
                                    <source src="{{ URL::asset('no-hay-fotos-aun.mp4') }}" type="video/mp4">
                                </video> --}}
                                <img src="{{ URL::asset('no-hay-fotos-aun.png') }}" alt="" width="500px">
                            </div>
                        @endif
                    </div>

                    <!-- Columna Derecha: Información del Producto -->
                    <div class="col-md-6">

                        <p style="color: var(--dark)"><strong>Capacidad:</strong> {{ $field->capacity }}</p>
                        <p style="color: var(--dark)"><strong>Precio:</strong> ${{ $field->price }}</p>
                        <p style="color: var(--dark)"><strong>Tipo:</strong> {{ $field->type }}</p>
                        <p style="color: var(--dark)"><strong>Teléfono:</strong> {{ substr($user->phone, 2) }}</p>
                        <p style="color: var(--dark)"><strong>Descripción:</strong> {{ $field->description }}</p>
                        <p style="color: var(--dark)"><strong>Dueño/a:</strong> {{ $user->name . ' ' . $user->surname }}
                        </p>

                        <!-- Calificación -->
                        <form action="{{ route('rating.index') }}" method="POST" id="rating-form">
                            @csrf
                            <div class="rating">
                                <input type="hidden" id="fieldId" name="fieldId" value="{{ $field->id }}">
                                <input type="radio" id="star5" name="rating" value="5"
                                    {{ $rating == 5 ? 'checked' : '' }} /><label for="star5" title="5 stars">☆</label>
                                <input type="radio" id="star4" name="rating" value="4"
                                    {{ $rating == 4 ? 'checked' : '' }} /><label for="star4" title="4 stars">☆</label>
                                <input type="radio" id="star3" name="rating" value="3"
                                    {{ $rating == 3 ? 'checked' : '' }} /><label for="star3" title="3 stars">☆</label>
                                <input type="radio" id="star2" name="rating" value="2"
                                    {{ $rating == 2 ? 'checked' : '' }} /><label for="star2" title="2 stars">☆</label>
                                <input type="radio" id="star1" name="rating"
                                    value="1"{{ $rating == 1 ? 'checked' : '' }} /><label for="star1"
                                    title="1 star">☆</label>
                            </div>
                        </form>

                        <!-- Botón para mostrar el mapa -->
                        <button id="showMapButton" class="btn btn-info">Mostrar Mapa</button>

                    </div>
                </div>
                <!-- Date Selection -->
                <form id="reservationSearchForm" action="{{ route('fields.show', $field->id) }}" method="GET">
                    <div class="form-group">
                        <label for="day">Seleccionar Fecha:</label>
                        <input class="date" type="date" id="day" name="day" value="{{ $selectedDate }}"
                            min="{{ now()->format('Y-m-d') }}" onchange="this.form.submit()" style="margin-top: 30px;">
                    </div>
                </form>

                <!-- Contenedor para desplazamiento horizontal y botones de control -->
                <div class="button-wrapper" style="margin-top: 20px;">
                    <button class="scroll-button scroll-left" aria-label="Scroll Left">&#9664;</button>
                    <div class="button-separator"></div> <!-- Botón de separador -->
                    
                    
                    <div class="button-container">
                        @foreach ($hours as $hour)
                            @php
                                $currentDateTime = now();
                                $isPast = false;
                                if ($selectedDate === $currentDateTime->format('Y-m-d')) {
                                    // Si es el día de hoy, compararemos la hora
                                    $currentHour = $currentDateTime->format('H');
                                    $isPast = intval(substr($hour, 0, 2)) <= intval($currentHour);
                                } elseif ($selectedDate < $currentDateTime->format('Y-m-d')) {
                                    // Si la fecha es pasada
                                    $isPast = true;
                                }
                    
                                $isReserved = $reservedTurns->contains($hour);
                                $isOccupied = in_array($hour, $occupiedHours); // Verificar si la hora está ocupada
                            @endphp
                    
                            @if (!$isPast) <!-- Solo mostrar si no ha pasado -->
                                <form action="{{ route('turns.store') }}" method="POST" class="choose-turn d-inline-block">
                                    @csrf
                                    <input type="hidden" name="id_field" value="{{ $field->id }}">
                                    <input type="hidden" name="day" value="{{ $selectedDate }}T{{ $hour }}:00">
                                    <button type="submit" class="btn {{ $isReserved || $isOccupied ? 'btn-secondary' : 'btn-success' }}"
                                        {{ $isReserved || $isOccupied ? 'disabled' : '' }}>
                                        {{ $hour }}
                                    </button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                    
                    <div class="button-separator"></div> <!-- Botón de separador -->
                    <button class="scroll-button scroll-right" aria-label="Scroll Right">&#9654;</button>
                </div>
            </div>
        </div>
    </div>  
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Inicializa el mapa en el SweetAlert
                    const initMap = () => {
                        // Coordenadas de la cancha
                        var fieldCoords = [{{ $coordinates['latitude'] }}, {{ $coordinates['longitude'] }}];

                        // Crear el mapa sin definir el zoom aún
                        var map = L.map('map');

                        // Añade el tile layer de OpenStreetMap
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                        // Añade un marcador para la cancha
                        L.marker(fieldCoords).addTo(map)
                            .bindPopup("{{ $field->name }}")
                            .openPopup();

                        // Icono personalizado para la ubicación del usuario
                        var redIcon = L.icon({
                            iconUrl: '{{ asset('Usted-esta-aqui.png') }}', // Ruta a la imagen del icono
                            iconSize: [25, 41], // Tamaño del icono
                            iconAnchor: [12, 41], // Punto donde se ancla el icono en las coordenadas
                            popupAnchor: [0, -41] // Punto donde se muestra el popup
                        });

                        // Intentar obtener la ubicación actual del usuario
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(function(position) {
                                var userLat = position.coords.latitude;
                                var userLng = position.coords.longitude;
                                var userCoords = [userLat, userLng];

                                // Añadir marcador con el icono rojo en la ubicación actual del usuario
                                L.marker(userCoords, {
                                        icon: redIcon
                                    }).addTo(map)
                                    .bindPopup("Usted está aquí")
                                    .openPopup();

                                // Crear límites que incluyan tanto la cancha como la ubicación del usuario
                                var bounds = L.latLngBounds([fieldCoords, userCoords]);

                                // Ajustar el zoom del mapa para que ambas ubicaciones sean visibles
                                map.fitBounds(bounds);

                            }, function() {
                                console.log("No se pudo obtener la ubicación del usuario");

                                // En caso de no poder obtener la ubicación del usuario, centrar el mapa en la cancha
                                map.setView(fieldCoords, 15); // Nivel de zoom 15
                            });
                        } else {
                            console.log("Geolocalización no soportada por el navegador");

                            // En caso de que el navegador no soporte geolocalización, centrar el mapa en la cancha
                            map.setView(fieldCoords, 15); // Nivel de zoom 15
                        }
                    };

                    document.getElementById('showMapButton').addEventListener('click', function() {
                        Swal.fire({
                            title: 'Mapa de la Cancha',
                            html: '<div id="map" style="width: 100%; height: 400px;"></div>',
                            customClass: {
                                popup: 'custom-swal-width custom-swal-bg', // Añade clases personalizadas
                                title: 'custom-swal-title', // Clase para el título
                                content: 'custom-swal-text' // Clase para el contenido
                            },
                            didOpen: () => {
                                // Inicializa el mapa después de que el SweetAlert se haya abierto
                                initMap();
                            }
                        });
                    });
                });
            </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionar todos los formularios con la clase 'choose-turn'
        document.querySelectorAll('form.choose-turn').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío por defecto del formulario

                // Obtener la fecha y hora del input oculto
                const selectedDateTime = form.querySelector('input[name="day"]').value;

                // Convertir la fecha y hora al formato deseado (DD/MM a las HHhs)
                const formattedDateTime = new Date(selectedDateTime);
                
                // Formatear el día y el mes
                const day = String(formattedDateTime.getDate()).padStart(2, '0');
                const month = String(formattedDateTime.getMonth() + 1).padStart(2, '0'); // Los meses en JavaScript son 0-indexados
                const hour = String(formattedDateTime.getHours()).padStart(2, '0');
                
                // Crear la cadena de fecha y hora en el formato deseado
                const formattedDate = `${day}/${month} a las ${hour}hs`;

                // Mostrar la alerta de confirmación con la fecha formateada
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Desea confirmar la reserva del turno para ${formattedDate}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, reservar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Enviar el formulario si se confirma
                    }
                });
            });
        });
    });
</script>

            {{-- Submit Rating --}}
            <script>
                document.querySelectorAll('.rating > input').forEach((input) => {
                    input.addEventListener('click', function() {
                        document.getElementById('rating-form').submit();
                    });
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const scrollContainer = document.querySelector('.button-container');
                    const scrollLeftButton = document.querySelector('.scroll-left');
                    const scrollRightButton = document.querySelector('.scroll-right');

                    // Función para desplazar el contenedor a la izquierda
                    scrollLeftButton.addEventListener('click', function() {
                        scrollContainer.scrollBy({
                            left: -200, // Desplazar 200px hacia la izquierda
                            behavior: 'smooth' // Animación suave
                        });
                    });

                    // Función para desplazar el contenedor a la derecha
                    scrollRightButton.addEventListener('click', function() {
                        scrollContainer.scrollBy({
                            left: 200, // Desplazar 200px hacia la derecha
                            behavior: 'smooth' // Animación suave
                        });
                    });
                });
            </script>

        @endsection
