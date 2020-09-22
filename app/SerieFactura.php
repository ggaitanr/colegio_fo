<?php

namespace App;

use App\Pago;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerieFactura extends Model
{
    protected $table = 'serie_facturas';
    protected $fillable = [
        'serie',
        'actual',
        'no_facturas',
        'no_inicial',
        'no_actual'
    ];

    public function pagos()
    {
    	return $this->hasMany(Pago::class);
    }
}
