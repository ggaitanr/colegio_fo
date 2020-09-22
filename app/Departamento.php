<?php

namespace App;
use App\Municipio;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable= [
    	'nombre'
    ];

    public function municipios()
    {
    	return $this->hasMany(Municipio::class);
    }
}
