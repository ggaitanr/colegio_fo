<?php

namespace App;
use App\Cuota;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConceptoPago extends Model
{
    use SoftDeletes;
    protected $table = 'concepto_pagos';
    protected $fillable = [
        'nombre',
        'obligatorio',
        'is_parcial',
        'forma_pago'
    ];
    
    public function cuotas()
    {
    	return $this->hasMany(Cuota::class,'concepto_pago_id');
    }
}
