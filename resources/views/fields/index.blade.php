@extends('screens.app')

@section('content')

<style>
.button {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: auto; /* Ajusta el tamaño según el contenido */
	padding: 10px 20px; /* Espaciado interno del botón */
	border-radius: 25px; /* Bordes redondeados */
	background-color: #3C91E6; /* Fondo verde */
	color: white; /* Texto blanco */
	font-size: 14px; /* Tamaño de la fuente */
	transition: background-color 0.3s;
	cursor: pointer;
	text-align: center;
	text-decoration: none; 
}
.button:hover {
	background-color: rgb(8, 8, 107); /* Fondo más oscuro al pasar el mouse */
	color:white;
}



.buttonMap {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	border: none;
	width: auto; /* Ajusta el tamaño según el contenido */
	padding: 10px 20px; /* Espaciado interno del botón */
	border-radius: 25px; /* Bordes redondeados */
	background-color:  #02386d; /* Fondo verde */
	color: white; /* Texto blanco */
	font-size: 14px; /* Tamaño de la fuente */
	transition: background-color 0.3s;
	cursor: pointer;
	text-align: center;
	text-decoration: none; 
}
.buttonMap:hover {
	background-color: #01162c; /* Fondo más oscuro al pasar el mouse */
	
}
</style>
		<main>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Canchas Disponibles</h3>
						<script>
							document.addEventListener("DOMContentLoaded", function() {
								// Intentar obtener la ubicación cuando la página se cargue
								getLocation();
							});
						
							function getLocation() {
								if (navigator.geolocation) {
									console.log("Intentando obtener la ubicación...");
									navigator.geolocation.getCurrentPosition(showPosition, showError);
								} else {
/* 									alert("La geolocalización no es compatible con este navegador.");
 */								}
							}
						
							// Asignar las coordenadas a los campos ocultos
							function showPosition(position) {
								console.log("Ubicación obtenida:");
								console.log("Latitud: " + position.coords.latitude);
								console.log("Longitud: " + position.coords.longitude);
						
								document.getElementById("latitude").value = position.coords.latitude;
								document.getElementById("longitude").value = position.coords.longitude;
						
								// Habilitar el botón para que el usuario pueda ordenar por cercanía
								document.getElementById("submitButton").disabled = false;
							}
						
							// Manejar errores en caso de que el usuario no permita la geolocalización
							function showError(error) {
								switch(error.code) {
									case error.PERMISSION_DENIED:
/* 										alert("Has denegado el permiso para obtener la ubicación.");
 */										break;
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
						</script>
						<form id="locationForm" action="{{ route('userCoordinates') }}" method="get">
							@csrf 
							<input type="hidden" name="latitude" id="latitude">
							<input type="hidden" name="longitude" id="longitude">
							<input type="hidden" name="field_ids" id="field_ids" value="{{ json_encode($fields->pluck('id')) }}">
						
							<button type="submit" id="submitButton" class="buttonMap" disabled>
								Ordenar por cercanía <i class='bx bxs-map'></i>
							</button>                    
						</form>					
					</div>
					<table>
						<thead>
							<tr>
								<th>Canchas</th>
								<th>Capacidad</th>
								<th >Tipo</th>
                                <th>Precio</th>
							</tr>
						</thead>
						<tbody>
                        @forelse ($fields as $field)
                        <tr onclick="window.location='{{ route('fields.show', $field->id) }}';"
							style="cursor:pointer;">
                            <td>
                                <img src={{URL::asset('ubi.png')}}>
                                {{ $field->name }}
                            </td>
                            <td >{{ $field->capacity }}</td>
                            <td>{{ $field->type }}</td> 
                            <td>{{ '$' . $field->price }}</td>

							<td><a href="{{ route('fields.show', $field->id) }}" class="button"><i class="bi bi-eye"></i> Ver Mas</a></td>
							
                        </tr>
                        @empty
                            <td colspan="6">
                                <span class="text-danger">
                                    <strong>¡No se encontró ninguna cancha!</strong>
                                </span>
                            </td>
                        @endforelse
							
						</tbody>
						
					</table>
					{{ $fields->links() }}
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	


@endsection
