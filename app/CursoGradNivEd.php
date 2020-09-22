<?php

namespace App;
use App\Curso;
use App\GradoNivelEducativo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CursoGradNivEd extends Model
{
    use SoftDeletes;

    protected $table = 'curso_grad_niv_edu';
    protected $fillable= [
    	'curso_id',
    	'grado_nivel_educativo_id'
    ];

    public function curso()
    {
    	return $this->belongsTo(Curso::class,'curso_id');
    }

    public function grado_nivel_educativo()
    {
    	return $this->belongsTo(GradoNivelEducativo::class,'grado_nivel_educativo_id');
    }
}
