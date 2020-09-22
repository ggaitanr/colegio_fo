<?php

use Illuminate\Database\Seeder;
use App\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Curso();
        $data->nombre = "Matematicas";
        $data->save();

        $data = new Curso();
        $data->nombre = "Ciencias naturales";
        $data->save();

        $data = new Curso();
        $data->nombre = "Ciencias sociales";
        $data->save();

        $data = new Curso();
        $data->nombre = "Idioma español";
        $data->save();

        $data = new Curso();
        $data->nombre = "Idioma ingles";
        $data->save();

        $data = new Curso();
        $data->nombre = "Computacion";
        $data->save();

        $data = new Curso();
        $data->nombre = "Musica";
        $data->save();

        $data = new Curso();
        $data->nombre = "Hogar";
        $data->save();

        $data = new Curso();
        $data->nombre = "Agropecuaria";
        $data->save();
    }
}
