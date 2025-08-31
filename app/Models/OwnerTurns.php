<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerTurns extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_owner',
        'id_field',
        'player',
        'day',
        'deny',
    ];

    // Relationship with the owner (User)
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_owner');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'id_field');
    }
}
