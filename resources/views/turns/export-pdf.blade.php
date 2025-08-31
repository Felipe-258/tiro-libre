<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>

</head>

<body>
    <h1>Turnos Cargados</h1>
    <p>Rango de fechas: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    <p>Estado seleccionado:
        @switch($state)
            @case('0')
                Confirmado
            @break

            @case('1')
                Cobrado
            @break

            @case('2')
                Cancelado
            @break

            @default
                Todos
        @endswitch
    </p>
    <p>Total: <strong>${{$total}}</strong></p>
    <table>
        <thead>
            <tr>
                <th>Día</th>
                <th>Hora</th>
                <th>Nombre</th>
                <th>Cancha</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Metodo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingTurns as $pendingTurn)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($pendingTurn->day)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($pendingTurn->day)->format('H:i') }}</td>
                    <td>{{ $pendingTurn->player }}</td>
                    <td>{{ $pendingTurn->field_name }}</td>
                    <td>{{ '$' . $pendingTurn->cost }}</td>
                    <td>
                        @switch($pendingTurn->state)
                            @case(1)
                                <p style="color: green;"><strong>Cobrado</strong>
                                </p>
                            @break

                            @case(0)
                                <p style="color: orange;"><strong>Confirmado</strong></p>
                            @break

                            @case(2)
                                <p style="color: red;"><strong>Cancelado</strong></p>
                            @break
                        @endswitch
                    </td>
                    @if ($pendingTurn->deny)
                        <td>{{ $pendingTurn->source }} <span style="color: red;">(Eliminado)</span></td>
                    @else
                        <td>{{ $pendingTurn->source }}</td>
                    @endif



                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
