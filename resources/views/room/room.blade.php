@extends('screens.app')

@section('content')


@if ($message = Session::get('success'))
<div class="alert alert-success" role="alert">
    {{ $message }}
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('style.css') }}">
<script src="{{ asset('script.js') }}"></script>

<style>

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
        color: white;

    }

</style>


    <!-- MAIN -->
    <main>
        <div class="table-data">
            <div class="todo">
                <div class="head">
                    <h3>Salas</h3>
                        <a href="{{ route('room.create') }}" class="buttonMap">
                            Crear una nueva sala
                        </a> 
                </div>
                <ul class="todo-list">
                    @forelse ($rooms as $room)

                    @if (Carbon\Carbon::now()->lessThan($room->turn->day))

                    <li class="completed"> <!-- Puedes cambiar 'completed' según la lógica que desees implementar -->
                        <div style="display: table;">
                            <div style="display: table-row;">
                                <p style="display: table-cell; font-size: 20px; font-weight: bold;">{{ $room->name }}</p>
                            </div>
                            <div style="display: table-row;">
                                <p style="display: table-cell; font-size: 15px; font-weight: bold;">Jugadores unidos: {{ $room->quantity }} / {{ $room->max }}</p>
                            </div>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('room.show', $room->id) }}" class="button2" title="Ver detalles de la sala seleccionada">
                                Ver detalles
                            </a>
                            @if(auth()->id() === $room->turn->id_player)
                            <form action="{{ route('room.destroy', $room->id) }}" method="POST" class="delete-room">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-container button3" title="Eliminar sala">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </form>
                            @else 
                                @php
                                    $userId = auth()->id();
                                    $isPlayerInRoom = \DB::table('player_rooms')
                                        ->where('id_player', $userId)
                                        ->where('id_room', $room->id)
                                        ->exists();
                                @endphp
                                @if($isPlayerInRoom)
                                    <form action="{{ route('leaveRoom', $room->id) }}" method="POST" class="leave-room" >
                                        @csrf
                                        <button type="submit" class="button-red">
                                            Abandonar la sala
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('joinRoom', $room->id) }}" method="POST" class="join-room" title="Unirse a la sala">
                                        @csrf
                                        <button type="submit" class="button">Unirme</button>
                                    </form>
                                @endif
                            @endif
                            
                        </div>
                    </li>

                    @endif

                    @empty
                    <li>
                        <span class="text-danger">
                            <strong>No se han encontrado salas</strong>
                        </span>
                    </li>
                    @endforelse
                </ul>                
            </div>
        </div>
        
    </main>
    <!-- MAIN -->


    <script>
        
            /* ELIMINAR LA SALA */

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
    
    
        /* ABANDONAR LA SALA */
    
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form.leave-room').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevenir el envío por defecto
    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: '¿Quieres abandonar la sala?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, abandonar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Enviar el formulario si se confirma
                        }
                    });
                });
            });
        });



          /* UNIRTE A LA SALA */
    
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form.join-room').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault(); // Prevenir el envío por defecto
    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: '¿Quieres unirte la sala?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, unirme',
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
    


    @endsection


