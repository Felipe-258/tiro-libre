<?php

namespace App\Http\Controllers;

use App\Models\room;
use App\Models\PlayerRoom;
use App\Http\Requests\StoreroomRequest;
use App\Http\Requests\UpdateroomRequest;
use App\Models\Turn;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $rooms = Room::with(['turn.field'])->get();
        return view('room.room', ['rooms' => $rooms]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $id = auth()->user()->id;

        /* $turns = Turn::where('id_player', $id)->with('field')->get(); */
        $turns = Turn::with('field')
            ->where('id_player', $id)
            ->where('day', '>', Carbon::now())
            ->get();

        return view('room.create', compact('turns'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id_player = auth()->user()->id;

        $validated = $request->validate([
            'id_turn' => 'required|integer|exists:turns,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'max' => 'required|integer||min:0',
            'description' => 'required|string',
        ], [
            'quantity.min' => 'La cantidad no puede ser menor que 0.',
            'max.lte' => 'La cantidad no puede ser menor que 0.',
        ]);

        
        // Crear una nueva sala
        $room = Room::create([
            'id_turn' => $validated['id_turn'],
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'max' => $validated['max'],
            'description' => $validated['description'],
        ]);

        PlayerRoom::create([
            'id_room' => $room->id,
            'id_player' => $id_player,
        ]);

        flash_notification('La sala ha sido creada con éxito');
        // Redirigir a la vista deseada con un mensaje de éxito
        return redirect()->route('room'); // Asegúrate de que esta ruta exista
        //->with('success', 'Sala creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Obtener la sala por su ID, incluyendo los jugadores asociados
        $room = Room::with('playerRoom.playerPR')->findOrFail($id);

        // Pasar los datos a la vista
        return view('room.details', [
            'room' => $room,
            'players' => $room->playerRoom->map->playerPR
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateroomRequest $request, room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encuentra la sala por ID
        $room = Room::findOrFail($id);

        // Verifica que el usuario autenticado sea el creador del turno asociado a la sala
        if (auth()->id() === $room->turn->id_player) {
            // Elimina la sala

            $room->delete();

            // Redirige con un mensaje de éxito
            flash_notification('La sala ha sido eliminada con éxito');
            return redirect()->route('room');
        }

        // Redirige con un mensaje de error si el usuario no es el creador
        flash_notification('No tienes permiso para eliminar esta sala');
        return redirect()->route('room');
    }
}