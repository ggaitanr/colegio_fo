<?php

namespace App;

use Illuminate\Database\Eloquent\Model; //import model
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $table = 'cursos';
    protected $fillable= [
    	'nombre'
    ];

}
