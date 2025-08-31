<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Room;

class PlayerRoom extends Model
{
    use HasFactory;
    public function playerPR()
    {
        return $this->belongsTo(User::class, 'id_player');
    }
    
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_room');
    }


    protected $table = 'player_rooms';
    protected $fillable = ['id_room', 'id_player'];

}
