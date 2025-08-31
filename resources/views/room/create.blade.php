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
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Crear una nueva sala
                </div>
                <div class="float-end">
                    <a href="{{ route('room') }}" class="btn btn-primary btn-sm">&larr; Atrás</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('room.store') }}" method="post" class="create-room" >
                    @csrf

                    <div class="mb-3 row">
                        <label for="id_turn" class="col-md-4 col-form-label text-md-end text-start">Tus reservas</label>
                        <div class="col-md-6">
                            <select name="id_turn" id="id_turn">
                                @if($turns->isEmpty())
                                    <option disabled>
                                        No tienes reservas
                                    </option>
                                @else
                                    @foreach($turns as $turn)
                                        <option value="{{ $turn->id }}">
                                            {{ \Carbon\Carbon::parse($turn->day)->format('d-m-Y H:i') }} - {{ $turn->field->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            
                            
                            @if ($errors->has('id_turn'))
                                <span class="text-danger">{{ $errors->first('id_turn') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre de la sala</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="quantity" class="col-md-4 col-form-label text-md-end text-start">Jugadores unidos</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @if ($errors->has('quantity'))
                                <span class="text-danger">{{ $errors->first('quantity') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="max" class="col-md-4 col-form-label text-md-end text-start">Capacidad Maxima</label>
                        <div class="col-md-6">
                            <div class="col-md-6 content-select">
                                <select name="max" id="max" class="form-select">
                                    <option value="10" selected>10</option>
                                    <option value="12">12</option>
                                    <option value="14">14</option>
                                    <option value="16">16</option>
                                    <option value="18">18</option>
                                    <option value="20">20</option>
                                    <option value="22">22</option>
                                </select>
                                @if ($errors->has('max'))
                                    <span class="text-danger">{{ $errors->first('max') }}</span>
                                @endif
                                <i></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Descripcion</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Crear">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>


<script>
        
    /* CREAR LA SALA */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.create-room').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevenir el envío por defecto

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Quieres crear esta sala?",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, crear',
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
