@extends('screens.app')


@section('content')

<link rel="stylesheet" href="/public/style.css">
		<main>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Canchas Disponibles</h3>
						{{-- <i class='bx bx-search' ></i>
						<i class='bx bx-filter' ></i> --}}
					</div>
					<table>
						<thead>
							<tr>
								<th>Canchas</th>
								<th>Capacidad</th>
								<th>Tipo</th>
                                <th>Ubicacion</th>
							</tr>
						</thead>
						<tbody>
                        @forelse ($fields as $field)
                        <tr>
                            <td>
                                <img src="{{URL::asset('ubi.png')}}">
                                {{ $field->name }}
                            </td>
                            <td>{{ $field->capacity }}</td>
                            <td>{{ $field->type }}</td>
                            <td> <a href="{{ $field->coordinates }}" target="_bank"> <button class="buttonmap">Abrir Mapa</button></a> </td>
							{{-- https://maps.google.com/?q=<lat>,<lng> --}}
							<td><a href="{{ route('fields.show', $field->id) }}" class="button"></i> Ver Mas</a></td>
								
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
	
	<script src="script.js"></script>

@endsection
