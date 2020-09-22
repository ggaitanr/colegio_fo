<?php

namespace App\Http\Controllers\ConceptoPago;

use App\ConceptoPago;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ConceptoPagoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:conceptopago')->except(['index']);
    }


    public function index()
    {
        $concepto_pago = ConceptoPago::with('cuotas.ciclo')->get()->sortBy('id');
        return $this->showAll($concepto_pago);
    }

    public function store(Request $request)
    {
        $reglas = [
            'nombre' => 'required|string',
            
        ];
        $this->validate($request,$reglas);
        $data = $request->all();
        $conceptoPagos = ConceptoPago::create($data);
        return $this->showOne($conceptoPagos,201);
    }

    public function show(ConceptoPago $concepto_pago)
    {
        return $this->showOne($concepto_pago);
    }

    public function update(Request $request, ConceptoPago $concepto_pago)
    {
        $reglas = ['nombre'=> 'required'];

        $this->validate($request,$reglas);

        $concepto_pago->nombre = $request->nombre;
        $concepto_pago->obligatorio = $request->obligatorio;
        $concepto_pago->forma_pago = $request->forma_pago;
        $concepto_pago->is_parcial = $request->is_parcial;
        $concepto_pago->mora = $request->mora;


        if(!$concepto_pago->isDirty()) {
            return $this->errorResponse("Se debe especificar al menos un valor diferente para actualizar",422);
        }
        $concepto_pago->save();
        return $this->showOne($concepto_pago);
    }

    public function destroy(ConceptoPago $concepto_pago)
    {
        if(count($concepto_pago->cuotas)){
            return $this->errorResponse("no se puede eliminar concepto porque ya tiene cuotas asignadas",422);
        }

        $concepto_pago->delete();
        return $this->showOne($ciclo);
    }
}
