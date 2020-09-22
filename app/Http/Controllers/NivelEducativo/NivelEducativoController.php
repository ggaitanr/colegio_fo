<?php

namespace App\Http\Controllers\NivelEducativo;

use App\NivelEducativo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class NivelEducativoController extends ApiController
{
     public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index()
    {
        $nivel_educativo = NivelEducativo::with('grados.grado')->get()->sortBy('id');

        return $this->showAll($nivel_educativo);
    }

    public function store(Request $request)
    {
        $reglas = [
            'nombre' => 'required|string',
            'resolucion' => 'required',
            'fecha' => 'required'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $nivel_educativo = NivelEducativo::create($data);

        return $this->showOne($nivel_educativo,201);
    }

    public function show(NivelEducativo $niveles_educativo)
    {
        return $this->showOne($niveles_educativo);
    }

    public function update(Request $request, NivelEducativo $niveles_educativo)
    {
        $reglas = [
            'nombre' => 'required|string|unique:niveles_educativos,nombre,' . $niveles_educativo->id,
            'resolucion' => 'required',
            'fecha' => 'required'
        ];

        $this->validate($request, $reglas);

        $niveles_educativo->nombre = $request->nombre;
        $niveles_educativo->resolucion = $request->resolucion;
        $niveles_educativo->fecha = $request->fecha;
        $niveles_educativo->es_carrera = $request->es_carrera;

         if (!$niveles_educativo->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $niveles_educativo->save();
        return $this->showOne($niveles_educativo);
    }

    public function destroy(NivelEducativo $niveles_educativo)
    {
        $grados =$niveles_educativo->grados;

        if(count($grados)){
            return $this->errorResponse('no se puede eliminar nivel educativo, porque ya tiene grados asignados', 422);
        }

        $niveles_educativo->delete();

        return $this->showOne($niveles_educativo);
    }
}
