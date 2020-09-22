<?php

use Illuminate\Database\Seeder;
//use App\Inscripcion;
use App\Institucion;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$data = new Institucion();
        $data = new Institucion;
        $data->definicion = 'COLEGIO PARTICULAR MIXTO';
        $data->nombre = 'FRANCISCO ORANTES';
        $data->representante_legal = 'Marilú Orantes';
        $data->municipio_id = 1;
        $data->profesion = 'Lincenciada en pedagogia';
        $data->calidad_de = 'Directora educativa';
        $data->estado_civil = 'Casada';
        $data->nacionalidad = 'Guatemalteca';
        $data->fecha_nac = "1990-5-23";
        $data->cui ="2548966180501";
        $data->nit ="168263-3";
        $data->telefono ="7881-1076";
        $data->direccion = 'Barrio san pedro';
        $data->mora = 10;
        $data->dia_pago = 5;
        $data->save();
    }
}
