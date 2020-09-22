<?php

namespace App\Imports;

use App\Menu;
use App\MenuRol;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class MenuImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if($value[0] != ''){
                DB::beginTransaction();

                    $insert = new Menu();
                    $insert->text = $value[0];
                    $insert->path = $value[1];
                    $insert->name = $value[2];
                    $insert->father = $value[3];
                    $insert->icon = $value[4];
                    $insert->hide = $value[5];
                    $insert->save();

                    if(!is_null($value[6]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rol_id = $value[6];
                        $insert_menu_rol->menu_id = $insert->id;
                        $insert_menu_rol->save();
                    }

                    if(!is_null($value[7]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rol_id = $value[7];
                        $insert_menu_rol->menu_id = $insert->id;
                        $insert_menu_rol->save();
                    }

                    if(!is_null($value[8]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rol_id = $value[8];
                        $insert_menu_rol->menu_id = $insert->id;
                        $insert_menu_rol->save();
                    }

                    if(!is_null($value[9]))
                    {
                        $insert_menu_rol = new MenuRol();
                        $insert_menu_rol->rol_id = $value[9];
                        $insert_menu_rol->menu_id = $insert->id;
                        $insert_menu_rol->save();
                    }

                DB::commit();
    		}
		}
	}
}
