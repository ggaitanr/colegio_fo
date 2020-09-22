<?php

namespace App\Http\Controllers\SerieFactura;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\SerieFactura;
use Illuminate\Http\Request;

class SerieFacturaController extends ApiController
{
     public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:seriefactura')->except(['index']);
    }

    public function index()
    {
        $serie_facturas = SerieFactura::all()->sortBy('id');

        return $this->showAll($serie_facturas);
    }

    public function store(Request $request)
    {
        $reglas = [
            'serie' => 'required',
            'no_facturas' => 'required'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $data['no_actual'] = $request->no_inicial;
        $serie_actual = SerieFactura::where('actual',true)->first();
        if(!is_null($serie_actual)) return $this->errorResponse('No se puede crear otra serie hasta agotar el numero de facturas', 422);

        $serie_factura = SerieFactura::create($data);

        return $this->showOne($serie_factura,201);
    }

    public function show(SerieFactura $serie_factura)
    {
        return $this->showOne($serie_factura);
    }

    public function update(Request $request, SerieFactura $serie_factura)
    {
        $reglas = [
            'serie' => 'required',
            'no_facturas' => 'required'
        ];

        $this->validate($request, $reglas);

        $serie_factura->serie = $request->serie;
        $serie_factura->no_facturas = $request->no_facturas;

        if(count($serie_factura->pagos) && $serie_factura->no_inicial !== $request->no_inicial){
            return $this->errorResponse('No se puede actualizar no incial de factura porque ya existen facturas realizadas con serie', 422);
        }
        
        $serie_factura->no_inicial = $request->no_inicial;

         if (!$serie_factura->isDirty()) {
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        $serie_factura->save();
        return $this->showOne($serie_factura);
    }

    public function destroy(SerieFactura $serie_factura)
    {
         if(count($serie_factura->pagos)){
            return $this->errorResponse('No se puede eliminar serie facturas porque ya existen pagos relacionados a ella', 422);
        }
        $serie_factura->delete();
        return $this->showOne($serie_factura);
    }

    public function serieActiva()
    {
        $serie = SerieFactura::where('actual',true)->with('pagos')->first();

        if(is_null($serie)) return $this->errorResponse('No existe serie activa, por favor configure una una serie en la sección de serie facturas', 422);

        return $this->showOne($serie);
    }
}

