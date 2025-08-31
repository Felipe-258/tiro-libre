<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Rating;
use App\Models\Turn;

class Field extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_owner',
        'name',
        'capacity',
        'coordinates',
        'price',
        'type',
        'description',
        'start',
        'end',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_owner');
    }

    public function rating()
    {
        return $this->hasmany(Rating::class);
    }

    public function turn()
    {
        return $this->hasmany(Turn::class);
    }

    public function photos()
    {
        return $this->hasMany(FieldPhoto::class);
    }

    public function ownerTurns()
    {
        return $this->hasMany(OwnerTurns::class);
    }
}

