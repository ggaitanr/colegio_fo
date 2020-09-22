<?php

namespace App\Http\Controllers\Pago;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Institucion;
use App\Pago;
use App\PagoMes;
use App\PagoParcial;
use App\SerieFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoController extends  ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:pago')->except(['getByFechas','comprobante']);
    }

    public function index()
    {
        $pagos = Pago::with('inscripcion.alumno','apoderado','cuota.concepto_pago','inscripcion.ciclo','pagos_parciales','pagos_meses','serie')->get();
        return $this->showAll($pagos);
    }

    public function getByFechas($inicio = null, $fin = null)
    {
        $pagos = Pago::whereRaw('DATE(fecha) >= ?', [$inicio])
            ->whereRaw('DATE(fecha) <= ?', [$fin])
            ->with('inscripcion.alumno','apoderado','cuota.concepto_pago','inscripcion.ciclo','pagos_parciales','pagos_meses','serie')
            ->orderBy('id','asc')
            ->get();

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
            'inscripcion_id' => 'required|integer',
            'cuota_id' => 'required|integer',
            'serie_factura_id' => 'required|integer',
            'total' => 'required',
            'apoderado_id' => 'required',
            'total_cancelado' => 'required',
            'fecha' => 'required'
        ];
        
        $this->validate($request, $reglas);
        DB::beginTransaction();
            if($request->exonerar_mora == '' || is_null($request->exonerar_mora)){
                $request->exonerar_mora = 0;
            }
            $data = $request->all();

            if(!$request->is_credito){
                $data['pagado'] = true;
            }

            $serie = SerieFactura::where('id', $request->serie_factura_id)->first();

            if(count($serie->pagos)){
                $data['factura'] = $serie->no_actual + 1;
            }else{
                $data['factura'] = $serie->no_inicial;
            }
            
            $pago = Pago::create($data);
            $serie->no_actual = $pago->factura;
            if($serie->no_actual == $serie->no_facturas){
                $serie->actual = false;
            }
            $serie->save();

            if($request->is_credito){
                PagoParcial::create([
                    'pago_id' =>$pago->id,
                    'pago' => $request->total_cancelado,
                    'apoderado_id' => $pago->apoderado_id,
                    'fecha' => $request->fecha
                ]);

            }

            if($request->pago_mensual){
                foreach ($request->meses as $mes) {
                    PagoMes::create([
                        'pago_id' => $pago->id,
                        'mes_id' => $mes
                    ]);
                }
            }

        DB::commit();

        return $this->showOne($pago,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pago  $Pago
     * @return \Illuminate\Http\Response
     */
    public function show(Pago $pago)
    {
        $pago = Pago::where('id',$pago->id)->with('inscripcion.ciclo','inscripcion.alumno.responsable.apoderado','cuota.concepto_pago', 'pagos_parciales','pagos_meses.mes')->first();
        return $this->showOne($pago);
    }

    /**
     * Update the specified resource in storage.
     * //aqnular pago
     */
    public function update(Request $request, Pago $pago)
    {
        DB::beginTransaction();
            $pagos_parciales = $pago->pagos_parciales;
            if(count($pagos_parciales) > 1){
                return $this->errorResponse('no se puede anular pago al credito, porque tiene mas de un abono realizado',422);
             }else{
                foreach ($pagos_parciales as $pago_p) {
                    $pago_p->anulado = true;
                    $pago_p->motivo_anulado = 'por anulación de pago principal';
                    $pago_p->save();
                }
             }

            $pago->anulado = true;
            $pago->motivo_anulado = $request->motivo_anulado;
            $pago->save();

        DB::commit();

        return $this->showOne($pago);
    }

    //anular pago realizado por alumno
    public function anular($id, Request $request)
    {
        DB::beginTransaction();
            $pago = Pago::find($id);
            $pagos_parciales = $pago->pagos_parciales;
            if(count($pagos_parciales) > 1) return $this->errorResponse('no se puede anular pago al credito, porque tiene mas de un abono realizado',422);

            $pago->pagos_meses()->delete();
            $pago->anulado = true;
            $pago->motivo_anulado = $request->motivo_anulado;
            $pago->save();

        DB::commit();

        return $this->showOne($pago);
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
        $pago = Pago::where('id',$id)->with('inscripcion.ciclo','inscripcion.alumno','serie','apoderado','cuota.concepto_pago', 'pagos_parciales','pagos_meses.mes')->first();

         $institucion = Institucion::with('municipio.departamento')->first();

        $pdf = \PDF::loadView('pdfs.comprobante',['pago'=>$pago,'institucion'=>$institucion]);

        $pdf->setPaper('legal', 'portrait');

        return $pdf->download('comprobante.pdf');
    }
}
