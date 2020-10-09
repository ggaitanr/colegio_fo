<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mes extends Model
{
    protected $table = 'meses';
    protected $fillable = [
        'mes'
    ];
}
