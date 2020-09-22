<?php

namespace App\Http\Controllers\Inscripcion;

use App\Ciclo;
use App\Cuota;
use App\Inscripcion;
use App\Institucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class InscripcionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:inscripcion')->except(['index']);
    }

    public function index()
    {
        $inscripciones = Inscripcion::with('ciclo','alumno','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->get();
        return $this->showAll($inscripciones);
    }

    public function getByFechas($inicio = null, $fin = null, $grado = 0)
    {
        if($inicio !== null && $fin!== null){
            $inscripciones = Inscripcion::whereRaw('DATE(fecha) >= ?', [$inicio])
            ->whereRaw('DATE(fecha) <= ?', [$fin])
            ->with('ciclo','alumno','grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')
            ->orderBy('id','asc')
            ->get();
        }

        if((int)$grado !== 0){
            $inscripciones = $inscripciones->where('grado_nivel_educativo_id',$grado)->values();
        }

        return $this->showAll($inscripciones);
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
            'alumno_id' => 'required|integer',
            'ciclo_id' => 'required|integer',
            'grado_nivel_educativo_id' => 'required|integer',
            'fecha' => 'required',
            'jornada' => 'required'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $data['numero'] = $this->getCorrelativo($request->ciclo_id);
        $inscripcion = Inscripcion::create($data);

        return $this->showOne($inscripcion,201);
    }

    public function documento(Request $request)
    {
        $reglas = [
            "documento" => "required|mimes:pdf|max:10000"
        ];
        
        $this->validate($request, $reglas);
        $inscripcion = Inscripcion::where('id',$request->id)->with('alumno')->first();

        $folder = 'alumno_'.$inscripcion->alumno_id.$inscripcion->alumno->primer_nombre.$inscripcion->alumno->primer_apellido;
        $name = $request->documento->getClientOriginalName();
        $inscripcion->documento = $request->documento->storeAs($folder, $name);

        $inscripcion->save();

        return $this->showOne($inscripcion,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inscripcion  $Inscripcion
     * @return \Illuminate\Http\Response
     */
    public function show(Inscripcion $inscripcione)
    {
        return $this->showOne($inscripcione);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inscripcion  $Inscripcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscripcion $inscripcione)
    {
        $reglas = [
            'grado_nivel_educativo_id' => 'required|integer',
            'jornada' => 'required'
        ];

        $this->validate($request, $reglas);

        $inscripcione->grado_nivel_educativo_id = $request->grado_nivel_educativo_id;
        $inscripcione->fecha = $request->fecha;
        $inscripcione->jornada = $request->jornada;

         if (!$inscripcione->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $inscripcione->save();
        return $this->showOne($inscripcione);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inscripcion  $Inscripcion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inscripcion $inscripcione)
    {
        $pagos = $inscripcione->pagos;
        
        if(count($pagos)) return $this->errorResponse('no se puede eliminar inscripción porque ya tiene pagos asignados');
        $inscripcione->delete();
        return $this->showOne($inscripcione);
    }

    public function getCicloActual()
    {
        $ciclo_actual = Ciclo::where('actual',true)->first();
        if(is_null($ciclo_actual)) return $this->errorResponse('no se ha configurado el ciclo lectivo actual, redirecionando a lista de alumnos',422);

        return $this->showOne($ciclo_actual);
    }

    public function getContrato($inscripcion_id)
    {

        $inscripcion = Inscripcion::where('id',$inscripcion_id)->with('grado_nivel_educativo.grado','grado_nivel_educativo.nivelEducativo')->first();
        $alumno = $inscripcion->alumno;
        $cuota_inscripcion = Cuota::where('concepto_pago_id',1)->where('grado_nivel_educativo_id',$inscripcion->grado_nivel_educativo->id)->where('ciclo_id',$inscripcion->ciclo_id)->first();
        $cuota_colegiatura = Cuota::where('concepto_pago_id',2)->where('grado_nivel_educativo_id',$inscripcion->grado_nivel_educativo->id)->where('ciclo_id',$inscripcion->ciclo_id)->first();
        $responsable = $alumno->responsable()->with('apoderado.municipio.departamento','apoderado.telefonos')->first();
        $institucion = Institucion::with('municipio.departamento')->first();
        $date = $this->getCurrentDates(date("Y/m/d"));

        $pdf = \PDF::loadView('pdfs.contrato',['inscripcion'=>$inscripcion, 'alumno'=>$alumno, 'responsable' => $responsable, 'institucion' => $institucion,'date'=>$date,'cuota_inscripcion'=>$cuota_inscripcion,'cuota_colegiatura'=>$cuota_colegiatura]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->download('ejemplo.pdf');
    }

    function getCurrentDates($date)
    {
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $anio = iconv('ISO-8859-2', 'UTF-8', strftime("%Y", strtotime($date)));
        $mes = iconv('ISO-8859-2', 'UTF-8', strftime("%B", strtotime($date)));
        $dia = iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d", strtotime($date)));

       return (object)['dia'=>$dia,'mes'=>$mes,'anio'=>$anio];
    }

    function getCorrelativo($ciclo_id){
        $ciclo = Ciclo::find($ciclo_id);
        $inscripciones = $ciclo->inscripciones;
        return $ciclo->ciclo.'-'.(count($inscripciones)+1);
    }
}