@extends('screens.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <div class="row justify-content-center mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Todos las Canchas Afiliadas
                    </div>
                    <div class="float-end">
                        <a href="{{ route('fields.index') }}" class="btn btn-primary btn-sm">&larr; Volver</a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="map" style="width: 100%; height: 600px;"></div>

                    <script>
                        // Crear el mapa y establecer la vista inicial
                        var initialCoordinates = [-33.008286767127515, -58.521264464319856]; // Coordenadas específicas
                        var zoomLevel = 14; // Ajusta el nivel de zoom aquí
                    
                        // Crear el mapa y establecer la vista inicial con las coordenadas y el nivel de zoom
                        var map = L.map('map').setView(initialCoordinates, zoomLevel);
                    
                        // Añadir la capa de OpenStreetMap al mapa
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                    
                        // Añadir marcadores al mapa
                        @foreach ($coordinatesAll as $field)
                            L.marker(@json($field['coordinates'])).addTo(map)
                                .bindPopup("{{ $field['name'] }}");
                        @endforeach

                        // Obtener la ubicación actual del usuario
                        function getLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(showPosition, showError);
                            } else {
                                alert("La geolocalización no es compatible con este navegador.");
                            }
                        }

                        var redIcon = L.icon({
                            iconUrl: 'Usted-esta-aqui.png', // Icono rojo
                            iconSize: [25, 41], // Tamaño del icono
                            iconAnchor: [12, 41], // Punto donde se ancla el icono en las coordenadas
                            popupAnchor: [0, -41] // Punto donde se muestra el popup
                        });

                        // Mostrar la posición del usuario en el mapa
                        function showPosition(position) {
                            var userCoordinates = [position.coords.latitude, position.coords.longitude];
                            L.marker(userCoordinates, { icon: redIcon }).addTo(map)
                                .bindPopup("Estás aquí").openPopup();
                            map.setView(userCoordinates, zoomLevel);
                        }

                        // Manejar errores al obtener la ubicación
                        function showError(error) {
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
/*                                     alert("Has denegado el permiso para obtener la ubicación.");
 */                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    alert("La información de ubicación no está disponible.");
                                    break;
                                case error.TIMEOUT:
                                    alert("La solicitud para obtener la ubicación ha expirado.");
                                    break;
                                case error.UNKNOWN_ERROR:
                                    alert("Ocurrió un error desconocido.");
                                    break;
                            }
                        }

                        // Llamar a la función de ubicación cuando la página cargue
                        document.addEventListener("DOMContentLoaded", function() {
                            getLocation();
                        });
                    </script>
                    
                </div>
            </div>
        </div>
    </div>
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 100px); /* Ajusta esta altura según sea necesario */
        }
        .card-body {
            padding: 0; /* Elimina el padding de la tarjeta para que el mapa ocupe todo el espacio */
        }
    </style>
    
    <script src="script.js"></script>

    @endsection