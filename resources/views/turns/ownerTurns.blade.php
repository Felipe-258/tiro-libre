@extends('screens.app')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @endif

    <!-- CSS de DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- JS de jQuery y DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Moment.js para manejar la fecha y hora -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/dataRender/moment.js"></script>

    {{-- Fuente Monospace --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Azeret+Mono:ital,wght@0,100..900;1,100..900&family=B612+Mono&family=Cutive+Mono&display=swap"
        rel="stylesheet">
    @php
        $total = 0;
    @endphp
    {{-- <br>
    <h1 class="text-center" style="color: var(--dark);"> A Pate<span class="highlight"
            style="background-color: rgb( 118, 198, 224); border-radius: 5px; padding: 3px; color: white; ">ar</span>
    </h1> --}}
    <div class="row justify-content-center mt-3">
        <div class="col-md-11">

            <div class="card">
                <div id="turnsContainer" class="minimized">
                    <div class="card-header"><button id="toggleButton" class="newturn_button">Cargar un nuevo
                            Turno</button></div>
                    <div id="turnsContent" style="display: none;">
                        {{-- <div class="card-header">Cargar un nuevo Turno</div> --}}

                        <div class="card-body">

                            <form action="{{ route('turns.owner.createTurn') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre
                                        del
                                        Jugador:</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" required maxlength="30">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="name"
                                        class="col-md-4 col-form-label text-md-end text-start">Fecha</label>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" type="date" id="day" name="day"
                                                value="{{ $date->format('Y-m-d') }}" {{-- min="{{ $date->format('Y-m-d') }}" --}}>
                                        </div>
                                    </div>

                                </div>
                                {{-- <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start">Hora</label>
                            <div class="col-md-6">
                                <select class="col-md-6" name="hour" id="hour"
                                    style="width: 100%; height: 100%; text-align: center; background-color: var(--light); color: var(--dark);border-radius:5px;">
                                    @foreach ($hours as $hour)
                                        <option class="form-control" value="{{ $hour }}">{{ $hour }}:00
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                                <div class="mb-3 row">
                                    <label for="name"
                                        class="col-md-4 col-form-label text-md-end text-start">Hora</label>
                                    <div class="col-md-6 d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-secondary" id="prevHour">◀</button>
                                        <div class="hour-display"
                                            style="width: 100%; text-align: center; background-color: var(--light); color: var(--dark); border-radius: 5px;">
                                            <span id="selectedHour">{{ $date->format('H:i') }}</span>
                                        </div>
                                        <button type="button" class="btn btn-secondary" id="nextHour">▶</button>
                                    </div>
                                </div>
                                <input type="hidden" name="hour" id="hourInput" value="{{ $date->format('H:i:s') }}">
                                <div class="mb-3 row">
                                    <label for="name"
                                        class="col-md-4 col-form-label text-md-end text-start">Cancha</label>
                                    <div class="col-md-6">
                                        <select class="col-md-6" name="field" id="field"
                                            style="width: 100%; height: 100%; text-align: center; background-color: var(--light); color: var(--dark);border-radius:5px; ">
                                            @foreach ($fields as $field)
                                                <option class="form-control" value="{{ $field->id }}">
                                                    {{ $field->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"></label>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-info form-button">Cargar</button>
                                    </div>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('toggleButton').addEventListener('click', function() {
            var photosContainer = document.getElementById('turnsContent');
            if (photosContainer.style.display === 'none') {
                photosContainer.style.display = 'block';
            } else {
                photosContainer.style.display = 'none';
            }
        });
    </script>

    <div class="row justify-content-center mt-3">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header">Turnos Cargados</div>
                <div class="card-body">
                    @if ($pendingTurns->isEmpty())
                        <p>No hay Turnos Cargados.</p>
                    @else
                        <div class="row justify-content-center mt-3">
                            <div class="col-md-11">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="minDate">Desde:</label>
                                        <input type="date" id="minDate" class="form-control"
                                            value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="maxDate">Hasta:</label>
                                        <input type="date" id="maxDate" class="form-control"
                                            value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fieldSelect">Cancha:</label>
                                        <select id="fieldSelect" class="form-control">
                                            <option value="">Todas las canchas</option>
                                            @foreach ($fields as $field)
                                                <option value="{{ $field->name }}">{{ $field->name }}</option>
                                                <i></i>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button id="filterButton" class="btn btn-info">Filtrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <br>
                <table id="turnsTable" class="cell-border">
                    <thead>
                        <tr>
                            <th>Día y Hora</th>
                            {{-- <th>Hora</th> --}}
                            <th>Nombre</th>
                            <th>Cancha</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingTurns as $pendingTurn)
                            <tr>
                                <td class="monospace-date">
                                    {{ \Carbon\Carbon::parse($pendingTurn->day)->format('d/m/Y H:i') }}</td>
                                {{-- <td>{{ \Carbon\Carbon::parse($pendingTurn->day)->format('H:i') }}</td> --}}
                                <td>{{ $pendingTurn->player }}</td>
                                <td>{{ $pendingTurn->field_name }}</td>
                                <td>{{ '$' . $pendingTurn->cost }}</td>
                                <td >@switch($pendingTurn->state)
                                                    @case(0)
                                                    <p class="text-warning text-center" >Confirmado</p>
                                                    @break

                                                    @case(1)
                                                    <p class="text-success text-center" >Cobrado</p>

                                                    @break

                                                    @case(2)
                                                    <p class="text-danger text-center"  >Cancelado</p>

                                                    @break

                                                    @default
                                                        Desconocido
                                                @endswitch
                                    </td>

                                <td class="text-center">
                                    <div style="display: inline-block;">
                                        <button
                                            onclick="showForm('{{ $pendingTurn->id }}', '{{ $pendingTurn->source }}', '{{ $pendingTurn->player }}',  '{{ \Carbon\Carbon::parse($pendingTurn->day)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($pendingTurn->day)->format('H:i') }}', '{{ $pendingTurn->field_name }}', '{{ $pendingTurn->state }}')"
                                            type="button" class="btn btn-warning"><i class='bx bx-edit'></i></button>

                                    </div>
                                    <div style="display: inline-block;">
                                        <form
                                            action="{{ $pendingTurn->source === 'offline' ? route('turns.owner.eraseTurn', $pendingTurn->id) : route('turns.destroy', $pendingTurn->id) }}"
                                            method="POST" class="deny-turn" style="display: inline;">
                                            @csrf
                                            @if ($pendingTurn->source === 'online')
                                                @method('DELETE')
                                            @endif
                                            <button type="submit" class="btn btn-danger"><i
                                                    class='bx bx-trash'></i></button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                            @php
                                if ($pendingTurn->state == 1) {
                                    $total = $total + $pendingTurn->cost;
                                }

                            @endphp
                        @endforeach
                    </tbody>
                    {{-- @dd($fields) --}}
                </table>

                {{-- <h1 class="text-center" style="color: var(--dark);"> Total Facturado: <span class="highlight" style="background-color: #02386d; border-radius: 5px; padding: 5px; color: white; ">${{ $total}}</span> </h1> --}}
                <h2 class="text-center" style="color: var(--dark);"> Total Cobrado: <span id="totalAmount"
                        class="highlight"
                        style="background-color: #02386d; border-radius: 5px; padding: 5px; color: white;">${{ $total }}</span>
                </h2>
                <br>
                {{-- <a href="{{ route('export.turns.pdf') }}" class="btn btn-primary">Exportar a PDF</a> --}}
                <p style="height: 3px"></p>
                <button id="exportTurnsBtn" class="btn btn-primary">Exportar Turnos a PDF</button>
                @endif
            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#turnsTable').DataTable({
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron registros",
                    "info": "Mostrando _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros en total)", // Esto se ajustará automáticamente
                    "search": "Buscar:",
                    "paginate": {
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "columnDefs": [{
                    "targets": 0, // Índice de la columna de fecha
                    "render": function(data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return moment(data, 'DD/MM/YYYY HH:mm').unix();
                        }
                        return data;
                    }
                }]
            });

            // Filtro personalizado para fechas y cancha
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var min = $('#minDate').val() ? moment($('#minDate').val(), 'YYYY-MM-DD') : null;
                    var max = $('#maxDate').val() ? moment($('#maxDate').val(), 'YYYY-MM-DD') : null;
                    var date = moment(data[0], 'DD/MM/YYYY'); // Ajusta al formato de la columna de fecha
                    var selectedField = $('#fieldSelect').val();
                    var fieldName = data[3]; // Columna de nombre de cancha

                    if (
                        (!min || date.isSameOrAfter(min)) &&
                        (!max || date.isSameOrBefore(max)) &&
                        (selectedField === "" || fieldName === selectedField)
                    ) {
                        return true;
                    }
                    return false;
                }
            );

            // Aplicar el filtro y recalcular el total
            $('#filterButton').on('click', function() {
                table.draw();
                calculateTotal();
                updateInfo();
            });


            // Recalcular el total facturado según el filtro aplicado
            function calculateTotal() {
                let total = 0;
                table.rows({
                    search: 'applied'
                }).every(function(rowIdx, tableLoop, rowLoop) {
                    var rowData = this.data();
                    var price = parseFloat(rowData[3].replace('$', '')); // Columna de precio
                    var state = rowData[4]; // Estado
                    if (state.includes('Cobrado')) {
                        total += price;
                    }
                });
                $('#totalAmount').text(`$${total.toFixed(0)}`); // Actualizar el total en el HTML
            }

            // Ajustar la información de total filtrado
            function updateInfo() {
                var info = table.page.info();
                $('.dataTables_info').html(
                    `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsDisplay} registros`);
            }

            // Aplicar el filtro y recalcular el total
            $('#filterButton').on('click', function() {
                table.draw();
                calculateTotal();
                updateInfo(); // Actualizar información después de aplicar el filtro
            });

            // Ejecutar al cargar la página para calcular total y ajustar info
            $('#filterButton').click();

            // Calcular el total y ajustar la información cuando la tabla se dibuje por cualquier razón
            table.on('draw', function() {
                calculateTotal();
                updateInfo(); // Llamar a la función para actualizar la información
            });
        });
    </script>



    <script>
        /* ELIMINAR Turno */

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form.deny-turn').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevenir el envío por defecto
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¿Quieres eliminar el turno?",
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

        function showForm(id, table, playerName, day, hour, fieldName, state) {
            const stateOptions = [{
                    value: "0",
                    text: "Confirmado",
                    color: "#FFC107"
                }, // Amarillo
                {
                    value: "1",
                    text: "Cobrado",
                    color: "#28A745"
                }, // Verde
                {
                    value: "2",
                    text: "Cancelado",
                    color: "#DC3545"
                } // Rojo
            ];

            const stateOptionsHTML = stateOptions.map(option => `
        <option value="${option.value}" style="color: ${option.color};" ${state == option.value ? 'selected' : ''}>
            ${option.text}
        </option>
    `).join('');

            Swal.fire({
                title: 'Formulario',
                html: `
        <form id="myForm" method="POST" action="/turns/change-state/${table}">
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" name="name" class="swal2-input" placeholder="Nombre" value="${playerName}" disabled>
            <input type="date" name="day" class="swal2-input" value="${day}" disabled>
            <input type="text" name="hour" class="swal2-input" placeholder="Hora" value="${hour}" disabled>
            <input type="text" name="field" class="swal2-input" placeholder="Cancha" value="${fieldName}" disabled>
            <select name="state" class="swal2-input">
                ${stateOptionsHTML}
            </select>
        </form>
        `,
                showCancelButton: false,
                confirmButtonText: 'Enviar',
                focusConfirm: false,
                preConfirm: () => {
                    document.getElementById('myForm').submit(); // Enviar el formulario
                }
            });
        }
    </script>
    <script>
        document.getElementById('exportTurnsBtn').addEventListener('click', function() {
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().split('T')[0];

            Swal.fire({
                title: 'Exportar Turnos',
                html: `
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <label for="startDate" style="margin-bottom: 5px;">Fecha inicio:</label>
                <input type="date" id="startDate" class="swal2-input" value="${firstDay}" style="padding: 10px; border-radius: 4px; border: 1px solid #ccc;">

                <label for="endDate" style="margin-bottom: 5px;">Fecha fin:</label>
                <input type="date" id="endDate" class="swal2-input" value="${lastDay}" style="padding: 10px; border-radius: 4px; border: 1px solid #ccc;">

                <label for="state" style="margin-bottom: 5px;">Seleccionar estado:</label>
                <select id="state" class="swal2-input" style="padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
                    <option value="all">Todos</option>
                    <option value="0">Confirmado</option>
                    <option value="1">Cobrado</option>
                    <option value="2">Cancelado</option>
                </select>

                <label for="fieldID" style="margin-bottom: 5px;">Cancha:</label>
                <select id="fieldID" class="swal2-input" style="padding: 10px; border-radius: 4px; border: 1px solid #ccc;">
    @foreach ($fields as $fieldCurrent)
        <option value="{{ $fieldCurrent->id }}">{{ $fieldCurrent->name }}</option>
    @endforeach
</select>



            </div>
        `,
                focusConfirm: false,
                preConfirm: () => {
                    const startDate = document.getElementById('startDate').value;
                    const endDate = document.getElementById('endDate').value;
                    const state = document.getElementById('state').value;
                    const field = document.getElementById('fieldID').value;


                    if (!startDate || !endDate) {
                        Swal.showValidationMessage('Debes seleccionar un rango de fechas');
                        return false;
                    }

                    return {
                        startDate,
                        endDate,
                        state,
                        field
                    };
                },
                customClass: {
                    popup: 'custom-swal-width custom-swal-bg',
                    title: 'custom-swal-title',
                    content: 'custom-swal-text',
                    input: 'custom-swal-input',
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const {
                        startDate,
                        endDate,
                        state,
                        field
                    } = result.value;
                    window.location.href =
                        `/export-turns?startDate=${startDate}&endDate=${endDate}&state=${state}&field=${field}`;

                }
            });
        });
    </script>
    <script>
        const hours = @json($hours);
        let currentHourIndex = 0;

        document.getElementById('prevHour').addEventListener('click', function() {
            currentHourIndex = (currentHourIndex > 0) ? currentHourIndex - 1 : hours.length - 1;
            updateHourDisplay();
        });

        document.getElementById('nextHour').addEventListener('click', function() {
            currentHourIndex = (currentHourIndex < hours.length - 1) ? currentHourIndex + 1 : 0;
            updateHourDisplay();
        });

        function updateHourDisplay() {
            const selectedHour = hours[currentHourIndex];
            document.getElementById('selectedHour').textContent = selectedHour + ":00";
            document.getElementById('hourInput').value = selectedHour; // Actualiza el valor del campo oculto
        }
    </script>
    <style>
        .dataTables_wrapper .dataTables_length select {
            color: var(--dark);
        }

        table.dataTable.order-column>tbody tr>.sorting_1,
        table.dataTable.order-column>tbody tr>.sorting_2,
        table.dataTable.order-column>tbody tr>.sorting_3,
        table.dataTable.display>tbody tr>.sorting_1,
        table.dataTable.display>tbody tr>.sorting_2,
        table.dataTable.display>tbody tr>.sorting_3 {
            background-color: var(--grey);
        }

        .sorting_1 {
            background-color: var(--grey);
        }

        .sorting_asc {
            background-color: var(--grey);
        }

        .dataTables_length {
            padding-bottom: 15px;
        }

        .dataTables_wrapper .dataTables_filter input {
            color: var(--dark);
        }

        .newturn_button {
            color: var(--dark);
            width: 100%;
            border: none;
            background-color: transparent;
            align-content: left;
            border-radius: 3px;
        }

        .newturn_button:hover {
            background-color: rgba(0, 0, 0, 0.100);
            color: var(--blue);
        }

        .monospace-date {

            font-family: "B612 Mono", serif;
            /* font-weight: 400;
            font-style: normal; */
        }
    </style>
@endsection
