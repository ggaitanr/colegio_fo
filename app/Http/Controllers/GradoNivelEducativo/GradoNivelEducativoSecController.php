<?php

namespace App\Http\Controllers\GradoNivelEducativo;

use App\GradSecNivEd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class GradoNivelEducativoSecController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $grad_nivel_secciones = GradSecNivEd::with('seccion','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();

        return $this->showAll($grad_nivel_secciones);
    }


    /**
     */
    public function store(Request $request)
    {
        $reglas = [
            'seccion_id' => 'required|integer',
            'grado_nivel_educativo_id' => 'required|integer'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $seccion_niv = GradSecNivEd::create($data);

        return $this->showOne($seccion_niv,201);
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
    public function destroy(GradSecNivEd $gradoNivelEducativosSeccione)
    {
        $gradoNivelEducativosSeccione->delete();

        return $this->showOne($gradoNivelEducativosSeccione);
    }
}
