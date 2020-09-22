<?php

use App\Mes;
use Illuminate\Database\Seeder;

class MesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Mes;
        $data->mes = 'enero';
        $data->save();

        $data = new Mes;
        $data->mes = 'febrero';
        $data->save();

        $data = new Mes;
        $data->mes = 'marzo';
        $data->save();

        $data = new Mes;
        $data->mes = 'abril';
        $data->save();

        $data = new Mes;
        $data->mes = 'mayo';
        $data->save();

        $data = new Mes;
        $data->mes = 'junio';
        $data->save();

        $data = new Mes;
        $data->mes = 'julio';
        $data->save();

        $data = new Mes;
        $data->mes = 'agosto';
        $data->save();

        $data = new Mes;
        $data->mes = 'septiembre';
        $data->save();

        $data = new Mes;
        $data->mes = 'octubre';
        $data->save();

        $data = new Mes;
        $data->mes = 'noviembre';
        $data->save();

        $data = new Mes;
        $data->mes = 'diciembre';
        $data->save();
    }
}
