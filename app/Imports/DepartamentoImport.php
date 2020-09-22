<?php

namespace App\Imports;

use App\Departamento;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DepartamentoImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $insert = new Departamento();
            $insert->nombre = $row[0];
            $insert->save();
        }
    }
}
