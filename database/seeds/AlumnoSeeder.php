<?php

use App\Alumno;
use App\Apoderado;
use App\TelefonoApoderado;
use App\ApoderadoAlumno;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Alumno();
        $data->codigo = "0001";
        $data->primer_nombre = "Gabriela";
        $data->segundo_nombre = "Eunice";
        $data->tercer_nombre = "";
        $data->primer_apellido = "Gaitan";
        $data->segundo_apellido = "Rivas";
        $data->fecha_nac = "1998-01-06";
        $data->genero = "F";
        $data->telefono = "57896754";
        $data->email = "gabriela@gmail.com";
        $data->direccion = "Barrio san sebastian, Guazacapan Santa Rosa";
        $data->save();

        $data_p = new Apoderado();
        $data_p->cui = "123215456987";
        $data_p->primer_nombre = "Ruth";
        $data_p->segundo_nombre = "";
        $data_p->primer_apellido = "Rivas";
        $data_p->segundo_apellido = "";
        $data_p->fecha_nac = "1980-03-25";
        $data_p->email = "";
        $data_p->direccion = "Barrio san sebastian, Guazacapan Santa Roa";
        $data_p->municipio_id = 1;
        $data_p->nacionalidad = "Guatemalteco";
        $data_p->estado_civil = 'Casado';
        $data_p->nit = '789563-7';
        $data_p->save();

        TelefonoApoderado::create([
            'tipo_telefono' => 'P',
            'telefono' => "55776655",
            'apoderado_id' => $data_p->id
        ]);

        ApoderadoAlumno::create([
            'apoderado_id' => $data_p->id,
            'alumno_id' => $data->id,
            'responsable' => true,
            'tipo_apoderado' => 'M'
        ]);
    }
}
