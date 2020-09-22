<?php

namespace App;

use App\Inscripcion;
use App\ApoderadoAlumno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumno extends Model
{
	use SoftDeletes;

    protected $table = 'alumnos';

	protected $fillable = [
		'codigo',
        'primer_nombre',
        'segundo_nombre',
        'tercer_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nac',
        'genero',
        'telefono',
        'email',
        'direccion',
        'enfermedades',
        'alergias',
        'foto'
	];

	public function apoderados()
	{
		return $this->hasMany(ApoderadoAlumno::class);
	}

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function responsable()
    {
        return $this->hasOne(ApoderadoAlumno::class)->where('responsable',true);
    }
}
