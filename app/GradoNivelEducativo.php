<?php

namespace App;

use App\Cuota;
use App\Grado;
use App\Inscripcion;
use App\GradSecNivEd;
use App\CursoGradNivEd;
use App\NivelEducativo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradoNivelEducativo extends Model
{

    protected $table = 'grados_niveles_educativos';

    protected $fillable= [
    	'grado_id',
    	'nivel_educativo_id'
    ];

    public function grado()
    {
    	return $this->belongsTo(Grado::class,'grado_id');
    }

    public function nivelEducativo()
    {
    	return $this->belongsTo(NivelEducativo::class,'nivel_educativo_id');
    }

    public function secciones()
    {
        return $this->hasMany(GradSecNivEd::class,'grado_nivel_educativo_id');
    }

    public function cursos()
    {
        return $this->hasMany(CursoGradNivEd::class,'grado_nivel_educativo_id');
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class,'grado_nivel_educativo_id');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class,'grado_nivel_educativo_id');
    }
}
