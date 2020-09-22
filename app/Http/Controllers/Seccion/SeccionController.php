<?php

namespace App\Http\Controllers\Seccion;

use App\Seccion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SeccionController extends ApiController
{
     public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:seccion')->except(['index']);
    }

    public function index()
    {
        $seccion = Seccion::all()->sortBy('id');

        return $this->showAll($seccion);
    }

    public function store(Request $request)
    {
        $reglas = [
            'seccion' => 'required|string'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $seccion = Seccion::create($data);

        return $this->showOne($seccion,201);
    }

    public function show(Seccion $seccione)
    {
        return $this->showOne($seccion);
    }

    public function update(Request $request, Seccion $seccione)
    {
        $reglas = [
            'seccion' => 'required|string|unique:secciones,seccion,' . $seccione->id,
        ];

        $this->validate($request, $reglas);

        $seccione->seccion = $request->seccion;

         if (!$seccione->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $seccione->save();
        return $this->showOne($seccione);
    }

    public function destroy(Seccion $seccione)
    {
        $seccione->delete();

        return $this->showOne($seccione);
    }
}
