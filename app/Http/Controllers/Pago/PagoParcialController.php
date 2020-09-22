<?php

namespace App\Http\Controllers\Pago;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Pago;
use App\PagoParcial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoParcialController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:pago');
    }

    public function index()
    {
        $pagos = PagoParcial::all();
        return $this->showAll($pagos);
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
            'pago_id' => 'required|integer',
            'pago' => 'required',
            'fecha' => 'required'
        ];
        
        $this->validate($request, $reglas);
        DB::beginTransaction();
            $data = $request->all();
            $pago = Pago::where('id',$request->pago_id)->with('inscripcion.alumno.responsable.apoderado','cuota.concepto_pago','cuota.ciclo')->first();

            if(is_null($pago->inscripcion->alumno->responsable)){
                return $this->errorResponse('alumno actualmente no tiene asignado responsable',422);
            }
            $apoderado_id = $pago->inscripcion->alumno->responsable->apoderado->id;
            $data['apoderado_id'] = $apoderado_id;
            $pago_parcial = PagoParcial::create($data);

            $pago->total_cancelado = $pago->total_cancelado + $request->pago;

            if($pago->total == $pago->total_cancelado){
                $pago->pagado = true;
            }
            $pago->save();

        DB::commit();

        return $this->showOne($pago,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $Pago
     * @return \Illuminate\Http\Response
     */
    public function show(PagoParcial $pagos_parciale)
    {
        return $this->showOne($Pagos_parciale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pago  $Pago
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PagoParcial $pagos_parciale)
    {
        DB::beginTransaction();
            $pagos_parciale->anulado = true;
            $pagos_parciale->motivo_anulado = $request->motivo_anulado;
            $pagos_parciale->save();

            $pago = Pago::where('id',$pagos_parciale->pago_id)->with('inscripcion','cuota.concepto_pago','cuota.ciclo')->first();
            $pago->pagado = false;
            $pago->total_cancelado = $pago->total_cancelado - $pagos_parciale->pago;
            $pago->save();

        DB::commit();

        return $this->showOne($pago,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pago  $Pago
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pago $Pago)
    {
     
    }

    public function comprobante($id)
    {
        $abono = PagoParcial::where('id',$id)->with('pago_padre.inscripcion.ciclo','pago_padre.inscripcion','pago_padre.cuota.concepto_pago','pago_padre.apoderado','pago_padre.serie')->first();

        $pdf = \PDF::loadView('pdfs.comprobante_abono',['abono'=>$abono]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->download('comprobante_abono.pdf');
    }
}
