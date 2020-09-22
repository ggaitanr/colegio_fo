<?php

use Illuminate\Database\Seeder;
use App\Imports\MunicipioImport;
use Maatwebsite\Excel\Facades\Excel;

class MunicipioSeeder extends Seeder
{
    public function run()
    {
        $datas = Excel::import(new MunicipioImport, 'database/seeds/Municipios.xlsx', null, \Maatwebsite\Excel\Excel::XLSX);
    }
}
