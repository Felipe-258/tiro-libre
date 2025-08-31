<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Field;

class FieldPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['field_id', 'photo_path'];

// En el modelo FieldPhoto
public function field()
{
    return $this->belongsTo(Field::class);
}

}
