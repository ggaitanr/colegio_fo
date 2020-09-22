<html>
    <head>
        <style>
            .header{background:#eee;color:#444;border-bottom:1px solid #ddd;padding:10px;}

            .client-detail{background:#ddd;padding:10px;}
            .client-detail th{text-align:left;}

            .items{border-spacing:0;}
            .items thead{background:#ddd;}
            .items tbody{background:#eee;}
            .items tfoot{background:#ddd;}
            .items th{padding:10px;}
            .items td{padding:10px;}

            h1 small{display:block;font-size:16px;color:#888;}

            table{width:100%;}
            .text-right{text-align:right;}

            #imagen {
                width: 100px;
            }

            #anulado {
                color: rgba(255, 0, 0, 0.5);
                -webkit-transform: rotate(-45deg); 
                -moz-transform: rotate(-45deg);    
                width:100px; text-align: center; position: relative; float: left; width: 100%
            }
        </style>
    </head>
    <body>

    <div class="header">
        <table width="100%">
            <tbody>
                <tr>
                    <td width="80%">
                    <h1>
                        Comprobante # {{ str_pad ($abono->id, 7, '0', STR_PAD_LEFT) }}
                        <small>
                            Emitido el {{ date('d/m/Y', strtotime($abono->fecha)) }}
                        </small>
                    </h1>
                    </td>
                    <td style="text-align: left;">
                        <div>
                            <img  id="imagen" src="{{asset('img/logo.jpg')}}"/>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <table class="client-detail">
        @if($abono->anulado)
        <h1 id="anulado">
            ANULADO
        </h1>
        @endif

        <tr>
            <th style="width:100px;">
                Responsable
            </th>
            <td>{{ $abono->pago_padre->apoderado->primer_nombre }}
                {{ $abono->pago_padre->apoderado->segundo_nombre }}
                {{ $abono->pago_padre->apoderado->primer_apellido }}
                {{ $abono->pago_padre->apoderado->segundo_apellido }}</td>
        </tr>
        <tr>
            <th>NIT</th>
            <td>{{ $abono->pago_padre->apoderado->nit !== null ? $abono->pago_padre->apoderado->nit:'C/F' }}</td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>{{ $abono->pago_padre->apoderado->direccion }}</td>
        </tr>
    </table>

    <hr />

    <table class="items">
        <thead>
            <tr>
                <th class="text-left">Descripción</th>
                <th class="text-right" style="width:100px;">Total abono</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>abono a factura no {{str_pad ($abono->pago_padre->factura, strlen($abono->pago_padre->serie->no_facturas), '0', STR_PAD_LEFT)}} por concepto de 
                    {{$abono->pago_padre->cuota->concepto_pago->nombre}} ciclo escolar {{$abono->pago_padre->inscripcion->ciclo->ciclo}}
                 </td>
                    }
                <td class="text-right">Q {{number_format($abono->pago, 2)}}</td>
            </tr>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
    </body>
</html>