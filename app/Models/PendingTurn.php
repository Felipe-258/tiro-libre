<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* use app\Models\Field; */
use App\Models\User;

class PendingTurn extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_player',
        'id_field',
        'day',
        'duration',
        'approved',
    ];

    // Relationship with the player (User)
    public function player()
    {
        return $this->belongsTo(User::class, 'id_player');
    }

    // Relationship with the field (Field)
    public function field()
    {
        return $this->belongsTo(Field::class, 'id_field');
    }
}
