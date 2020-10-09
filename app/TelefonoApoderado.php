<?php

namespace App;

use App\Apoderado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelefonoApoderado extends Model
{
    protected $table = 'telefono_apoderados';
    
    protected $fillable = [
        'apoderado_id',
        'tipo_telefono',
        'telefono'
	];

	public function apoderado()
	{
		return $this->belongsTo(Apoderado::class);
	}
}
