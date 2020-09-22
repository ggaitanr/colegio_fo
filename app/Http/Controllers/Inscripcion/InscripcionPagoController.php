<?php

namespace App\Http\Controllers\Inscripcion;

use App\Inscripcion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class InscripcionPagoController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
    }

    public function index(Inscripcion $inscripcione)
    {
        $pagos = $inscripcione->pagos()->with('cuota.concepto_pago','cuota.ciclo','pagos_parciales','pagos_meses','serie')->get();
        return $this->showAll($pagos);
    }
}
