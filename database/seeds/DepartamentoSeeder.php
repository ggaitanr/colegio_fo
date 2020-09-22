<?php

use Illuminate\Database\Seeder;
use App\Imports\DepartamentoImport;
use Maatwebsite\Excel\Facades\Excel;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
    	$datas = Excel::import(new DepartamentoImport, 'database/seeds/Departamento.xlsx', null, \Maatwebsite\Excel\Excel::XLSX);
    }
}
