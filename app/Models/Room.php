<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Turn;
use App\Models\PlayerRoom;


class Room extends Model
{
    use HasFactory;
    public function turnR()
    {
        return $this->belongsTo(Turn::class);    
    }

    public function playerRoom()
    {
        return $this->hasMany(PlayerRoom::class, 'id_room');
    }

    public function turn()
    {
        return $this->belongsTo(Turn::class, 'id_turn');
    }
    
    protected $fillable = [
        'id_turn',
        'name',
        'quantity',
        'max',
        'description',
    ];


}
