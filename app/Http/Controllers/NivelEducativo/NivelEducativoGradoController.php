<?php

namespace App\Http\Controllers\NivelEducativo;

use App\Ciclo;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\NivelEducativo;
use Illuminate\Http\Request;

class NivelEducativoGradoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index(NivelEducativo $niveles_educativo)
    {
        $grados = $niveles_educativo->grados()->with('grado')->get();
        return $this->showAll($grados);
    }

    public function getByCiclo($ciclo_id)
    {
        $cuotas_niveles = NivelEducativo::with('grados.grado','grados.cuotas')->get();

        $cuotas = collect();
        foreach ($cuotas_niveles as $key => $value) {
        	# code...
        }

        return $this->showAll($filtered);
    }

   
}
