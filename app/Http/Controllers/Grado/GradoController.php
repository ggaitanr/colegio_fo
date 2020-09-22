<?php

namespace App\Http\Controllers\Grado;

use App\Grado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class GradoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:grado')->except(['index']);
    }

    public function index()
    {
        $grados = Grado::all()->sortBy('id');

        return $this->showAll($grados);
    }

    public function store(Request $request)
    {
        $reglas = [
            'nombre' => 'required|string'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $grado = Grado::create($data);

        return $this->showOne($grado,201);
    }

    public function show(Grado $grado)
    {
        return $this->showOne($grado);
    }

    public function update(Request $request, Grado $grado)
    {
        $reglas = [
            'nombre' => 'required|string|unique:grados,nombre,' . $grado->id,
        ];

        $this->validate($request, $reglas);

        $grado->nombre = $request->nombre;

         if (!$grado->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $grado->save();
        return $this->showOne($grado);
    }

    public function destroy(Grado $grado)
    {
        $grado->delete();

        return $this->showOne($grado);
    }
}
