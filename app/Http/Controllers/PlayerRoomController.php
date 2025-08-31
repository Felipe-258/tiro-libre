<?php

namespace App\Http\Controllers;

use App\Models\PlayerRoom;
use App\Models\Room;
use App\Http\Requests\StorePlayerRoomRequest;
use App\Http\Requests\UpdatePlayerRoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerRoomController extends Controller
{

    public function joinRoom(Request $request, $roomId){

        $playerId = Auth::id();
        $room = Room::where('id', $roomId)->first();
        /* dd($room); */

        $existingPlayer = PlayerRoom::where('id_room', $roomId)
                                    ->where('id_player', $playerId)
                                    ->first();

        if ($existingPlayer) {
            flash_notification('Ya te encuentras en esta sala.', 'info');
            return redirect()->route('room');
        }

        if ($room->quantity == $room->max) {
            flash_notification('Esta sala esta llena', 'info');
            return redirect()->route('room');
        } else {
            PlayerRoom::create([
                'id_room' => $roomId,
                'id_player' => $playerId,
            ]);

            $room->quantity = $room->quantity + 1;
            $room->save();

            flash_notification('Te has unido a la sala con éxito.');
            return redirect()->route('room');
        }
    }

    public function leaveRoom($roomId){

        $playerId = Auth::id();
        $room = Room::where('id', $roomId)->first();
        PlayerRoom::where('id_room', $roomId)->where('id_player', $playerId)->delete();

        $room->quantity = $room->quantity - 1;
        $room->save();

        flash_notification('Has abandonado la sala.');
            return redirect()->route('room');
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
    public function store(StorePlayerRoomRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerRoom $PlayerRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerRoom $PlayerRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlayerRoomRequest $request, PlayerRoom $PlayerRoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerRoom $PlayerRoom)
    {
        //
    }
}
