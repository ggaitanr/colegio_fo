<?php

namespace App\Imports;

use App\Municipio;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MunicipioImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $insert = new Municipio();
            $insert->nombre = $row[0];
            $insert->departamento_id = $row[1];
            $insert->save();
        }
    }
}
