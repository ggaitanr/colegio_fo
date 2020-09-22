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
use Illuminate\Support\Facades\Storage;

class AlumnoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:alumno')->only(['create','update','destroy']);
    }

    public function index()
    {
        $alumnos = Alumno::with('apoderados.alumno','responsable.apoderado')->orderBy('id')->get();
        return $this->showAll($alumnos);
    }

    //funcion para filtrar registros por codigo o alumno
    public function searchQuery($search = '')
    {
        $queryModel = Alumno::query();

        $columns = ['codigo',DB::raw("concat(primer_nombre, ' ', IFNULL(segundo_nombre,''),' ',IFNULL(tercer_nombre,''),' ',IFNULL(primer_apellido,''),' ',IFNULL(segundo_apellido,''))")];

        foreach ($columns as $column) {
           $queryModel->orWhere($column, 'LIKE', '%'.$search.'%');
        }


        $alumnos = $queryModel->get();

        return $this->showAll($alumnos);
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
            'codigo' => 'required|string:unique',
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'fecha_nac' => 'required',
            'genero' => 'required',
            'direccion' => 'required|string'
        ];
        
        $this->validate($request, $reglas);

        DB::beginTransaction();
            $alumno_exists = Alumno::where('codigo',$request->codigo)->withTrashed()->first();     
            
            if(!is_null($alumno_exists)) return $this->errorResponse('codigo de alumno ya fue asignado',422);


            $data = $request->all();
            $imagePath = '';
            if($request->foto != null || $request->foto != ''){
                if (preg_match('/^data:image\/(\w+);base64,/', $request->foto)) {
                    $data_img = substr($request->foto, strpos($request->foto, ',') + 1);
                    $data_img = base64_decode($data_img);
                    $imagePath = $request->codigo.'_'.time().'.png';
                    Storage::disk('images')->put($imagePath, $data_img);
                }

                $data['foto'] = 'img/alumnos/'.$imagePath;
            }

            $alumno = Alumno::create($data);

            $representante_id = $request->representante_id;

            if(is_null($representante_id)){

                $representante_exits = Apoderado::where('cui',$request->cui)->withTrashed()->first();
                if(!is_null($representante_exits)) return $this->errorResponse('cui de representante ya fue asignado, si el cui es de un representante existente, haga clic la opcion validar del formulario',422);

                $representante = Apoderado::create([
                    'cui' => $request->cui,
                    'primer_nombre' => $request->primer_nombre_a,
                    'segundo_nombre' => $request->segundo_nombre_a,
                    'primer_apellido' => $request->primer_apellido_a,
                    'segundo_apellido' => $request->segundo_apellido_a,
                    'email' => $request->email_a,
                    'fecha_nac' => $request->fecha_nac_a,
                    'direccion' => $request->direccion_a,
                    'ocupacion' => $request->ocupacion,
                    'municipio_id' => $request->municipio_id,
                    'estado_civil' => $request->estado_civil,
                    'nacionalidad' => $request->nacionalidad
                ]); 

                $representante_id = $representante->id;  

                TelefonoApoderado::create([
                    'tipo_telefono' => 'P',
                    'telefono' => $request->telefono_a,
                    'apoderado_id' => $representante_id
                ]);
            }

            ApoderadoAlumno::create([
                'apoderado_id' => $representante_id,
                'alumno_id' => $alumno->id,
                'responsable' => true,
                'tipo_apoderado' => $request->tipo_apoderado
            ]);

        DB::commit();

        return $this->showOne($alumno,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function show(Alumno $alumno)
    {
        $alumno = Alumno::where('id',$alumno->id)->with(
            'responsable.apoderado','inscripciones.ciclo',
            'inscripciones.grado_nivel_educativo.grado',
            'inscripciones.grado_nivel_educativo.nivelEducativo')->first();
        return $this->showOne($alumno);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alumno $alumno)
    {
        $reglas = [
            'codigo' => 'required|string|unique:alumnos,codigo,' . $alumno->id,
            'primer_nombre' => 'required|string',
            'primer_apellido' => 'required|string',
            'fecha_nac' => 'required',
            'genero' => 'required',
            'direccion' => 'required|string'
        ];
        
        $this->validate($request, $reglas);

        $alumno->codigo = $request->codigo;
        $alumno->primer_nombre = $request->primer_nombre;
        $alumno->segundo_nombre = $request->segundo_nombre;
        $alumno->tercer_nombre = $request->tercer_nombre;
        $alumno->primer_apellido = $request->primer_apellido;
        $alumno->segundo_apellido = $request->segundo_apellido;
        $alumno->telefono = $request->felefono;
        $alumno->email = $request->email;
        $alumno->direccion = $request->direccion;
        $alumno->enfermedades = $request->enfermedades;
        $alumno->alergias = $request->alergias;

        if($request->foto != null || $request->foto != ''){
            $imagePath = '';
            if (preg_match('/^data:image\/(\w+);base64,/', $request->foto)) {
                $data = substr($request->foto, strpos($request->foto, ',') + 1);
                $data = base64_decode($data);
                $imagePath = $request->codigo.'_'.time().'.png';
                Storage::disk('images')->put($imagePath, $data);
            }
            $alumno->foto = 'img/alumnos/'.$imagePath;
        }

        if (!$alumno->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        
        $alumno->save();
        return $this->showOne($alumno);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\alumno  $alumno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alumno $alumno)
    {
       $inscripciones = $alumno->inscripciones;
        if(count($inscripciones)){
            return $this->errorResponse('no se puede eliminar alumno, porque ya tiene historial de inscripciones',422);
        }
        $alumno->delete();
        return $this->showOne($alumno);
    }

    public function validateRulesApoderado(Request $request)
    {
        $rules = [
            'cui',
            'primer_nombre',
            'tercer_nombre',
            'primer_apellido',
            'fecha_nac',
            'genero',
            'direccion'
        ];

        $this->validate($request,$rules);
    }

    public function historialAlumnos(){
        $alumnos = Alumno::with('inscripciones.ciclo','inscripciones.pagos.cuota.concepto_pago','inscripciones.pagos.pagos_parciales','inscripciones.pagos.pagos_meses','inscripciones.pagos.serie')->get();

        return $this->showAll($alumnos);
    }

    public function historialAlumno($id){
        $alumno = Alumno::where('id',$id)->with('responsable.apoderado')->first();
        $inscripciones = $alumno->inscripciones()->with('ciclo','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();
        $pagos = $alumno->inscripciones()->with('pagos.serie','pagos.pagos_parciales', 'pagos.pagos_meses','pagos.cuota.concepto_pago','pagos.cuota.ciclo')->get()->pluck('pagos')->collapse()->values();

        return response()->json([
            'alumno' =>$alumno,
            'inscripciones' => $inscripciones,
            'pagos' => $pagos
        ]);
    }
}
