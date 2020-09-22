<?php

namespace App\Http\Controllers\GradoNivelEducativo;

use App\GradoNivelEducativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class GradoNivelEducativoCuotaController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(GradoNivelEducativo $gradoNivelEducativo)
    {
        $cuotas = $gradoNivelEducativo->cuotas()->with('concepto_pago','ciclo')->get();
        return $this->showAll($cuotas);
    }

     public function getByciclo($id,$ciclo_id)
    {
    	$grado = GradoNivelEducativo::find($id);
    	$cuotas = $grado->cuotas()->with('concepto_pago','ciclo')->where('ciclo_id',$ciclo_id)->get()->values();

        return $this->showAll($cuotas);
    }
}
