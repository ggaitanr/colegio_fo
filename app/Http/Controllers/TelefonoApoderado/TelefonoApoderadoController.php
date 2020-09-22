<?php

namespace App\Http\Controllers\TelefonoApoderado;

use App\TelefonoApoderado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class TelefonoApoderadoController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $TelefonoApoderados = TelefonoApoderado::with('apoderado')->get();
        return $this->showAll($TelefonoApoderados);
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
            'apoderado_id' => 'required',
            'telefono' => 'required'
        ];
        
        $this->validate($request, $reglas);
        $data = $request->all();
        $TelefonoApoderado = TelefonoApoderado::create($data);

        return $this->showOne($TelefonoApoderado,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TelefonoApoderado  $TelefonoApoderado
     * @return \Illuminate\Http\Response
     */
    public function show(TelefonoApoderado $telefono_apoderado)
    {
        return $this->showOne($telefono_apoderado);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TelefonoApoderado  $TelefonoApoderado
     * @return \Illuminate\Http\Response
     */
    public function destroy(TelefonoApoderado $telefono_apoderado)
    {
        $telefono_apoderado->delete();
        return $this->showOne($telefono_apoderado);
    }
}
