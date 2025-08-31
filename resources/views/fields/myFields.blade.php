@extends('screens.app')

@section('content')
		<!-- MAIN -->
		<main>
			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Mis Canchas</h3>
					</div>
					@if($fields->isEmpty())
                    <p>No tienes ninguna cancha a tu nombre.</p>
                    @else
					<table>
						<thead>
							<tr>
								<th>Nombre</th>
								<th>Capacidad</th>
								<th>Suelo</th>
								<th>Precio</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($fields as $field)
							<tr onclick="window.location='{{ route('fields.edit', $field->id) }}';" style="cursor:pointer;">
								<td>
									<img src="ubi.png" alt="Ubicación">
									<p>{{ $field->name }}</p>
								</td>
								<td>
									{{ $field->capacity }}
								</td>
								<td>
									{{ $field->type }}
								</td>
								<td>
									{{ $field->price }}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@endif
				</div>
			</div>
		</main>
		<!-- MAIN -->
		
	</section>
	<!-- CONTENT -->
	

	<script src="script.js"></script>

    
@endsection