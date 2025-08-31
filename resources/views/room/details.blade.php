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

<!-- MAIN -->
<style>
    .button {
        display: inline-flex;
        align-items: center;
        border: none;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 25px;
        background-color: #3C91E6;
        color: white;
        font-size: 15px;
        transition: background-color 0.3s;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }
    .button:hover {
        background-color: rgb(8, 8, 107);
        color:white;
    }

    .room-details {
        margin-top: 20px;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-item strong {
        display: block;
        margin-bottom: 5px;
    }

    .text-danger {
        color: red;
    }


    .field-link {
        text-decoration: none; /* Quitar el subrayado */
        color: #007bff; /* Color del enlace */
        font-weight: bold; /* Texto en negrita */
        cursor: pointer;
        transition: color 0.3s;
    }

    .field-link:hover {
        color: #0056b3; /* Cambiar color al pasar el cursor */
    }

    .right-align-container {
    text-align: right; /* Alinea todo el contenido hacia la derecha */
    margin-right: 10px; /* Opcional: puedes ajustar este valor según sea necesario */
    }

    .right-align-container form {
        display: inline-block; /* Asegura que los formularios se comporten como elementos en línea */
        margin-left: 10px; /* Espacio entre formularios si hay más de uno */
    }

    .buttonU {
	display: inline-flex;
	align-items: center;
	outline: none;
	border: none;
	justify-content: center;
	width: auto;
	/* Ajusta el tamaño según el contenido */
	padding: 10px 20px;
	/* Espaciado interno del botón */
	border-radius: 25px;
	/* Bordes redondeados */
	background-color: rgb(53, 209, 53);
	/* Fondo verde */
	color: white;
	/* Texto blanco */
	font-size: 15px;
	/* Tamaño de la fuente */
	transition: background-color 0.3s;
	cursor: pointer;
	text-align: center;
	text-decoration: none;
}

.buttonU:hover {
	background-color: rgb(1, 255, 1);
	/* Fondo más oscuro al pasar el mouse */
}


</style>

<main>

    <div class="table-data">
        <div class="order">
            <a href="{{ url()->previous() }}" style="float: right;" class="button">&larr; Volver</a>
            <div class="head">
                <h2>Sala "{{ $room->name }}"</h2>
            </div>

            <div class="container">  
                <div class="room-details">
                    <div class="detail-item">
                        <strong>Descripción</strong>
                        <div>{{ $room->description ?? 'No disponible' }}</div>
                    </div>

                    <div class="detail-item">
                        <strong>Participantes</strong>
                        <div>
                            @forelse ($players as $player)
                                {{ $player->name }} {{ $player->surname }}@if(!$loop->last), @endif
                            @empty
                                <span class="text-danger">
                                    <strong>No hay jugadores unidos</strong>
                                </span>
                            @endforelse
                        </div>
                    </div>
                    <br>
                    <h4>Datos del turno</h4>
                    <div class="detail-item">
                        <strong>Día</strong>
                        <div>{{ \Carbon\Carbon::parse($room->turn->day)->format('d/m/Y') }}</div>
                    </div>
                    <div class="detail-item">
                        <strong>Hora</strong>
                        <div>{{ \Carbon\Carbon::parse($room->turn->day)->format('H:i') }}</div>
                    </div>
                    <div class="detail-item">
                        <strong>Lugar:</strong>
                        <div>
                            <a href="{{ route('fields.show', $room->turn->field->id) }}" class="field-link">
                                {{ $room->turn->field->name }}
                            </a>
                        </div>
                    </div>

                    <div class="right-align-container">
                    @if(auth()->id() === $room->turn->id_player)
                        {{-- Formulario para eliminar la sala --}}
                        <form action="{{ route('room.destroy', $room->id) }}" method="POST" class="delete-room">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-red">
                                Eliminar la sala
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
                            {{-- Formulario para abandonar la sala --}}
                            <form action="{{ route('leaveRoom', $room->id) }}" method="POST" class="leave-room" >
                                @csrf
                                <button type="submit" class="button-red">
                                    Abandonar la sala
                                </button>
                            </form>
                        @else
                            {{-- Formulario para unirse a la sala --}}
                            <form action="{{ route('joinRoom', $room->id) }}" method="POST" class="join-room" title="Unirse a la sala">
                                @csrf
                                <button type="submit" class="buttonU">Unirme</button>
                            </form>
                        @endif
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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
