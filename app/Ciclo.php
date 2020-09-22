<?php

namespace App;
use App\Cuota;
use App\Inscripcion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciclo extends Model
{
    use SoftDeletes;
    protected $table = 'ciclos';
    protected $fillable = [
        'ciclo',
        'actual',
        'inicio',
        'fin'
    ];

    public function inscripciones()
    {
    	return $this->hasMany(Inscripcion::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }
}
