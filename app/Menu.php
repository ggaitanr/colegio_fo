<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rol;

class Menu extends Model
{
    protected $table = 'menus';
    protected $fillableee = ['text','path','icon','name','hide','father'];

    public function rols()
    {
        return $this->belongsToMany(Rol::class);        
    }
}
