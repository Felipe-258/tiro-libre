<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Field;
use App\Models\Room;


class Turn extends Model
{
    use HasFactory;

    protected $fillable = ['id','id_player', 'id_field', 'day', 'phone', 'state'];

    public function player()
    {
        return $this->belongsTo(User::class, 'id_player');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'id_field');
    }

    public function room()
    {
        return $this->hasmany(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_player');
    }

}