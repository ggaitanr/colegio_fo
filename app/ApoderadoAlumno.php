<?php

namespace App;

use App\Alumno;
use App\Apoderado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApoderadoAlumno extends Model
{
    protected $table = 'apoderados_alumnos';
    
    protected $fillable = [
		'alumno_id',
        'apoderado_id',
        'responsable',
        'tipo_apoderado'
	];

	public function alumno()
	{
		return $this->belongsTo(Alumno::class);
	}

	public function apoderado()
	{
		return $this->belongsTo(Apoderado::class);
	}
}
