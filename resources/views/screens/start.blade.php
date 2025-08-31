@extends('screens.app')

@section('content')


    @if (auth()->check() && auth()->user()->hasRole('owner'))
        <main>
            <h1 class="text-center" style="color: var(--dark)">
                Bienvenido, {{ auth()->user()->name }}!
            </h1>
            <div class="grid">

                <button class="component right" style="background-image: url('{{ URL::asset('cargar-turno.png') }}');" aria-label="Cargar Turno" onclick="location.href='{{ route('owner-turns') }}';"></button>


                <button class="component right" style="background-image: url('{{ URL::asset('mi-calendario.png') }}');"
                    onclick="location.href='{{ route('calendar.owner') }}';">
                </button>

                <button class="component right" style="background-image: url('{{ URL::asset('mis-canchas.png') }}');"
                    onclick="location.href='{{ route('fields.showMyFields') }}';">
                </button>

                <button class="component left"
                    style="background-image: url('{{ URL::asset('solicitudes-pendientes.png') }}');"
                    onclick="location.href='{{ route('turns.pending') }}';">
                </button>

            </div>

            <style>
                .grid {
                    padding-top: 15px;
                    display: grid;
                    grid-template-columns: 1.4fr 1fr;
                    /* Columna grande (2fr) a la izquierda y una más pequeña (1fr) a la derecha */
                    grid-template-rows: repeat(3, 1fr);
                    /* Tres filas iguales a la derecha para los bloques pequeños */
                    gap: 11.5px;

                }

                .component {
                    position: relative;
                    color: rgb(240, 248, 255);
                    text-shadow: 0 0 3px #130101, 0 0 5px #000000;
                    padding: 30px 95px 95px 30px;
                    text-align: center;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 26px;
                    transition: background-color 0.3s;
                    background-repeat: no-repeat;
                    background-size: 97.5%;
                    /* Cambia esto a 90% para que cubra el 90% del botón */
                    background-position: center;
                    /* Centra la imagen */
                }

                .component.left {
                    grid-column: 1 / 2;
                    /* Para que ocupe la columna izquierda */
                    grid-row: 1 / 4;
                    /* Ocupa las 3 filas de la izquierda */
                    background-position: left;

                }

                .component.right {
                    background-position: right;

                }

                .component:hover {
                    background-color: #1080f0;
                }

                .component::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-image: inherit;
                    background-repeat: repeat;
                    opacity: 0.2;
                    z-index: -1;
                }

                @media (max-width: 600px) {
                    .grid {
                        grid-template-columns: 1fr;
                        /* Stack buttons on small screens */
                    }
                }
            </style>

        </main>
    @else
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Inicio</h1>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Canchas Recomendadas</h3>
                    </div>
                    <table>
                        <thead style="color: var(--dark);">
                            <tr>
                                <th>Canchas</th>
                                <th class="text-center">Capacidad</th>
                                <th class="text-center">Suelo</th>
                                <th class="text-center">Precio</th>
                            </tr>
                        </thead>
                        <tbody style="color: var(--dark);">
                            @foreach ($fields as $field)
                                <tr onclick="window.location='{{ route('fields.show', $field->id) }}';"
                                    style="cursor:pointer;">
                                    <td>
                                        <img src="ubi.png" alt="Ubicación">
                                        <p>{{ $field->name }}</p>
                                    </td>
                                    <td class="text-center">{{ $field->capacity }}</td>
                                    <td class="text-center">{{ $field->type }}</td>
                                    <td class="text-center">${{ $field->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="todo">
                    <div class="head">
                        <h3>Salas Recomendadas</h3>
                    </div>
                    @foreach ($rooms as $room)
                        <ul class="todo-list">
                            <li class="not-completed" onclick="window.location='{{ route('room.show', $room->id) }}';"
                                style="cursor:pointer;">
                                <p>{{ $room->name }}</p>
                                <p>Jugadores {{ $room->quantity }}/{{ $room->max }}</p>
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>

        </main>
    @endif

@endsection

<script src="script.js"></script>
