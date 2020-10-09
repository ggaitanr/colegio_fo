<?php

namespace App;

use App\Pago;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoParcial extends Model
{

    protected $table = 'pagos_parciales';
    protected $fillable= [
    	'pago_id',
    	'pago',
    	'fecha',
    	'anulado',
    	'motivo_anulado',
        'apoderado_id'
    ];

    public function pago_padre()
    {
        return $this->belongsTo(Pago::class,'pago_id');
    }
}
