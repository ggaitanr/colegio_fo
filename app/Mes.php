<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mes extends Model
{
    use SoftDeletes;
    protected $table = 'meses';
    protected $fillable = [
        'mes'
    ];
}
