<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuRol extends Model
{
    protected $table = 'menu_rols';
    protected $fillable = ['rol_id','menu_id'];
}
