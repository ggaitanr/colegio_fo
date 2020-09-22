<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Menu;

class Rol extends Model
{
    protected $table = 'rols';
    protected $fillable= [
    	'rol'
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class,'menu_rols', 'rol_id', 'menu_id');
    }
}
