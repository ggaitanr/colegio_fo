<?php

namespace App\Http\Controllers\Rol;

use App\Rol;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class RolController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $roles = Rol::all();
        return $this->showAll($roles);
    }

    /**
     */
    public function store(Request $request)
    {
    }

    public function show(Ciclo $ciclo)
    { 
    }

    /**
     */
    public function update(Request $request, Ciclo $ciclo)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ciclo $ciclo)
    {
    }
}

