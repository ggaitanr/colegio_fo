<?php

namespace App;

use App\GradoNivelEducativo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NivelEducativo extends Model
{
    use SoftDeletes;

    protected $table = 'niveles_educativos';

    protected $fillable= [
    	'nombre',
        'resolucion',
        'fecha',
    	'es_carrera'
    ];

    public function grados()
    {
    	return $this->hasMany(GradoNivelEducativo::class,'nivel_educativo_id');
    }
}
