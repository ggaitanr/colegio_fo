import Axios from 'axios'
import moment from 'moment'
import store from '../../../store/index'


export default {

    alumnoName(data) {
        Object.keys(data).forEach(function(key) {
            if (data[key] === null) {
                data[key] = '';
            }
        })

        return data.primer_nombre + ' ' + data.segundo_nombre + ' ' + data.tercer_nombre + ' ' + data.primer_apellido + ' ' + data.segundo_apellido
    },

    //funcion para imprimir
    print(inscripciones) {
        let self = this

        //setear header table with columns
        var headers = {
            fila_0: {
                col_1: { text: 'Numero inscripcion', style: 'tableHeader', alignment: 'center' },
                col_2: { text: 'Fecha inscripcion', style: 'tableHeader', alignment: 'center' },
                col_3: { text: 'Ciclo escolar', style: 'tableHeader', alignment: 'center' },
                col_4: { text: 'Alumno', style: 'tableHeader', alignment: 'center' },
                col_5: { text: 'Grado', style: 'tableHeader', alignment: 'center' }
            }
        }


        var body = [];
        for (var key in headers) {
            if (headers.hasOwnProperty(key)) {
                var header = headers[key]
                var row = new Array()
                row.push(header.col_1)
                row.push(header.col_2)
                row.push(header.col_3)
                row.push(header.col_4)
                row.push(header.col_5)
                body.push(row);
            }
        }

        for (var key in inscripciones) {
            if (inscripciones.hasOwnProperty(key)) {
                var data = inscripciones[key]

                var row = new Array()
                row.push(data.numero)
                row.push(moment(data.fecha).format('DD/MM/YYYY'))
                row.push(data.ciclo.ciclo)
                row.push(self.alumnoName(data.alumno))
                row.push(data.grado_nivel_educativo.grado.nombre + ' ' + data.grado_nivel_educativo.nivel_educativo.nombre)

                body.push(row);
            }
        }

        return {
            body: body,
            widths: ['*', '*', '*', '*', '*']
        }
    }
}