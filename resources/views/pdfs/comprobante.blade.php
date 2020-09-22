<html>
    <head>
        <style>
            #factura {
                padding: 0px;
                font-family: Arial, sans-serif;
                font-size: 14px ;
            }

            #logo {
                float: left;
                margin-left: 2%;
                margin-right: 2%;
            }
            #imagen {
                width: 100px;
            }

            #fact {
                font-size: 18px;
                font-weight: bold;
                text-align: center;
            }   

            #datos {
                float: left;
                margin-top: 0%;
                margin-right: 2%;
                /*text-align: justify;*/
            }

            #encabezado {
                text-align: center;
                margin-left: 10px;
                margin-right: 10px;
                font-size: 16px;
            }

            section {
                clear: left;
            }

            #cliente {
                text-align: left;
            }

            #facliente {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 15px;
            }

            #fa {
                color: #FFFFFF;
                font-size: 14px;
            }

            #facarticulo {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
                margin-bottom: 15px;
            }

            #facarticulo thead {
                padding: 20px;
                background: #2183E3;
                text-align: center;
                border-bottom: 1px solid #FFFFFF;
            }

            #anulado {
                color: rgba(255, 0, 0, 0.5);
                top: 30%;
                left: 50%;
                font-size: 40px;
                -webkit-transform: rotate(-45deg); 
                -moz-transform: rotate(-45deg);    
                width:100px; text-align: center; position: absolute;
            }
        </style>
    </head>
    <body>
        <div id="factura">
            <header>
                <table id="facliente">
                    <tbody>
                        <tr>
                            <td style="text-align: left;" width="20%">
                                <div>
                                    <img  id="imagen" src="{{asset('img/logo.jpg')}}"/>
                                </div>
                            </td>
                            <td style="text-align:center; font-size: 12px;" width="50%">
                               <span> {{strtoupper($institucion->definicion)}}</span><br/>
                                <span><b>"{{$institucion->nombre}}"</b></span><br/>
                                <span>"{{$institucion->direccion}}"</span><br/>
                                <span>
                                Acuerdo ministerial No. 486 7881-1076 NIT {{$institucion->nit}} <br>Email:jcarlos.ad7@gmail.com</span>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <p><b>FACTURA SERIE "{{$pago->serie->serie}}"</b><br>
                                <span style="color: red;">No. {{ str_pad ($pago->factura, strlen($pago->serie->no_facturas), '0', STR_PAD_LEFT) }}</span><br></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </header>
            <br>
            <section>
                <div>
                    <table id="facliente">
                        <tbody>
                            <tr>
                                <td width="80%" id="cliente">
                                    <strong>Nombre. {{$pago->apoderado->primer_nombre}}
                                    {{$pago->apoderado->segundo_nombre}}
                                    {{$pago->apoderado->primer_apellido}}
                                    {{$pago->apoderado->segundo_apellido}}
                                    </strong><br>
                                </td>

                                <td id="cliente">
                                    <strong>NIT. {{$pago->apoderado->nit !== null ? $pago->apoderado->nit:'C/F'}}</strong><br>
                                </td>
                            </tr>
                            <tr>
                                <td id="cliente">
                                    <strong>Dirección. {{$pago->apoderado->direccion}}</strong><br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            <br>
            <section>
                <div>
                    <table id="facarticulo">

                        <thead>
                            <tr id="fa">
                                <th>DESCRIPCION</th>
                                <th>VALOR</th>
                            </tr>
                        </thead>

                            
                            @if($pago->anulado)
                            <h1 id="anulado">
                                ANULADA
                            </h1>
                            @endif
                        <tbody>
                            @if($pago->cuota->concepto_pago->forma_pago === 'A' || $pago->cuota->concepto_pago->forma_pago === 'N')
                              <tr>
                                <td style="text-align:center;">pago por concepto de {{$pago->cuota->concepto_pago->nombre}} ciclo escolar {{$pago->inscripcion->ciclo->ciclo}}
                                    {{$pago->descripcion}}
                                 </td>
                                    }
                                <td style="text-align:center;"> Q {{number_format($pago->total - $pago->mora, 2)}} </td>
                            </tr>
                            @else
                                <tr>
                                    <td style="text-align:center;">pago por concepto de {{$pago->cuota->concepto_pago->nombre}} ciclo escolar {{$pago->inscripcion->ciclo->ciclo}} </td>
                                    <td style="text-align:center;"></td>
                                </tr>
                                @foreach($pago->pagos_meses as $m)
                                    <tr>
                                        <td style="text-align:center;">pago mes de {{$m->mes->mes}} </td>
                                        <td style="text-align:center;">Q {{number_format($pago->cuota->cuota, 2)}}</td>
                                    </tr>
                                @endforeach
                              @endif

                            <tr>
                                @if($pago->cuota->concepto_pago->mora && $pago->mora > 0)
                                    <td style="text-align:center;">recargo por mora</td>
                                    <td style="text-align:center;"> Q {{number_format($pago->mora, 2)}} </td>
                                @endif
                            </tr>
                          
                        </tbody>
                        <tfoot>
                            <tr style="font:bold;">
                                <td style="text-align:right;">Total</td>
                                <td style="text-align:center;"><label>Q {{number_format($pago->total, 2)}}</label></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>
            <br>
            <br>
            <section>
                <table id="facliente">
                    <tbody>
                        <tr>
                            <td width="70%">
                                <p>(F)________________________________</p>
                                          <span style="margin-left: 100px;">  Secretaria</span>
                            </td>
                            
                            <td id="cliente">
                                Fecha.  {{date('d/m/Y', strtotime($pago->fecha))}}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p>
                    <span style="margin-left: 40px; font-size: 13px;"> Extento de I.S.R segun árticulo 6, inciso "C" Decreto 26-92 y sus Reformas de Ley de Impuestos sobre la renta.  </span><br />
                    <span style="font-size: 13px;"> Extento de I.V.A. segun árticulo 7, numeral 13 y árticulo 8, numeral 1, Decreto 27-92 Ley de Impuesto al Valor Agregado.  </span>
                    <small>
                        <b> </b>
                    </small>
                </p>
            </section>
        </div>
    </body>
</html>