<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Field;
use App\Models\FieldPhoto;
use App\Models\Rating;
use App\Models\User;
use App\Models\OwnerTurns;
use App\Models\Turn;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File; // Make sure to only use this import

class FieldController extends Controller
{
    public function index(): View
    {
        $fields = Field::orderBy('name', 'asc')->paginate(0);
        return view('fields.index', ['fields' => $fields]);

    }

    public function indexTeam(): View
    {
        return view('team.team');

    }

    public function allTeam(): View
    {
        $coordinatesAll = DB::table("fields")->select("coordinates", "name")->get();
        $coordinatesClean = [];
        foreach ($coordinatesAll as $coordinates) {
            $cleanCoordinates = explode(', ', $coordinates->coordinates);
            $coordinatesClean[] = [
                'coordinates' => $cleanCoordinates,
                'name' => $coordinates->name
            ];
        }
        return view('fields.allFields', ['coordinatesAll' => $coordinatesClean]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldRequest $request): RedirectResponse
    {
        $id = auth()->user()->id;
        $data = $request->except('_token');
        $data['id_owner'] = $id;

        try {

            // Crear el campo (field) si todas las fotos son válidas
            $field = Field::create($data);

            // Almacenar las fotos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    // Verificar si el archivo es una imagen válida
                    if ($photo->isValid()) {
                        $path = $photo->store('field_photos', 'public');
                        
                        // Guardar la foto en la base de datos
                        FieldPhoto::create([
                            'field_id' => $field->id,
                            'photo_path' => $path,
                        ]);
                    } else {
                        flash_notification('El archivo no es válido. Solo se permiten imágenes.');
                        return redirect()->route('fields.create');
                    }
                }
            }

            // Notificación de éxito
            flash_notification('La cancha ha sido creada con éxito');

        } catch (\Exception $e) {
            // Notificación de error
            flash_notification('Las imagenes ingresadas no son validas. Solo formatos .jpeg .png .gif');
            return redirect()->route('fields.create');        
        }

        return redirect()->route('fields.create');
    }

    // Método para validar las fotos
    private function validatePhoto($photo)
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
        if (!in_array($photo->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('El archivo no es una imagen válida.');
        }
    }
    
    public function userCoordinates(Request $request)
    {
        //En un futuro: establecer un limite para las respuestas de cercania (de momento no es necesario porque trabajamos a nivel gchu)
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $fieldIds = json_decode($request->input('field_ids'), true); //traemos id y no directamente el objeto porque nos transforma todo en json y no lo podemos tratar como objeto cancha               

        $fields = Field::selectRaw("*,
        (6371 * acos(cos(radians(?))
        * cos(radians(SUBSTRING_INDEX(coordinates, ',', 1)))
        * cos(radians(SUBSTRING_INDEX(coordinates, ',', -1)) - radians(?))
        + sin(radians(?))
        * sin(radians(SUBSTRING_INDEX(coordinates, ',', 1))))) AS distance",
            [$latitude, $longitude, $latitude]
        )
            ->whereIn('id', $fieldIds)  //realizamos la consulta y ahora si podemos trabajar en el mismo blade
            ->orderBy('distance')
            ->paginate(0);

        /* foreach ($fields as $field) {
            list($lat, $lon) = explode(",",$field->coordinates);
            $lat = trim($lat);
            $lon = trim($lon);
        } */


        /* dd($fields['data']); */
        return view('fields.index', [
            'fields' => $fields,
        ]);
    }

    public function getCoordinatesAttribute($url)
    {
        /* $url = "https://www.google.com/maps/place/Space+Needle/@47.620506,-122.349277,17z/data=!4m6!1m3!3m2!1s0x5490151f4ed5b7f9:0xdb2ba8689ed0920d!2sSpace+Needle!3m1!1s0x5490151f4ed5b7f9:0xdb2ba8689ed0920d"; */
        $url_coordinates_position = strpos($url, '@') + 1;
        $coordinates = substr($url, $url_coordinates_position);
        // Now $coordinates contains "47.620506,-122.349277"
        list($latitude, $longitude) = explode(',', $coordinates);
        
        
        flash_notification('Ordenado con exito');
        return view('fields.index', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    /**
     * Display the specified resource.
     */
    /*     public function show(Field $field): View
        {
            $user = User::find($field->id_owner);
            
            // Convert coordinates to an array of latitude and longitude
            $coordinates = explode(', ', $field->coordinates);
        
            return view('fields.show', [
                'field' => $field,
                'user' => $user,
                'coordinates' => [
                    'latitude' => $coordinates[0],
                    'longitude' => $coordinates[1],
                ],
            ]);
        } */
        public function show(Request $request, $fieldId)
        {
            $field = Field::findOrFail($fieldId);
            $user = User::find($field->id_owner);
            $selectedDate = $request->input('day', now()->format('Y-m-d')); // Fecha actual por defecto
            
            // Obtener horas reservadas de Turn
            $reservedTurns = Turn::where('id_field', $fieldId)
                ->whereDate('day', $selectedDate)
                ->get()
                ->pluck('day')
                ->map(function ($dateTime) {
                    return date('H:i', strtotime($dateTime));
                });
        
            // Obtener las coordenadas
            $coordinates = explode(', ', $field->coordinates);
        
            // Obtener las fotos
            $photos = $field->photos;
        
            // Definir el rango de horas
            $start = Carbon::parse($field->start);
            $end = Carbon::parse($field->end);
        
            $hours = collect();
            while ($start->lessThanOrEqualTo($end)) {
                $hours->push($start->format('H:i'));
                $start->addHour(); // Incrementar una hora
            }
        
            // Obtener la calificación actual del usuario autenticado
            $userIdAuth = Auth::id();
            $rating = Rating::where('id_player', $userIdAuth)
                ->where('id_field', $fieldId)
                ->first();
        
            // Crear un array para las horas ocupadas
            $occupiedHours = $reservedTurns->toArray();
        
            // Verificar turnos en OwnerTurns y agregarlos al mismo array
            foreach ($hours as $hour) {
                $timestamp = $selectedDate . ' ' . $hour; // Combinar fecha y hora
                $existingTurn = OwnerTurns::where('id_field', $fieldId)
                    ->where('day', $timestamp)
                    ->first();
        
                if ($existingTurn) {
                    $occupiedHours[] = $hour; // Agregar hora ocupada al array
                }
            }
        
            // Eliminar duplicados y ordenar las horas ocupadas
            $occupiedHours = array_unique($occupiedHours);
            sort($occupiedHours);
            
            // Pasar las horas ocupadas a la vista
            return view('fields.show', [
                'field' => $field,
                'user' => $user,
                'selectedDate' => $selectedDate,
                'hours' => $hours,
                'reservedTurns' => $reservedTurns,
                'occupiedHours' => $occupiedHours, // Horas ocupadas combinadas
                'coordinates' => [
                    'latitude' => $coordinates[0],
                    'longitude' => $coordinates[1],
                ],
                'rating' => $rating ? $rating->rating : null,
                'photos' => $photos
            ]);
        }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'capacity' => 'nullable|in:Todos,8,10,12,14,16,18,20,22',
            'type' => 'nullable|string|max:50',
        ]);

        $search = $validated['search'];
        $price = $validated['price'];
        $capacity = $validated['capacity'];
        $type = $validated['type'];

        session([
            'search' => $search,
            'price' => $price,
            'capacity' => $capacity,
            'type' => $type,
        ]);

        $capacity = ($capacity !== "Todos") ? (int) $capacity : null;

        $fields = Field::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
            ->when($price, function ($query, $price) {
                return $query->where('price', '<=', $price);
            })
            ->when($capacity, function ($query, $capacity) {
                return $query->where('capacity', $capacity);
            })
            ->when($type && $type !== "Todos", function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->orderBy('name', 'asc')
            ->paginate(0);

        return view('fields.index', [
            'fields' => $fields,
        ]);
    }



    public function showMyFields(): View
    {
        $userId = Auth::id();
        $fields = Field::where('id_owner', $userId)->get();

        return view('fields.myFields', [
            'fields' => $fields,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Field $field): View
    {
        return view('fields.edit', [
            'field' => $field
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldRequest $request, Field $field): RedirectResponse
    {
        
        
        // Verifica si se ha cargado una nueva imagen
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                // Verificar si el archivo es una imagen válida
                if ($photo->isValid()) {
                    $path = $photo->store('field_photos', 'public');
                    
                    // Guardar la foto en la base de datos
                    FieldPhoto::create([
                        'field_id' => $field->id,
                        'photo_path' => $path,
                    ]);
                } else {
                    flash_notification('El archivo no es válido. Solo se permiten imágenes.');
                    return redirect()->route('fields.create');
                }
            }
        }
        

        // Actualiza el campo con los datos validados
        $field->update($request->validated());
        flash_notification('Cancha actualizada correctamente.');
        return redirect()->back()/* 
->withSuccess('Field is updated successfully.') */ ;


    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Field $field): RedirectResponse
    {
        $field->delete();
        flash_notification('Cancha eliminada correctamente.');

        return redirect()->route('fields.index');
    }

    public function deletePhoto($id)
    {
        // Busca la foto por ID
        $photo = FieldPhoto::find($id);

        if ($photo) {
            $path = storage_path('app/public/' . $photo->photo_path);

            // Verifica si el archivo existe y lo elimina
            if (File::exists($path)) {
                File::delete($path);
                // Elimina el registro de la base de datos
                $photo->delete();
                flash_notification('Foto Eliminada con exito');
                return redirect()->back();
            } else {
                flash_notification('Error al eliminar la foto');
                return redirect()->back();
            }
        } else {
            flash_notification('Error al eliminar la foto');
            return redirect()->back();
        }
    }

}
