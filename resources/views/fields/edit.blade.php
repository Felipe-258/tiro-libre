@extends('screens.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="row justify-content-center mt-3">
    <div class="col-md-8">

        @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                {{ $message }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Editar cancha
                </div>
                <div class="float-end">
                    <a href="{{ route('fields.showMyFields') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('fields.update', $field->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("PUT")                    

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Nombre</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $field->name }}" maxlength="30">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="type" class="col-md-4 col-form-label text-md-end text-start">Capacidad</label>
                        <div class="col-md-6 content-select">
                            <select name="capacity" id="capacity" class="form-select">
                                <option value="10" {{ $field->capacity == 10 ? 'selected' : '' }}>10</option>
                                <option value="12" {{ $field->capacity == 12 ? 'selected' : '' }}>12</option>
                                <option value="14" {{ $field->capacity == 14 ? 'selected' : '' }}>14</option>
                                <option value="16" {{ $field->capacity == 16 ? 'selected' : '' }}>16</option>
                                <option value="18" {{ $field->capacity == 18 ? 'selected' : '' }}>18</option>
                                <option value="20" {{ $field->capacity == 20 ? 'selected' : '' }}>20</option>
                                <option value="22" {{ $field->capacity == 22 ? 'selected' : '' }}>22</option>
                            </select>
                            @if ($errors->has('capacity'))
                                <span class="text-danger">{{ $errors->first('capacity') }}</span>
                            @endif
                            <i></i>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="coordinates" class="col-md-4 col-form-label text-md-end text-start">Coordenadas</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('coordinates') is-invalid @enderror" id="coordinates" name="coordinates" value="{{ $field->coordinates }}" required>
                            @if ($errors->has('coordinates'))
                                <span class="text-danger">{{ $errors->first('coordinates') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="price" class="col-md-4 col-form-label text-md-end text-start">Precio</label>
                        <div class="col-md-6">
                          <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $field->price }}" required min="1" max="999999999">
                            @if ($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="type" class="col-md-4 col-form-label text-md-end text-start"><Tarea></Tarea>Terreno</label>
                        <div class="col-md-6 content-select">
                            <select name="type" id="type" class="form-select">
                                <option value="Sintetico" {{ $field->type == 'Sintetico' ? 'selected' : '' }}>Sintetico</option>
                                <option value="Natural" {{ $field->type == 'Natural' ? 'selected' : '' }}>Natural</option>
                                <option value="Tierra" {{ $field->type == 'Tierra' ? 'selected' : '' }}>Tierra</option>
                                <option value="Cemento" {{ $field->type == 'Cemento' ? 'selected' : '' }}>Cemento</option>
                                <option value="Arena" {{ $field->type == 'Arena' ? 'selected' : '' }}>Arena</option>
                            </select>
                            @if ($errors->has('type'))
                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            @endif
                            <i></i> 
                        </div>
                    </div> 

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Descripción</label>
                        <div class="col-md-6">
                            <textarea maxlength="255" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ $field->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="start" class="col-md-4 col-form-label text-md-end text-start"><Tarea></Tarea>Inicio del servicio</label>
                        <div class="col-md-6 content-select">
                            <select name="start" id="start" class="form-select">
                                <option value="00:00" {{ $field->start == '00:00:00' ? 'selected' : '' }}>00:00</option>
                                <option value="01:00" {{ $field->start == '01:00:00' ? 'selected' : '' }}>01:00</option>
                                <option value="02:00" {{ $field->start == '02:00:00' ? 'selected' : '' }}>02:00</option>
                                <option value="03:00" {{ $field->start == '03:00:00' ? 'selected' : '' }}>03:00</option>
                                <option value="04:00" {{ $field->start == '04:00:00' ? 'selected' : '' }}>04:00</option>
                                <option value="05:00" {{ $field->start == '05:00:00' ? 'selected' : '' }}>05:00</option>
                                <option value="06:00" {{ $field->start == '06:00:00' ? 'selected' : '' }}>06:00</option>
                                <option value="07:00" {{ $field->start == '07:00:00' ? 'selected' : '' }}>07:00</option>
                                <option value="08:00" {{ $field->start == '08:00:00' ? 'selected' : '' }}>08:00</option>
                                <option value="09:00" {{ $field->start == '09:00:00' ? 'selected' : '' }}>09:00</option>
                                <option value="10:00" {{ $field->start == '10:00:00' ? 'selected' : '' }}>10:00</option>
                                <option value="11:00" {{ $field->start == '11:00:00' ? 'selected' : '' }}>11:00</option>
                                <option value="12:00" {{ $field->start == '12:00:00' ? 'selected' : '' }}>12:00</option>
                                <option value="13:00" {{ $field->start == '13:00:00' ? 'selected' : '' }}>13:00</option>
                                <option value="14:00" {{ $field->start == '14:00:00' ? 'selected' : '' }}>14:00</option>
                                <option value="15:00" {{ $field->start == '15:00:00' ? 'selected' : '' }}>15:00</option>
                                <option value="16:00" {{ $field->start == '16:00:00' ? 'selected' : '' }}>16:00</option>
                                <option value="17:00" {{ $field->start == '17:00:00' ? 'selected' : '' }}>17:00</option>
                                <option value="18:00" {{ $field->start == '18:00:00' ? 'selected' : '' }}>18:00</option>
                                <option value="19:00" {{ $field->start == '19:00:00' ? 'selected' : '' }}>19:00</option>
                                <option value="20:00" {{ $field->start == '20:00:00' ? 'selected' : '' }}>20:00</option>
                                <option value="21:00" {{ $field->start == '21:00:00' ? 'selected' : '' }}>21:00</option>
                                <option value="22:00" {{ $field->start == '22:00:00' ? 'selected' : '' }}>22:00</option>
                                <option value="23:00" {{ $field->start == '23:00:00' ? 'selected' : '' }}>23:00</option>
                            </select>                            
                            @if ($errors->has('start'))
                                <span class="text-danger">{{ $errors->first('start') }}</span>
                            @endif
                            <i></i>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="end" class="col-md-4 col-form-label text-md-end text-start"><Tarea></Tarea>Fin del servicio</label>
                        <div class="col-md-6 content-select">
                            <select name="end" id="end" class="form-select">
                                <option value="00:00" {{ $field->end == '00:00:00' ? 'selected' : '' }}>00:00</option>
                                <option value="01:00" {{ $field->end == '01:00:00' ? 'selected' : '' }}>01:00</option>
                                <option value="02:00" {{ $field->end == '02:00:00' ? 'selected' : '' }}>02:00</option>
                                <option value="03:00" {{ $field->end == '03:00:00' ? 'selected' : '' }}>03:00</option>
                                <option value="04:00" {{ $field->end == '04:00:00' ? 'selected' : '' }}>04:00</option>
                                <option value="05:00" {{ $field->end == '05:00:00' ? 'selected' : '' }}>05:00</option>
                                <option value="06:00" {{ $field->end == '06:00:00' ? 'selected' : '' }}>06:00</option>
                                <option value="07:00" {{ $field->end == '07:00:00' ? 'selected' : '' }}>07:00</option>
                                <option value="08:00" {{ $field->end == '08:00:00' ? 'selected' : '' }}>08:00</option>
                                <option value="09:00" {{ $field->end == '09:00:00' ? 'selected' : '' }}>09:00</option>
                                <option value="10:00" {{ $field->end == '10:00:00' ? 'selected' : '' }}>10:00</option>
                                <option value="11:00" {{ $field->end == '11:00:00' ? 'selected' : '' }}>11:00</option>
                                <option value="12:00" {{ $field->end == '12:00:00' ? 'selected' : '' }}>12:00</option>
                                <option value="13:00" {{ $field->end == '13:00:00' ? 'selected' : '' }}>13:00</option>
                                <option value="14:00" {{ $field->end == '14:00:00' ? 'selected' : '' }}>14:00</option>
                                <option value="15:00" {{ $field->end == '15:00:00' ? 'selected' : '' }}>15:00</option>
                                <option value="16:00" {{ $field->end == '16:00:00' ? 'selected' : '' }}>16:00</option>
                                <option value="17:00" {{ $field->end == '17:00:00' ? 'selected' : '' }}>17:00</option>
                                <option value="18:00" {{ $field->end == '18:00:00' ? 'selected' : '' }}>18:00</option>
                                <option value="19:00" {{ $field->end == '19:00:00' ? 'selected' : '' }}>19:00</option>
                                <option value="20:00" {{ $field->end == '20:00:00' ? 'selected' : '' }}>20:00</option>
                                <option value="21:00" {{ $field->end == '21:00:00' ? 'selected' : '' }}>21:00</option>
                                <option value="22:00" {{ $field->end == '22:00:00' ? 'selected' : '' }}>22:00</option>
                                <option value="23:00" {{ $field->end == '23:00:00' ? 'selected' : '' }}>23:00</option>
                            </select>                            
                            @if ($errors->has('end'))
                                <span class="text-danger">{{ $errors->first('end') }}</span>
                            @endif
                            <i></i>
                        </div>
                    </div>
                    <!-- Campo adicional para subir fotos -->
                    <div class="mb-3 row">
                        <label for="photos" class="col-md-4 col-form-label text-md-end text-start">Subir
                            Fotos</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control @error('photos') is-invalid @enderror"
                                id="photos" name="photos[]" multiple accept="image/*">
                            @if ($errors->has('photos'))
                                <span class="text-danger">{{ $errors->first('photos') }}</span>
                            @endif
                            @error('photos.*')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                        </div>
                    </div>       
                                    
                    <div class="mb-3 row">
                        <button type="submit" class="col-md-3 offset-md-5 btn btn-primary">Guardar</button>

                    </div>
                    
                </form>
                <br>
                <div class="mb-3 row">
                    <button id="togglePhotos" type="button" class="btn btn-secondary">Mostrar/Esconder Fotos</button>
                    <div id="photosContainer" style="display: none; padding: 10px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; justify-items: center;">
                            @foreach ($field->photos as $photo)
                                <form action="{{ route('photo.delete', $photo->id) }}" method="POST" style="display: flex; flex-direction: column; align-items: center;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <div style="text-align: center;">
                                        <img src="/storage/{{ $photo->photo_path }}" alt="photo" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 5px;">
                                        <button type="submit" class="btn btn-danger btn-lg" title="Eliminar foto">
                                            <i class='bx bx-trash'></i> 
                                        </button>
                                    </div>
                                </form>    
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <br>
                <div class="mb-3 row">
                    <form action="{{ route('fields.destroy', $field->id) }}" method="POST" class="col-md-3 offset-md-5">
                        @csrf
                        @method('DELETE')
                        <input type="submit" type="button" class="btn btn-danger" value="Eliminar cancha">
                    </form>
                    
                </div>
            </div>
        </div>
    </div>    
</div>
<style>.photo-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Ajusta este valor según sea necesario */
}

.photo-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    margin-right: 10px; /* Espacio entre la imagen y el botón */
}</style>


{{-- @foreach ($field->photos as $photo)
 <form action="{{ route('photo.delete', $photo->id) }}" method="POST">
    @csrf
    @method('DELETE')

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <img src="/storage/{{ $photo->photo_path }}" alt="photo" style="width: 100px; height: 100px; object-fit: cover;">
        <button type="submit" class="icon-container button3" title="Eliminar foto">
            <i class='bx bx-trash'></i>
        </button>
    </div>
    <br>
</form>    
@endforeach
 --}}

 <script>
    document.getElementById('togglePhotos').addEventListener('click', function() {
    var photosContainer = document.getElementById('photosContainer');
    if (photosContainer.style.display === 'none') {
        photosContainer.style.display = 'block';
    } else {
        photosContainer.style.display = 'none';
    }
});

 </script>
@endsection