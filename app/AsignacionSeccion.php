<?php

namespace App;

use App\Seccion;
use App\Inscripcion;
use Illuminate\Database\Eloquent\Model;

class AsignacionSeccion extends Model
{
	protected $table = 'asignacion_sessions';

    protected $fillable = [
		'inscripcion_id',
        'seccion_id'
	];

	public function inscripcion()
	{
		return $this->belongsTo(Inscripcion::class);
	}

	public function seccion()
	{
		return $this->belongsTo(Seccion::class);
	}
}
