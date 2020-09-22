<?php

use App\Cuota;
use App\ConceptoPago;
use Illuminate\Database\Seeder;

class ConceptoPagoSeeder extends Seeder
{
    public function run()
    {
        $data = new ConceptoPago;
        $data->nombre = 'inscripcion';
        $data->obligatorio = true;
        $data->is_parcial = true;
        $data->forma_pago = 'A';
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 200;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 250;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 300;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 350;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'colegiatura';
        $data->obligatorio = true;
        $data->is_parcial = true;
        $data->forma_pago = 'M';
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 150;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 175;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 180;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 190;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'aporte educativo';
        $data->obligatorio = true;
        $data->is_parcial = false;
        $data->forma_pago = 'A';
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 50;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 75;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 90;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 100;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'cuota aniversario';
        $data->obligatorio = true;
        $data->is_parcial = false;
        $data->forma_pago = 'A';
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 150;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 150;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 150;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 150;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'cuota computacion';
        $data->obligatorio = true;
        $data->is_parcial = false;
        $data->forma_pago = 'M';
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 75;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 80;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 90;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 100;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'paquete libros';
        $data->obligatorio = false;
        $data->is_parcial = true;
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 200;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 250;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 300;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 350;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'uniforme fisica';
        $data->obligatorio = false;
        $data->is_parcial = false;
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'tela para falda';
        $data->obligatorio = false;
        $data->is_parcial = false;
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 50;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 50;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 50;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 50;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }

        $data = new ConceptoPago;
        $data->nombre = 'camisa polo';
        $data->obligatorio = false;
        $data->is_parcial = false;
        $data->save();

        for($i=1; $i<20; $i++){
        	if($i<7){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>6 && $i<10){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else if($i>9 && $i<12){
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}else{
        		$cuota = new Cuota;
        		$cuota->cuota = 125;
        		$cuota->grado_nivel_educativo_id = $i;
        		$cuota->ciclo_id = 1;
        		$cuota->concepto_pago_id = $data->id;
        		$cuota->save();
        	}
        }
    }
}
