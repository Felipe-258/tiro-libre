@extends('screens.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>


    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            @if ($message = Session::get('success'))
                <div class="alert" role="alert" style="background-color: #15b100; ">
                    <p class="text-white">{{ $message }}</p>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        Agregar una nueva cancha
                    </div>
                    <div class="float-end">
                        <a href="{{ route('fields.index') }}" class="btn btn-primary btn-sm">&larr; Atrás</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('fields.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required maxlength="30">
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="type" class="col-md-4 col-form-label text-md-end text-start">Capacidad</label>
                            <div class="col-md-6 content-select">
                                <select name="capacity" id="capacity" class="form-select">
                                    <option value="10" selected>10</option>
                                    <option value="12">12</option>
                                    <option value="14">14</option>
                                    <option value="16">16</option>
                                    <option value="18">18</option>
                                    <option value="20">20</option>
                                    <option value="22">22</option>
                                </select>
                                @if ($errors->has('capacity'))
                                    <span class="text-danger">{{ $errors->first('capacity') }}</span>
                                @endif
                                <i></i>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="coordinates" class="col-md-4 col-form-label text-md-end text-start">Ingresa las
                                Coordenada (o haz clic en donde se encuentre ubicada)</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('coordinates') is-invalid @enderror"
                                    id="coordinates" name="coordinates" value="{{ old('coordinates') }}" required>
                                @if ($errors->has('coordinates'))
                                    <span class="text-danger">{{ $errors->first('coordinates') }}</span>
                                @endif
                            </div>
                        </div>

                        <div id="map" style="height: 400px;"></div>
                        {{-- <input type="text" id="coordinates" name="coordinates" hidden> --}}
                        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
                        <script>
                            // Inicializar el mapa
                            var map = L.map('map').setView([0, 0], 13);

                            // Cargar las capas de tiles
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                            }).addTo(map);

                            var marker;

                            // Función para manejar la ubicación del usuario
                            function onLocationFound(e) {
                                map.setView(e.latlng, 13);
                            }

                            // Función para manejar errores de ubicación
                            function onLocationError(e) {
                                alert("No se pudo obtener tu ubicación");
                            }

                            // Solicitar la ubicación del usuario
                            map.locate({
                                setView: true,
                                maxZoom: 16
                            });

                            // Eventos de ubicación
                            map.on('locationfound', onLocationFound);
                            map.on('locationerror', onLocationError);

                            // Evento de clic en el mapa
                            map.on('click', function(e) {
                                // Si ya existe un marcador, se elimina
                                if (marker) {
                                    map.removeLayer(marker);
                                }

                                // Colocar un nuevo marcador en las coordenadas del clic
                                marker = L.marker(e.latlng).addTo(map);

                                // Escribir las coordenadas en el input
                                var lat = e.latlng.lat;
                                var lng = e.latlng.lng;
                                document.getElementById('coordinates').value = lat + ', ' + lng;
                            });
                        </script>

                        <br>


                        <div class="mb-3 row">
                            <label for="price" class="col-md-4 col-form-label text-md-end text-start">Precio</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" value="{{ old('price') }}" required min="1"
                                    max="999999999">
                                @if ($errors->has('price'))
                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="type" class="col-md-4 col-form-label text-md-end text-start">Terreno</label>
                            <div class="col-md-6 content-select">
                                <select name="type" id="type" class="form-select">
                                    <option value="Cesped sintetico" selected>Cesped sintetico</option>
                                    <option value="Cesped natural">Cesped natural</option>
                                    <option value="Cemento">Cemento</option>
                                    <option value="Tierra">Tierra</option>
                                    <option value="Arena">Arena</option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                @endif
                                <i></i>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="description"
                                class="col-md-4 col-form-label text-md-end text-start">Descripción</label>
                            <div class="col-md-6">
                                <textarea maxlength="255" class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description">{{ old('description') }} </textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="start" class="col-md-4 col-form-label text-md-end text-start">Inicio del
                                servicio</label>
                            <div class="col-md-6 content-select">
                                <select name="start" id="start" class="form-select">
                                    <option value="00:00" selected>00:00</option>
                                    <option value="01:00">01:00</option>
                                    <option value="02:00">02:00</option>
                                    <option value="03:00">03:00</option>
                                    <option value="04:00">04:00</option>
                                    <option value="05:00">05:00</option>
                                    <option value="06:00">06:00</option>
                                    <option value="07:00">07:00</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                    <option value="21:00">21:00</option>
                                    <option value="22:00">22:00</option>
                                    <option value="23:00">23:00</option>
                                </select>
                                <i></i>
                                @if ($errors->has('start'))
                                    <span class="text-danger">{{ $errors->first('start') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="end" class="col-md-4 col-form-label text-md-end text-start">Fin del
                                servicio</label>
                            <div class="col-md-6 content-select">
                                <select name="end" id="end" class="form-select">
                                    <option value="00:00">00:00</option>
                                    <option value="01:00">01:00</option>
                                    <option value="02:00">02:00</option>
                                    <option value="03:00">03:00</option>
                                    <option value="04:00">04:00</option>
                                    <option value="05:00">05:00</option>
                                    <option value="06:00">06:00</option>
                                    <option value="07:00">07:00</option>
                                    <option value="08:00">08:00</option>
                                    <option value="09:00">09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="12:00">12:00</option>
                                    <option value="13:00">13:00</option>
                                    <option value="14:00">14:00</option>
                                    <option value="15:00">15:00</option>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00">17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="19:00">19:00</option>
                                    <option value="20:00">20:00</option>
                                    <option value="21:00">21:00</option>
                                    <option value="22:00">22:00</option>
                                    <option value="23:00" selected>23:00</option>
                                </select>
                                <i></i>
                                @if ($errors->has('end'))
                                    <span class="text-danger">{{ $errors->first('end') }}</span>
                                @endif
                            </div>
                        </div>

                        <script>
                            const startSelect = document.getElementById('start');
                            const endSelect = document.getElementById('end');

                            // Guardar todas las opciones originales del select de fin
                            const originalEndOptions = Array.from(endSelect.options);

                            startSelect.addEventListener('change', function() {
                                const startValue = this.value;

                                // Limpiar las opciones actuales del select de fin
                                endSelect.innerHTML = '';

                                // Filtrar y agregar solo las opciones válidas que son mayores a la hora de inicio seleccionada
                                originalEndOptions.forEach(option => {
                                    if (option.value > startValue) {
                                        endSelect.appendChild(option);
                                    }
                                });

                                // Si no hay opciones válidas, podrías ajustar el valor del select o mostrar un mensaje de error
                                if (endSelect.options.length === 0) {
                                    // Añadir una opción por defecto en caso de que no haya opciones válidas
                                    const noOptions = document.createElement('option');
                                    noOptions.text = 'No hay horarios disponibles';
                                    noOptions.disabled = true;
                                    noOptions.selected = true;
                                    endSelect.appendChild(noOptions);
                                }
                            });
                        </script>

                        <!-- Campo adicional para subir fotos -->
                        <div class="mb-3 row">
                            <label for="photos" class="col-md-4 col-form-label text-md-end text-start">Subir
                                Fotos</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control @error('photos') is-invalid @enderror"
                                    id="photos" name="photos[]" multiple accept="image/*">
                                @if ($errors->has('photos'))
                                    <span class="text-danger">{{ $errors->first('photos') }}</span>
                                @endif
                                @error('photos.*')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Añadir">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
