<?php

namespace App\Http\Controllers\Apoderado;

use App\Apoderado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ApoderadoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:alumno')->except(['index']);
    }

    public function index()
    {
        $Apoderados = Apoderado::all();
        return $this->showAll($Apoderados);
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
            'primer_nombre' => 'required|string'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $apoderado = Apoderado::create($data);

        return $this->showOne($apoderado,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Apoderado  $Apoderado
     * @return \Illuminate\Http\Response
     */
    public function show($cui)
    {
        $apoderado = Apoderado::where('cui',$cui)->with('telefonos')->first();

        if($apoderado === null) return $this->errorResponse('no existe ningun apoderado con el cui '.$cui, 404);
        return $this->showOne($apoderado);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Apoderado  $Apoderado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apoderado $apoderado)
    {
        $reglas = [
            'primer_nombre' => 'required|string',
        ];

        $this->validate($request, $reglas);

        $apoderado->primer_nombre = $request->primer_nombre;

         if (!$apoderado->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $Apoderado->save();
        return $this->showOne($apoderado);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Apoderado  $Apoderado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apoderado $apoderado)
    {
        $Apoderado->delete();

        return $this->showOne($Apoderado);
    }
}
