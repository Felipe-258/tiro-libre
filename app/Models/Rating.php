<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Field;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_player',
        'id_field',
        'rating',
        'created_at',
        'updated_at',
    ];
    public function playerR()
    {
        return $this->belongsTo(User::class);
    }

    public function fieldR()
    {
        return $this->belongsTo(Field::class);
    }
}
