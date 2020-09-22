<?php

namespace App;

use App\Municipio;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'institucion';
    protected $fillable= [
    	'definicion',
        'nombre',
    	'representante_legal',
    	'cui',
    	'logo',
    	'municipio_id',
    	'profesion',
    	'extendido_en',
    	'calidad_de',
    	'estado_civil',
    	'nacionalidad',
        'nit'
    ];

    public function municipio()
    {
    	return $this->belongsTo(Municipio::class);
    }
}
