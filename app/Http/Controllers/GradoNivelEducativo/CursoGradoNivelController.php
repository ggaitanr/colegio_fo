<?php

namespace App\Http\Controllers\GradoNivelEducativo;

use App\CursoGradNivEd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class CursoGradoNivelController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index()
    {
        $curso_niveles = CursoGradNivEd::all();

        return $this->showAll($curso_niveles);
    }


    /**
     */
    public function store(Request $request)
    {
        $reglas = [
            'curso_id' => 'required|integer',
            'grado_nivel_educativo_id' => 'required|integer'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $curso_nivel = CursoGradNivEd::create($data);

        return $this->showOne($curso_nivel,201);
    }

    /**
     */
    public function show(Curso $curso)
    {
        
    }

    /**
     */
    public function update(Request $request, Curso $curso)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy(CursoGradNivEd $gradoNivelEducativosCurso)
    {
        $gradoNivelEducativosCurso->delete();

        return $this->showOne($gradoNivelEducativosCurso);
    }
}
