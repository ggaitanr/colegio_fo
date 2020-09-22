<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grado extends Model
{
    use SoftDeletes;

    protected $table = 'grados';

    protected $fillable= [
    	'nombre'
    ];
}
