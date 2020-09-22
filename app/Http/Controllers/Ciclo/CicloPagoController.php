<?php

namespace App\Http\Controllers\Ciclo;

use App\Ciclo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CicloPagoController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:consultaciclo')->except(['index']);
    }

    public function index(Ciclo $ciclo)
    {
        $pagos = $ciclo->inscripciones()->with('pagos.cuota.concepto_pago','pagos.cuota.ciclo','pagos.pagos_parciales','pagos.pagos_meses.mes','pagos.serie','pagos.inscripcion.alumno','pagos.apoderado')->get()->pluck('pagos')->collapse()->values();

        return $this->showAll($pagos);
    }
}
