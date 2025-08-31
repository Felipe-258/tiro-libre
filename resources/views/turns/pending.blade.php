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





<div class="row justify-content-center mt-3">
    <div class="col-md-11">
        <div class="card">
            <div class="card-header">Solicitudes Pendientes</div>
            <div class="card-body">

                @if($pendingTurns->isEmpty())
                    <p>No hay solicitudes pendientes.</p>
                @else
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Jugador</th>
                                <th>Cancha</th>
                                <th >Día</th>
                                <th>Hora</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingTurns as $pendingTurn)
                                <tr>
                                    <td ><img src="{{ URL::asset($pendingTurn->player->photo) }}" alt="User Photo" width="50px"> {{ $pendingTurn->player->name . ' ' . $pendingTurn->player->surname}}</td>
                                    <td>{{ $pendingTurn->field->name }}</td>
									<td>{{ \Carbon\Carbon::parse($pendingTurn->day)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pendingTurn->day)->format('H:i') }}</td>
                                    <td><a href="" class="phone-whatsapp">  {{ substr($pendingTurn->player->phone, 2) }} <div style="display: inline-block;">
                                        <button style="background-color: transparent;
                                            border: 0ch; color: var(--dark);"><i class='bx bxl-whatsapp'></i></button>
                                    </div> </a></td>
                                    <td></strong>
                                        <div style="display: inline-block;">
                                            <form action="{{ route('turns.approve', $pendingTurn->id) }}" method="POST" class="accept-turn" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success"><i class='bx bx-check'></i></button>
                                            </form>
                                        </div>
                                        <div style="display: inline-block;">
                                            <form action="{{ route('turns.deny', $pendingTurn->id) }}" method="POST" class="deny-turn" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger"><i class='bx bx-trash'></i></button>
                                            </form>
                                        </div>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<style>

</style>

<script>
        
    /* ELIMINAR LA SALA */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.deny-turn').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío por defecto
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres rechazar el turno?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, rechazar',
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
    document.querySelectorAll('form.accept-turn').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío por defecto

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Quieres aceptar el turno?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, aceptar',
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