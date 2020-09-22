<?php

namespace App\Http\Controllers\Rol;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Rol;
use Illuminate\Http\Request;

class RolMenuController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Rol $rol)
    {
        $rols = $rol->menus;
        return $this->showAll($rols);
    }
}
