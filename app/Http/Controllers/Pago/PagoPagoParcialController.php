<?php

namespace App\Http\Controllers\Pago;

use App\Pago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class PagoPagoParcialController extends ApiController
{
   public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:pago');
    }

    public function index(Pago $pago)
    {
        $pagos = $pago->pagos_parciales;
        return $this->showAll($pagos);
    }
}
