<?php

namespace App\Http\Controllers\Curso;

use App\Curso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CursoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:curso')->except(['index']);
    }

    public function index()
    {
        $cursos = Curso::all(); #select * from cursos
        return $this->showAll($cursos);
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
            'nombre' => 'required|string'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $curso = Curso::create($data); #insert into curso('nombre')values('nombre del curso')

        return $this->showOne($curso,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function show(Curso $curso)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curso $curso)
    {
        $reglas = [
            'nombre' => 'required|string|unique:cursos,nombre,' . $curso->id,
        ];

        $this->validate($request, $reglas);

        $curso->nombre = $request->nombre;

         if (!$curso->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $curso->save();
        return $this->showOne($curso);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();

        return $this->showOne($curso);
    }
}
