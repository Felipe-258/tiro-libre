<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Field;
use app\Models\Rating;
use app\Models\Turn;
use app\Models\PendingTurn;
use app\Models\Player_room;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasRoles;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    /* protected $table = 'users'; */
    protected $fillable = ['name','surname', 'email', 'password', 'phone'];

    use HasFactory;
    public function field()
    {
        return $this->hasMany(Field::class, 'id_owner');
    }

    public function rating()
    {
        return $this->hasmany(Rating::class);
    }

    public function turn()
    {
        return $this->hasmany(Turn::class);
    }

    public function player_room()
    {
        return $this->hasmany(PlayerRoom::class);
    }

    public function pendingTurns()
    {
        return $this->hasMany(PendingTurn::class);
    }

    public function ownerTurns()
    {
        return $this->hasMany(OwnerTurns::class);
    }

    
}
