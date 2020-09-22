<?php

use Illuminate\Database\Seeder;
use App\NivelEducativo;
use App\GradoNivelEducativo;

class NivelEducativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new NivelEducativo;
        $data->nombre = "Primaria";
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 1; $i<7; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }

        $data = new NivelEducativo;
        $data->nombre = "Basico";
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 1; $i<4; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }

        $data = new NivelEducativo;
        $data->nombre = "Bachiller en computacion";
        $data->es_carrera= true;
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 4; $i<6; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }

        $data = new NivelEducativo;
        $data->nombre = "Bachiller en ciencias y letras";
        $data->es_carrera = true;
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 4; $i<6; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }

        $data = new NivelEducativo;
        $data->nombre = "Perito en administracion de empresas";
        $data->es_carrera = true;
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 4; $i<7; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }

        $data = new NivelEducativo;
        $data->nombre = "Perito contador";
        $data->es_carrera = true;
        $data->resolucion = '1969-2017';
        $data->fecha = '2017-01-08';
        $data->save();

        for($i = 4; $i<7; $i++){
        	$g_n = new GradoNivelEducativo;
        	$g_n->nivel_educativo_id = $data->id;
        	$g_n->grado_id = $i;
        	$g_n->save();
        }
    }
}
