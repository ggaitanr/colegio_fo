<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    protected $table = 'secciones';

    protected $fillable= [
    	'seccion'
    ];
}
