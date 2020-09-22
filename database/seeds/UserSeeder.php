<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new User();
        $data->email = "secret@secret.com";
        $data->password = bcrypt("secret");
        $data->name = "Marilu Orantes";
        $data->rol_id = 1;
        $data->save();

        $data = new User();
        $data->email = "secretario@secretario.com";
        $data->password = bcrypt("secret");
        $data->name = "Pedro el secretario";
        $data->rol_id = 2;
        $data->save();

        $data = new User();
        $data->email = "financiero@financiero.com";
        $data->password = bcrypt("secret");
        $data->name = "Pedro el financiero";
        $data->rol_id = 3;
        $data->save();

        $data = new User();
        $data->email = "reportes@reportes.com";
        $data->password = bcrypt("secret");
        $data->name = "Pedro el de los reportes";
        $data->rol_id = 4;
        $data->save();
    }
}
