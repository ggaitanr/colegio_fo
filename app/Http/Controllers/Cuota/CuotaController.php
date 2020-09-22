<?php

namespace App\Http\Controllers\Cuota;

use App\ConceptoPago;
use App\Cuota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CuotaController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:asignarcuota')->except(['index']);
    }

    public function index(ConceptoPago $concepto_pago){
        $cuota = $concepto_pago->cuotas()->with('concepto_pago','grado_nivel_educativo','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();
        return $this->showAll($cuota);
    }


    public function store(Request $request)
    {
        $reglas = [
            'forms' => 'required'
        ];

        $this->validate($request, $reglas);

        foreach ($request->forms as $key => $form) {
            $cuota = Cuota::create($form); 
        }
              
        return $this->showOne($cuota,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cuota $cuota)
    {
        $reglas = [
            'cuota' => 'required',
        ];

        $this->validate($request, $reglas);

        $cuota->cuota = $request->cuota;

        if (!$cuota->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $cuota->save();
        
        return $this->showOne($cuota);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
