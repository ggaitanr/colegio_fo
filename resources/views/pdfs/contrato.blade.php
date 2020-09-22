<html>

<head>
    <style>
        /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 1cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            text-align: center;
            line-height: 0.5cm;
            font-size: 14px;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        .logo {
            position: fixed;
            text-align: left;
            margin: 30px, 20px, 0px, 0px;
        }

        .title-body {
            font-size: 12px;
            font-weight: bold;
        }

        .text-body {
            font-size: 12px;
            font-weight: normal;
            text-align: justify;
        }

        .right {
            text-align: right;
        }

        p {
            line-height: 1.5em;
            font-size: 12px;
            text-align: justify;
        }

        ol li {
            padding: 5px;
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>
        <img class="logo" src="{{asset('img/logo.jpg')}}" width="100px" height="100px" />
        <b>CONTRATO DE ADHESION POR PRESTACION DE SERVICIOS EDUCATIVOS <br />
            DEL COLEGIO PREUNIVERSITARIO {{strtoupper($institucion->nombre)}} </b>
    </header>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <p class="right"> Contrato No. {{$inscripcion->numero}}<br />
            <b> Autorizado según resolución {{$inscripcion->grado_nivel_educativo->nivelEducativo->resolucion}} DIACO</b>
        </p>

        <p>En el municipio de Puerto San José, Departamento de Escuintla, el día: <span>{{$date->dia}}</span>
            del mes de {{$date->mes}} del año {{$date->anio}}</p>

        <p class="title-body" align="center"> DATOS DEL PROPIETARIO O REPRESENTANTE LEGAL DEL ESTABLECIMIENTO EDUCATIVO.</p>
        <p>{{$institucion->representante_legal}} de: {{ \Carbon\Carbon::createFromDate($institucion->fecha_nac)->age}} años de edad, de estado civil {{$institucion->estado_civil}}, nacionalidad {{$institucion->nacionalidad}} de profesión u oficio: {{$institucion->profesion}}, me identifico con Documento Personal de Identificación –DPI- numero {{$institucion->cui}} extendido por el REGISTRO NACIONAL DE LAS PERSONAS DE LA REPÚBLICA DE GUATEMALA, actúo en mi calidad de {{$institucion->calidad_de}} del Centro Educativo denominado COLEGIO PREUNIVERSITARIO {{$institucion->nombre}} de conformidad con Resolución Número {{$inscripcion->grado_nivel_educativo->nivelEducativo->resolucion}} de fecha {{\Carbon\Carbon::createFromDate($inscripcion->grado_nivel_educativo->nivelEducativo->fecha)->isoFormat('DD-MM-YYYY')}}.
        </p>
        <p class="title-body" align="center">DATOS DEL PADRE RESPONSABLE O REPRESENTANTE LEGAL DEL ALUMNO</p>
        <p>
            Nombre completo: {{$responsable->apoderado->primer_nombre.' '.$responsable->apoderado->segundo_nombre.' '.$responsable->apoderado->primer_apellido.' '.$responsable->apoderado->segundo_apellido}} de:
            {{ \Carbon\Carbon::createFromDate($responsable->apoderado->fecha_nac)->age}} años de edad, de estado civil
            {{$responsable->apoderado->estado_civil}}, nacionalidad Guatemalteca de profesión u oficio: {{$responsable->apoderado->ocupacion}} me identifico con Documento Personal de Identificación –DPI- número {{$responsable->apoderado->cui}} extendido por el REGISTRO NACIONAL DE LAS PERSONAS -RENAP- DE LA REPÚBLICA DE GUATEMALA, con residencia en: {{$responsable->apoderado->direccion}} con número de teléfono

            @foreach ($responsable->apoderado->telefonos as $t)
            <span style="margin-left: 5px;"></span> {{ $t->tipo_telefono == 'C' ? '   Casa: '.$t->telefono : $t->tipo_telefono == 'O' ? '    Oficina: '.$t->telefono : '      Celular: '.$t->telefono }}
            @endforeach

            , actuó en mi calidad de: @if ($responsable->tipo_apoderado === 'P')
            Padre de familia
            @elseif ($responsable->tipo_apoderado === 'M')
            Madre de familia
            @else
            Representante legal
            @endif
        </p>
        <p>
            Los arriba identificados, aseguramos ser los datos de identificación anotados, estar en libre ejercicio de nuestros derechos civiles y por el presente acto comparecemos a celebrar CONTRATO DE ADHESION POR PRESTACION DE SERVICIOS EDUCATIVOS de conformidad con las siguientes clausulas:
        </p>

        <p class="text-body">
            <b>PRIMERA: DEL ALUMNO A QUIEN SE LE PROPORCINARA EL SERVICIO EDUCATIVO</b><br />
            Nombre completo: {{$alumno->primer_nombre.' '.$alumno->segundo_nombre.' '.$alumno->tercer_nombre.' '.$alumno->primer_apellido.' '.$alumno->segundo_apellido}} quien cursara el {{ $inscripcion->grado_nivel_educativo->grado->nombre }}, grado, nivel {{ $inscripcion->grado_nivel_educativo->nivelEducativo->nombre }}
            carrera: {{$inscripcion->grado_nivel_educativo->nivelEducativo->es_carrera ? $inscripcion->grado_nivel_educativo->nivelEducativo->nombre : '---'}} jornada/plan {{$inscripcion->jornada == 'M' ? 'Matutina': 'Vespertina'}} el cual está debidamente autorizado por el Ministerio de Educación de conformidad con la resolución No. {{$inscripcion->grado_nivel_educativo->nivelEducativo->resolucion}} misma que se pone a la vista.<br /><br />

            <b>SEGUNDA: DEL PLAZO DE VIGENCIA</b><br />

            El servicio educativo convenido mediante este Contrato de Adhesión será válido para el presente ciclo lectivo durante el plazo de vigencia no puede ser modificada ninguna de sus cláusulas.<br /><br />

            <b>TERCERA: DERECHOS DEL ALUMNO Y PADRE DE FAMILIA O REPRESENTANTE LEGAL DEL MISMO.</b>
            <br />

            El alumno y el padre de familia o representante legal del mismo como usuarios del servicio educativo contratado, en armonía con el artículo 4 de la Ley de Protección al Consumidor y Usuario, tendrá derecho a:<br />

            <ol type="a" class="text-body">
                <li>
                    La protección a la vida, salud y seguridad en la adquisición consumo y uso de bienes y servicios: Las instalaciones del centro educativo están adecuadas para que los alumnos no corran ninguna clase de riesgo que ponga en peligro su integridad física, así también están dotadas de todos los servicios básicos ofrecidos a los padres de familia. En el caso que la prestación del servicio de transporte sea brindado por una entidad ajena al centro educativo este proporcionara todas las medidas de seguridad necesarias para la debida protección de los alumnos. Asimismo propondrán medidas de seguridad a los padres a través de un seguro escolar el cual no será obligatorio pero debe queda en acta la decisión del padre de la adquisición o la no adquisición de mismo, se debe de velar por la seguridad de los alumnos en cualquier actividad propuesta y hacerlo siempre del conocimiento de los padres para su respectivo consentimiento de la participación.
                </li>
                <li>
                    La libertad de elección del bien o servicio: Los padres de familia tienen el derecho de poder adquirir tanto los útiles escolares, como los uniformes, transporte, seguro y otros servicios adicionales en el establecimiento comercial que se adecue mejor a su capacidad económica, sin embargo el centro educativo puede facilitar la compra o prestación de servicios, siempre y cuando medie convenio por escrito en el cual lo solicitan los padres de familia, debiendo en tal caso cumplir con las obligaciones tributarias correspondientes.
                </li>
                <li>
                    La libertad de contratación: el padres de familia o representante legal del alumno tiene derecho de libre contratación por lo que para los bienes o servicios que sean necesarios para la educación de su(s) hijo(s) pueden contratar o adquirir los bienes o servicios que más se adecuen con su capacidad económica. Transporte, Seguro Escolar y otros.
                </li>
                <li>
                    La información veraz, suficiente, clara y oportuna sobre los bienes y servicios: El centro educativo se compromete a proporcionar a los padres de familia información completa sobre el servicio contratado y especialmente los horarios de clases, los grados y las carreras autorizadas que imparten, los sistemas de evaluación, los cursos adicionales que imparten, el monto de las cuotas que se cobran tanto de inscripción como de cuota mensual así como de las actividades extraescolares a que se pueden realizar durante el ciclo lectivo.
                    El centro educativo tiene la obligación de cumplir con cada una de las clausulas estipuladas en el presente contrato así como también con todo lo ofrecido a los padres de familia, tanto en la publicidad efectuada en los medios de comunicación, información escrita o cualquier otro documento publicitario.
                </li>
            </ol>
        </p>
        <p>
            El centro educativo tiene la obligación de cumplir con cada una de las cláusulas estipuladas en el presente contrato así como también con todo lo ofrecido a los padres de familia, tanto en la publicidad efectuada en los medios de comunicación, información escrita o cualquier otro documento publicitario. Utilizar el libro de Quejas o el medio legalmente por la Dirección de Atención y Asistencia al Consumidor para dejar registro de su disconformidad con respecto a un bien adquirido o servicio contratado; Al hacer constar su inconformidad en el libro de quejas, el padre de familia o representante legal del alumno, debe de esperar un período prudencial de ocho días para que la misma le sea resuelta por el centro educativo, transcurrido este tiempo sin que exista una solución deberá interponer la queja correspondiente ante DIACO, para iniciar el procedimiento administrativo.
        </p>
        <div style="page-break-before:always;">
            <p>
                <br /><br />
                <b>CUARTA: DERECHO DE RETRACTO.</b>
                <br />
                El padre de familia o representante legal del alumno, que hubiere firmado el presente contrato, tendrá derecho a retractarse dentro de un plazo no mayor de cinco días hábiles contados a partir de la firma del contrato, siempre que no hubieren hecho uso del bien o servicio. Si ejercita oportunamente este derecho le serán restituidos en su totalidad los valores pagados.
                <br /><br />
                <b>QUINTA: OBLIGACIONES DEL PADRE DE FAMILIA O REPRESENTANTE LEGAL DEL ALUMNO:</b>
                <br />
                El padre de familia o representante legal del alumno, en armonía con el artículo 5 de la Ley de Protección al Consumidor y Usuario, tendrá las siguientes obligaciones:
                <ol type="a" class="text-body">
                    <li>
                        Pagar por los bienes o servicios en el tiempo, modo y condiciones establecidas mediante el presente contrato.
                    </li>
                    <li>
                        Utilizar los bienes y servicios en observancia a uso moral y de conformidad a con las especificaciones proporcionadas por el proveedor y cumplir con la condiciones pactadas en el presente contrato, debiendo para tal efecto instruir al alumno sobre el cuidado tanto de instalaciones como de mobiliario y equipo del centro educativo; y en caso de daños y perjuicios ocasionados por el alumno, el padre de familia o representante legal, será el responsable, siempre y cuando sean debidamente comprobados y atribuidos al mismo.
                    </li>
                </ol>
            </p>
            <p>
                <b>SEXTA: DE LAS COUTAS.</b>
                <br />
                Como padre de familia o representante legal me comprometo a efectuar los siguientes pagos:
                <ol type="a" class="text-body">
                    <li>
                        INSCRIPCIÓN: La cantidad de Q. {{$cuota_inscripcion->cuota}}.
                    </li>
                    <li>
                        COLEGIATURA MENSUAL DE LOS MESES DE ENERO A OCTUBRE, la cantidad de Q.{{$cuota_colegiatura->cuota}}.
                    </li>
                    <li>
                        Cuotas debidamente autorizadas por el Ministerio de Educación, mediante resolución número {{$inscripcion->grado_nivel_educativo->nivelEducativo->resolucion}} de fecha {{\Carbon\Carbon::createFromDate($inscripcion->grado_nivel_educativo->nivelEducativo->fecha)->isoFormat('DD-MM-YYYY')}}.
                    </li>
                </ol>
            </p>
            <p>
                El pago de las cuotas se efectuará sin necesidad de cobro ni requerimiento alguno. Para el pago de las cuotas ambas partes acordamos que sea en forma: a) Anticipada --- b) Vencido , debiendo efectuar el pago a más¿ tarde el día 3 de cada mes.

                <br /><br />
                <b>SEPTIMA: DE LOS CHEQUES RECHAZADOS.</b>
                <br />
                Por concepto de cheques rechazados en el centro educativo podrá cobrar como máximo el valor que por tal motivo debita o cobra el BANCO que rechazó el mismo. El monto a cobrar no puede ser desproporcionado.
                <br /><br />

                <b>OCTAVA: DE LA INSOLVENCIA EN LOS PAGOS:</b>
                <br />
                En caso de que el padre de familia o representante legal del alumno se atrase o incumpla en los pagos normales en la cláusula SEXTA del presente contrato, dará lugar al cobro de interés por mora, los cuales serán fijados de conformidad con las tasa de interés legal. Si el centro educativo adopta el pago anticipado, el cobro de mora será permitido siempre y cuando se hayan dejado de efectuar los pagos por uno o más meses.
                <br /><br />

                <b>NOVENA: DEL RETIRO DEL ALUMNO.</b>
                <br />
                El padre de familia o representante legal del aluno, en todo caso quien haya sido el firmante del presente contrato, puede retirar al alumno en forma definitiva, y para tal efecto deberá: a) Enviar aviso por escrito al centro educativo; y b) Pagar la cuota mensual hasta el mes en que efectivamente sea retirado el alumno del plantel educativo.
                <br /><br />
                <b>DECIMA: DE LOS DERECHOS Y OBLIGACIONES DEL CENTRO EDUCATIVO.</b>
                <br />
                El centro educativo tendrá derecho a percibir las ganancias o utilidades que por la prestación del servicio corresponda; así mismo, exigir al padre de familia o representante legal del alumno, el cumplimiento del presente contrato, y también podrá, cuando sea necesario, acudir a los organismos administrativos o judiciales para la solución de conflictos que surjan por la prestación del servicio contratado.
                <br /><br />

                <b>DECIMA PRIMERA: DE LA COPIA DEL CONTRATO.</b>
                <br />
                Del presente contrato queda el original en poder del padre de familia o representante legal del alumno y una copia fiel digital al centro educativo, esto con el propósito de que cada parte esté bien enterada de sus derechos y obligaciones, las ejercite y cumpla de conformidad con lo establecido en el presente contrato y en el ordenamiento jurídico vigente aplicable. La copia original será entregada al padre de familia o representante legal del alumno al momento de firmal el presente contrato.
                <br /><br />
                <b>DECIMA SEGUNDA: DE LA ACEPTACIÓN.</b>
                <br />
                Ambas partes aceptadas todas y cada una de las cláusulas que contiene el presente contrato. Nosotros los comparecientes damos lectura íntegra al presente contrato, y enterados de su contenido, objeto, validez y demás efectos legales, lo ratificamos, aceptamos y firmamos.
            </p>
            <p>
                <br /><br />
                f.)______________________________________<span style="position:absolute;left:150px;">f.)______________________________________</span>
                <br /><span style="position:absolute;left:25px;">Padre de familia y/o Representante legal</span> <span style="margin-left:215px;position:absolute;left:215px;">Representante del colegio</span>
            </p>
        </div>
    </main>

    <footer>

    </footer>
</body>