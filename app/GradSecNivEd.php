<?php

namespace App;

use App\Seccion;
use App\GradoNivelEducativo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradSecNivEd extends Model
{

    protected $table = 'grado_sec_niv_edu';
    protected $fillable= [
    	'seccion_id',
    	'grado_nivel_educativo_id'
    ];

    public function seccion()
    {
    	return $this->belongsTo(Seccion::class,'seccion_id');
    }

    public function grado_nivel_educativo()
    {
    	return $this->belongsTo(GradoNivelEducativo::class,'grado_nivel_educativo_id');
    }
}
