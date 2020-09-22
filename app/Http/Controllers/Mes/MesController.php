<?php

namespace App\Http\Controllers\Mes;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mes;
use Illuminate\Http\Request;

class MesController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $meses = Mes::all();
        return $this->showAll($meses);
    }
}
