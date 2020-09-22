<?php

use App\Imports\MenuImport;
use App\Rol;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //todo lo administrativo
        $data = new Rol; 
        $data->rol = 'admin';
        $data->save();

        //todo lo inscripciones, gestion de pagos
        $data = new Rol;
        $data->rol = 'secretario';
        $data->save();

        //todo lo financiero
        $data = new Rol;
        $data->rol = 'financiero';
        $data->save();

        //reportes
        $data = new Rol;
        $data->rol = 'reportes';
        $data->save();

        Excel::import(new MenuImport, 'database/seeds/Menu.xlsx');
    }
}
