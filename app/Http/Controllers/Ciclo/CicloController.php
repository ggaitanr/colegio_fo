<?php

namespace App\Http\Controllers\Ciclo;

use App\Alumno;
use App\Ciclo;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CicloController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:ciclo')->except(['index']);
    }
    public function index()
    {
        $ciclo = Ciclo::all();
        return $this->showAll($ciclo);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'ciclo' => 'required', 
            'inicio' => 'required',
            'fin' => 'required'
        ];
        $this->validate($request, $reglas);

        DB::beginTransaction();

            $data = $request->all();
            $data['actual'] = true;
            $activos = Ciclo::where('actual',true)->get();

            foreach ($activos as $activo) {
                $activo->actual = false;
                $activo->save();
            }

            $ciclo = Ciclo::create($data);
        DB::commit();

        return $this->showOne($ciclo, 201);
    }

    public function show(Ciclo $ciclo)
    { 
        $ciclo = Ciclo::where('id',$ciclo->id)->with('cuotas.concepto_pago','cuotas.grado_nivel_educativo.grado','cuotas.grado_nivel_educativo.nivelEducativo')->firstOrFail();
        return $this->showOne($ciclo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ciclo  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ciclo $ciclo)
    {
        $reglas = [
            'ciclo' => 'required|numeric',
        ];

        $this->validate($request, $reglas);

        DB::beginTransaction();
        $ciclo->ciclo = $request->ciclo;
        $ciclo->inicio = $request->inicio;
        $ciclo->fin = $request->fin;
        $ciclo->actual = $request->actual;

        if($request->actual){
            $activos = Ciclo::where('actual',true)->get();
            foreach ($activos as $activo) {
                $activo->actual = false;
                $activo->save();
            }
        }

        if (!$ciclo->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $ciclo->save();
        DB::commit();
        return $this->showOne($ciclo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ciclo  $ciclo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ciclo $ciclo)
    {
        if($ciclo->inscripciones){
            return $this->errorResponse('No se puede eliminar ciclo escolar porque ya tiene inscripciones', 422);
        }

        if($ciclo->pagos){
            return $this->errorResponse('No se puede eliminar ciclo escolar porque ya tiene cuotas asignadas', 422);
        }

        if($ciclo->actual){
            $menor = $ciclo->ciclo -1;
            $ciclo_menor = Ciclo::where('ciclo',$menor)->first();
            if(!is_null($ciclo_menor)){
                $ciclo_menor->actual = true;
                $ciclo_menor->save();
            }
        }
        $ciclo->delete();
        return $this->showOne($ciclo);
    }

    public function getDataCiclo($id=0)
    {
        $id == 0 ? $ciclo = Ciclo::where('actual',true)->first() : $ciclo = Ciclo::find($id); 
        
        $total_alumnos = Alumno::all()->count();
        $total_inscripciones = count($ciclo->inscripciones);
        
        $pagos = $ciclo->inscripciones()->with('pagos')->get()->pluck('pagos')->collapse()->values();

        $total_pagos = $pagos->sum('total');
        $pago_cuotas = $ciclo->cuotas()->with('concepto_pago','pagos')->get();
        $total_cancelado = $pagos->sum('cancelado');
        $total_credito_cancelado = $pagos->where('is_credito',true)->sum('total_cancelado');
        $total_credito = $pagos->where('is_credito',true)->sum('total');
        $total_contado = $pagos->where('is_credito',false)->sum('total');

        $resumen = [
            'total_cancelado_credito' => $total_credito_cancelado,
            'total_credito' => $total_credito,
            'total_contado' => $total_contado,
            'total' => $total_pagos
        ];


        return response()->json([
            'total_alumnos' =>$total_alumnos,
            'total_inscripciones' => $total_inscripciones,
            'total_pagos' => $total_pagos,
            'pago_cuotas' => $pago_cuotas,
            'resumen' => $resumen,
            'ciclo' => $ciclo
        ]);
    }

    public function getCicloActual()
    {
        $ciclo = Ciclo::where('actual',true)->first();
        
        if(is_null($ciclo)){
            return $this->errorResponse('Ciclo actual aun no ah sido registrado', 422);
        }
        return $this->showOne($ciclo);
    }
}

