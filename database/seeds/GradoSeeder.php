<?php

use App\Grado;
use Illuminate\Database\Seeder;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Grado();
        $data->nombre = "primero";
        $data->save();

        $data = new Grado();
        $data->nombre = "segundo";
        $data->save();

        $data = new Grado();
        $data->nombre = "tercero";
        $data->save();

        $data = new Grado();
        $data->nombre = "cuarto";
        $data->save();

        $data = new Grado();
        $data->nombre = "quinto";
        $data->save();

        $data = new Grado();
        $data->nombre = "sexto";
        $data->save();
    }
}
