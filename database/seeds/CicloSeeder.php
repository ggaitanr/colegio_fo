<?php

use Illuminate\Database\Seeder;
use App\Ciclo;

class CicloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Ciclo();
        $data->ciclo = 2020;
        $data->actual = true;
        $data->inicio = '2020-01-08';
        $data->fin = '2020-10-24';
        $data->save();
    }
}
