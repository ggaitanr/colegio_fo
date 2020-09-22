<?php

namespace App\Http\Controllers\GradoNivelEducativo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class GradoNivelEducativoInscripcionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(GradoNivelEducativo $gradoNivelEducativo)
    {
        $grado_inscripciones = $gradoNivelEducativo->inscripciones()->with('alumno')->get();
        return $this->showAll($grado_inscripciones);
    }
}
