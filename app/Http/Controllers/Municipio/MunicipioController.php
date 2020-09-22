<?php

namespace App\Http\Controllers\Municipio;

use App\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MunicipioController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $municipios = Municipio::with('departamento')->get();

        return $this->showAll($municipios);
    }

    public function store(Request $request)
    {
        $reglas = [
            'nombre' => 'required|string',
            'departamento_id' => 'required|integer'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $municipio = Municipio::create($data);

        return $this->showOne($municipio,201);
    }

    public function show(Municipio $municipio)
    {
        return $this->showOne($municipio);
    }

    public function update(Request $request, municipio $municipio)
    {
        $reglas = [
            'nombre' => 'required',
            'departamento_id' => 'required|integer'
        ];

        $this->validate($request, $reglas);

        $municipio->nombre = $request->nombre;
        $municipio->departamento_id = $request->departamento_id;

         if (!$municipio->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $municipio->save();
        return $this->showOne($municipio);
    }

    public function destroy(Municipio $municipio)
    {
        $municipio->delete();
        return $this->showOne($municipio);
    }
}