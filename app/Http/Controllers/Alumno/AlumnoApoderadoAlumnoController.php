<?php

namespace App\Http\Controllers\Alumno;

use App\Alumno;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AlumnoApoderadoAlumnoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
   
    public function index(Alumno $alumno)
    {
        $apoderados = $alumno->apoderados()->with('apoderado.telefonos')->get();
        return $this->showAll($apoderados);
    }
}
