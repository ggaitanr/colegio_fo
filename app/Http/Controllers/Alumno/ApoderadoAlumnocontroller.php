<?php

namespace App\Http\Controllers\Alumno;

use App\Alumno;
use App\Apoderado;
use App\ApoderadoAlumno;
use App\TelefonoApoderado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class ApoderadoAlumnoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('scope:alumno')->except(['index']);
    }
   
    public function index(Alumno $alumno)
    {
        $apoderados = $alumno->apoderados()->with('apoderado.telefonos')->get();
        return $this->showAll($apoderados);
    }

    public function store(Request $request)
    {
        $reglas = [
            'cui' => 'required|string:unique',
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'fecha_nac' => 'required',
            'direccion' => 'required|string',
            'telefono' => 'required',
            'municipio_id'=> 'required',
            'estado_civil' => 'required',
            'nacionalidad' => 'required'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();
            $data = $request->all();
            $apoderado_id = $request->apoderado_id;
            if(is_null($apoderado_id)){

                $apoderado_exists = Apoderado::where('cui',$request->cui)->withTrashed()->first();

                if(!is_null($apoderado_exists)) return $this->errorResponse('cui de apoderado ya fue asignado, si el cui es de un representante existente, haga clic la opcion validar del formulario',422);

                $apoderado = Apoderado::create($data);
                $apoderado_id = $apoderado->id;  

                TelefonoApoderado::create([
                    'tipo_telefono' => 'P',
                    'telefono' => $request->telefono,
                    'apoderado_id' => $apoderado_id
                ]);
            }

            if($request->responsable){//quitar responsabilidad a apoderado anterior
                $a_reponsable = ApoderadoAlumno::where('alumno_id',$request->alumno_id)->where('responsable',true)->first();
                $a_reponsable->responsable = false;
                $a_reponsable->save();
            }

            $apoderado_alumno = ApoderadoAlumno::create([
                'apoderado_id' => $apoderado_id,
                'alumno_id' => $request->alumno_id,
                'responsable' => $request->responsable,
                'tipo_apoderado' => $request->tipo_apoderado
            ]);

        DB::commit();

        return $this->showOne($apoderado_alumno,201);
    }

    public function update(Request $request, ApoderadoAlumno $apoderado_alumno)
    {
         $reglas = [
            'cui' => 'required|string|unique:apoderados,cui,'.$request->apoderado_id, 
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'fecha_nac' => 'required',
            'direccion' => 'required|string',
            'telefono' => 'required',
            'estado_civil' => 'required',
            'nacionalidad' => 'required'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();

            $apoderado = Apoderado::find($request->apoderado_id);

            $apoderado->primer_nombre = $request->primer_nombre;
            $apoderado->segundo_nombre = $request->segundo_nombre; 
            $apoderado->primer_apellido = $request->primer_apellido; 
            $apoderado->segundo_apellido = $request->segundo_apellido; 
            $apoderado->fecha_nac = $request->fecha_nac;   
            $apoderado->email = $request->email;
            $apoderado->direccion = $request->direccion;
            $apoderado->ocupacion = $request->ocupacion;
            $apoderado->estado_civil = $request->estado_civil;
            $apoderado->nacionalidad = $request->nacionalidad;
            $apoderado->nit = $request->nit;
            $apoderado->save();

            if($request->responsable){//quitar responsabilidad a apoderado anterior
                $a_responsable = ApoderadoAlumno::where('alumno_id',$request->alumno_id)->where('responsable',true)->first();

                if(!is_null($a_responsable)){
                    $a_responsable->responsable = false;
                    $a_responsable->save();
                }
            }

            $apoderado_alumno->tipo_apoderado = $request->tipo_apoderado;
            $apoderado_alumno->responsable = $request->responsable;
            $apoderado_alumno->save();

        DB::commit();

        return $this->showOne($apoderado_alumno,201);
    }

    public function destroy(ApoderadoAlumno $apoderado_alumno)
    {
        if($apoderado_alumno->responsable) return $this->errorResponse('no se puede remover apoderado '.$apoderado_alumno->cui.' porque es el responsable de este alumno');

        $apoderado_alumno->delete();
        return $this->showOne($apoderado_alumno,201);
    }
}
