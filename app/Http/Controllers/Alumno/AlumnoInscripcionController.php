<?php

namespace App\Http\Controllers\Alumno;

use App\Alumno;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AlumnoInscripcionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
   
    public function index(Alumno $alumno)
    {
        $inscripciones = $alumno->inscripciones()->with('ciclo','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();

        return $this->showAll($inscripciones);
    }
}
