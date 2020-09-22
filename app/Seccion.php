<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seccion extends Model
{
    use SoftDeletes;

    protected $table = 'secciones';

    protected $fillable= [
    	'seccion'
    ];
}
