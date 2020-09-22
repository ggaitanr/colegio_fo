<?php

namespace App\Http\Controllers\GradoNivelEducativo;

use App\GradoNivelEducativo;
use App\GradSecNivEd;
use App\CursoGradNivEd;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class GradoNivelEducativoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:niveleducativo')->except(['index']);
    }

    public function index()
    {
        $grado_nivs = GradoNivelEducativo::with('grado','nivelEducativo')->get()->sortBy('id');
        return $this->showAll($grado_nivs);
    }

    public function store(Request $request)
    {
        $reglas = [
            'nivel_educativo_id' => 'required',
            'grado_id' => 'required'
        ];
        $this->validate($request, $reglas);

        DB::beginTransaction();
            $data = $request->all();
            $grado_nivel_educativo = GradoNivelEducativo::create($data);

            //insertar cursos
            if(count($request->cursos)){
                foreach ($request->cursos as $curso) {
                    $grado_niv_ed_curso = new CursoGradNivEd();
                    $grado_niv_ed_curso->grado_nivel_educativo_id = $grado_nivel_educativo->id;
                    $grado_niv_ed_curso->curso_id = $curso;
                    $grado_niv_ed_curso->save();
                }
            }else{
                return $this->errorResponse("asigne al menos un curso",422);
            }

            //insertar secciones a grado
            if(count($request->secciones)){
                foreach ($request->secciones as $seccion) {
                    $seccion_create = new GradSecNivEd();
                    $seccion_create->grado_nivel_educativo_id = $grado_nivel_educativo->id;
                    $seccion_create->seccion_id = $seccion;

                    $seccion_create->save();
                }
            }else{
                return $this->errorResponse("asigne al menos una seccion",422);
            }
        DB::commit();

        return $this->showOne($grado_nivel_educativo,201);
    }

    public function show(GradoNivelEducativo $gradoNivelEducativo)
    {
        $gradoNivelEducativo = GradoNivelEducativo::where('id',$gradoNivelEducativo->id)->with('secciones.seccion', 'cursos.curso','grado','nivelEducativo')->firstOrFail();
        return $this->showOne($gradoNivelEducativo);
    }

    public function update(Request $request, GradoNivelEducativo $gradoNivelEducativo)
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

    public function destroy(GradoNivelEducativo $gradoNivelEducativo)
    {
        $gradoNivelEducativo->delete();

        return $this->showOne($gradoNivelEducativo);
    }
}
