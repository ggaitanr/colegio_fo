<?php

use Illuminate\Database\Seeder;
use App\Seccion;

class SeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Seccion();
        $data->seccion = "A";
        $data->save();

        $data = new Seccion();
        $data->seccion = "B";
        $data->save();

        $data = new Seccion();
        $data->seccion = "C";
        $data->save();
    }
}
