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
        $data->profesion = 'Administradora Educativa';
        $data->calidad_de = 'Directora educativa';
        $data->estado_civil = 'Casada';
        $data->nacionalidad = 'Guatemalteca';
        $data->fecha_nac = "1988-8-26";
        $data->cui ="2562785040101";
        $data->nit ="9167142-6";
        $data->telefono ="7884-6126";
        $data->direccion = 'Barrio San Pedro';
        $data->mora = 10;
        $data->dia_pago = 5;
        $data->save();
    }
}
