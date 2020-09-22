<?php

namespace App\Http\Controllers\Apoderado;

use App\Apoderado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ApoderadoTelefonoApoderadoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
   
    public function index(Apoderado $apoderado)
    {
        $telefonos = $apoderado->telefonos;
        return $this->showAll($telefonos);
    }
}
