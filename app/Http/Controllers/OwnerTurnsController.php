<?php

namespace App\Http\Controllers;

use App\Models\OwnerTurns;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Turn;
use App\Models\User;
use App\Models\Field;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB; // Para usar DB
use Spatie\Permission\Traits\HasRoles; // Para usar hasRole

class OwnerTurnsController extends Controller
{
    

    public function changeState($table, Request $request)
    {
        
        if ($table === 'offline') {
            $id = $request->id;
            //dd($id, ' ', $table);
            $editTurn = OwnerTurns::find($id);
            if ($editTurn) {
                $state = $request->state;
                $editTurn->state = $state;
                $editTurn->save();
                flash_notification(message: 'Turno Actualizado.', type: 'success');
                return redirect()->route('owner-turns');
            }

            flash_notification('Solicitud no encontrada.', 'danger');
            return redirect()->route('owner-turns');
        }
        if ($table === 'online') {
            $id = $request->id;
            $editTurn = Turn::find($id);
            if ($editTurn) {
                $state = $request->state;
                $editTurn->state = $state;
                $editTurn->save();
                flash_notification(message: 'Turno Actualizado.', type: 'success');
                return redirect()->route('owner-turns');
            }

            flash_notification('Solicitud no encontrada.', 'danger');
            return redirect()->route('owner-turns');
        }
    }

    public function ownerTurnsView($date = null)
    {
        if ($date) {
            
        } else {
            $date = $now = Carbon::now()->ceilHour();            
        }

        if (auth()->user()->hasRole('owner') || auth()->user()->hasRole('super-admin')) {
            $ownerId = auth()->user()->id;
            $ownerfields = Field::where('id_owner', $ownerId)->get();

            // Obtener la hora actual y definir los rangos de tiempo
            $now = Carbon::now();
            $startTime = $now->subHours(2);
            $endTime = (clone $now)->addDays(365);

            // Inicializar la colección para los turnos pendientes
            $pendingTurns = collect();

            // Recorrer los campos de los propietarios
            foreach ($ownerfields as $field) {
                // Consultar los turnos del propietario
                $ownerTurns = DB::table('owner_turns as ot')
                    ->join('users as o', 'ot.id_owner', '=', 'o.id')
                    ->join('fields as f', 'ot.id_field', '=', 'f.id')
                    ->where('ot.id_field', $field->id)
                    ->where('ot.deny', false)
                    /* ->where('ot.day', '>=', $startTime)
                    ->where('ot.day', '<=', $endTime) */
                    ->select('ot.*', 'o.name as player_name', 'o.surname as player_surname', 'f.name as field_name', 'f.price as field_price')
                    ->orderBy('ot.day') // Ordenar por el timestamp day
                    ->get();

                // Fusionar los turnos del propietario en la colección de turnos pendientes
                if ($ownerTurns->isNotEmpty()) {
                    $pendingTurns = $pendingTurns->merge($ownerTurns->map(function ($turn) {
                        return (object) [
                            'id' => $turn->id,
                            'day' => $turn->day,
                            'player' => $turn->player,
                            'field_name' => $turn->field_name,
                            'cost' => $turn->field_price,
                            'state' => $turn->state,
                            'source' => 'offline', // Indicar origen
                        ];
                    }));
                }

                // Consultar los turnos del canchero
                $fieldId = $field->id;
                $selectedDate = Carbon::now()->toDateString(); // Suponiendo que quieres turnos de hoy

                $cancheroTurns = Turn::where('id_field', $fieldId)
                    //->whereDate('day', $selectedDate)
                    ->with('player') // Cargar el jugador que registró el turno
                    ->get();

                // Agregar los turnos del canchero a la colección de turnos pendientes
                foreach ($cancheroTurns as $turn) {
                    $pendingTurns->push((object) [
                        'id' => $turn->id,
                        'day' => $turn->day,
                        'player' => $turn->player->name . ' ' . $turn->player->surname, // Este es el nombre del jugador que registró el turno
                        'field_name' => $field->name,
                        'cost' => $field->price,
                        'state' => $turn->state,
                        'source' => 'online', // Indicar origen
                    ]);
                }
            }
            $hours = collect(range(8, 23));
            
            $date = str_replace('P', 'T', $date); // Reemplazar la "P" por "T" para que sea un formato válido de fecha
            $date = Carbon::parse($date); // Convertir el string en una instancia de Carbon
            return view('turns.ownerTurns', [
                'pendingTurns' => $pendingTurns,
                'hours' => $hours,
                'fields' => $ownerfields,
                'date' => $date,
            ]);
        } else {
            return $this->index();
        }
    }



    public function eraseTurn($id)
    {
        $pendingTurn = OwnerTurns::find($id);
        if ($pendingTurn) {
            $pendingTurn->deny = true;
            //$pendingTurn->state = 2;
            $pendingTurn->save();
            flash_notification(message: 'Turno eliminado.', type: 'success');
            return redirect()->route('owner-turns');
        }

        flash_notification('Solicitud no encontrada.', 'danger');
        return redirect()->route('owner-turns');
    }
    function createTurn(Request $request)
    {
        //Sdd(substr($request->hour,0,2));
        $hour = substr($request->hour,0,2);
        // Crear el timestamp a partir de la entrada del request
        $timestamp = Carbon::createFromFormat('Y-m-d H', "$request->day $hour");
        $existingTurnOnline = Turn::where('id_field', $request->field)
            ->where('day', $timestamp)
            ->first();

        if ($existingTurnOnline) {
            // Si existe un turno, puedes devolver un mensaje de error
            $user = User::findOrFail($existingTurnOnline->id_player);

            //dd($user);
            flash_notification('Ya existe un turno para esta fecha y hora. Pedido por ' . $user->name . ' ' . $user->surname, type: 'info');
            return redirect()->back();
        }

        // Verificar si ya existe un turno en esa fecha y hora
        $existingTurn = OwnerTurns::where('id_field', $request->field)
            ->where('day', $timestamp)
            ->first();

        if ($existingTurn) {
            // Si existe un turno, puedes devolver un mensaje de error
            flash_notification('Ya existe un turno para esta fecha y hora.', type: 'info');
            return redirect()->back();
        }

        // Crea un nuevo turno si no existe
        OwnerTurns::create([
            'id_owner' => auth()->user()->id,
            'id_field' => $request->field,
            'player' => $request->name,
            'day' => $timestamp,
        ]);

        flash_notification('Turno Cargado Correctamente');
        return redirect()->back();
    }

    public function dataPDF($startDate, $endDate, $state)
    {
        $total=0;
        $ownerId = auth()->user()->id;
        $ownerFields = Field::where('id_owner', $ownerId)->get();
    
        // Convertir las fechas de entrada a objetos Carbon para su manipulación
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
    
        // Inicializar la colección para los turnos pendientes
        $pendingTurns = collect();
    
        // Consultar todos los turnos pendientes para los campos del propietario en el rango de fechas
        $ownerTurns = OwnerTurns::with(['owner', 'field']) // Eager loading
            ->whereIn('id_field', $ownerFields->pluck('id')) // Filtrar por los campos del propietario
            //->where('deny', false) // Descomentar si es necesario
            ->whereBetween('day', [$startDate, $endDate]) // Filtrar por el rango de fechas
            ->orderBy('day') // Ordenar por el campo 'day'
            ->get();
    
        // Procesar los turnos del propietario
        foreach ($ownerTurns as $turn) {
            $pendingTurns->push((object) [
                'id' => $turn->id,
                'id_field' => $turn->id_field,
                'day' => $turn->day,
                'player' => $turn->player,
                'field_name' => $turn->field->name,
                'cost' => $turn->field->price,
                'state' => $turn->state,
                'deny' => $turn->deny,
                'source' => 'Offline', // Indicar origen
            ]);
            if ($turn->state == 1 && !$turn->deny) {
                $total=$total+$turn->field->price;
            }
        }
    
        // Consultar los turnos del canchero
        foreach ($ownerFields as $field) {
            $cancheroTurns = Turn::where('id_field', $field->id)
                ->whereBetween('day', [$startDate, $endDate]) // Aplicar filtro de fechas
                ->with('player') // Cargar el jugador que registró el turno
                ->get();
    
            // Agregar los turnos del canchero a la colección de turnos pendientes
            foreach ($cancheroTurns as $turn) {
                $pendingTurns->push((object) [
                    'id' => $turn->id,
                    'id_field' => $field->id,
                    'day' => $turn->day,
                    'player' => $turn->player->name . ' ' . $turn->player->surname,
                    'field_name' => $field->name,
                    'cost' => $field->price,
                    'state' => $turn->state,
                    'deny' => $turn->deny,
                    'source' => 'Online', // Indicar origen
                ]
            );
            if ($turn->state == 1 && !$turn->deny) {
                $total=$total+$field->price;
            }
            }
        }
    
        // Filtrar por estado si se seleccionó algo distinto de "todos"
        if ($state !== 'all') {
            $pendingTurns = $pendingTurns->filter(function ($turn) use ($state) {
                return $turn->state == $state;
            });
        }
    
    
        return [$pendingTurns, $total];
    }
    
    
    public function exportTurnsToPDF(Request $request)
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');
        $state = $request->query('state');
        $fieldId = $request->query('field');

        $filteredTurns = $this->dataPDF($startDate, $endDate, $state);
        $pendingTurnsRaw = $filteredTurns[0]->sortBy('day');
        
        // Inicializar el total en cero
        $total = 0;
    
        // Filtrar los turnos y sumar los costos solo si el estado es 1
        $pendingTurns = $pendingTurnsRaw->filter(function ($pending) use ($fieldId, &$total) {
            $isFieldMatch = !$fieldId || $pending->id_field == $fieldId;
            if ($isFieldMatch && $pending->state == 1) {
                $total += $pending->cost; // Sumar al total si el estado es 1
            }
            return $isFieldMatch;
        });
    
        $pdf = \PDF::loadView('turns.export-pdf', compact('pendingTurns', 'startDate', 'endDate', 'state', 'total', 'fieldId'));
        return $pdf->download('turnos_cargados.pdf');
    }
    

    
      

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OwnerTurns $ownerTurns)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OwnerTurns $ownerTurns)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OwnerTurns $ownerTurns)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OwnerTurns $ownerTurns)
    {
        //
    }
}
