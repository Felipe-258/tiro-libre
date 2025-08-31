@extends('screens.app')

@section('content')

    <!-- MAIN -->
    <main>
        {{-- @if ($message = Session::get('success'))
				<div class="alert alert-success" role="alert">
					{{ $message }}
				</div>
			@endif --}}
        {{-- 			<script>
				@if (session('flash_notification'))
					var notification = @json(session('flash_notification'));
					toastr[notification.type](notification.message);
				@endif
			</script> --}}
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Mis Reservas</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Fecha (hora)</th>
                            <th scope="col">Cancha</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($turns->where('day', '>', \Carbon\Carbon::now())->isNotEmpty())
                            @foreach ($turns as $turn)
                                @if (Carbon\Carbon::now()->lessThan($turn->day))
                                    @php
                                        $field = $fields->get($turn->id_field);
                                    @endphp
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($turn->day)->format('d/m/Y (H:i)') }}</td>
                                        <td>
                                            <a href="{{ route('fields.show', ['field' => $field->id]) }}">
                                                {{ $field ? $field->name : 'No field' }}
                                            </a>
                                        </td>
                                        <td>
                                            <form action="{{ route('turns.destroy', $turn->id) }}" method="POST" class="delete-room">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No tienes reservas disponibles</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
            @if ((auth()->check() && auth()->user()->hasRole('player')) || auth()->user()->hasRole('super-admin'))
                <div class="todo">
                    <div class="todo">
                        <div class="head">
                            <h3>Mis Salas</h3>
                        </div>
                        @if (!empty($rooms) && $rooms->count() > 0)
                            {{-- Mostrar las salas disponibles --}}
                            <ul class="todo-list">
                                @foreach ($rooms as $room)
                                    <li class="not-completed"
                                        onclick="window.location='{{ route('room.show', $room->room->id) }}';"
                                        style="cursor:pointer;">
                                        <p>{{ $room->room->name }}</p>
                                        <p>Jugadores {{ $room->room->quantity }}/{{ $room->room->max }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Mostrar mensaje si no hay salas --}}
                            <p>Actualmente no tienes ninguna sala asignada a tu nombre.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>
    <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script>
        /* ELIMINAR LA SALA */

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.delete-room').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevenir el envío por defecto

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción eliminará el turno. Se le notificara al canchero.",
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
    </script>
    <script src="script.js"></script>


@endsection
