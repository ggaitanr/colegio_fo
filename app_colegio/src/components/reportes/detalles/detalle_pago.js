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

        var nombre = data.primer_nombre + ' ' + data.segundo_nombre + ' ' + data.tercer_nombre + ' ' + data.primer_apellido + ' ' + data.segundo_apellido

        return nombre.trim().replace(/\s\s+/g, ' ')
    },

    apoderadoName(data) {
        Object.keys(data).forEach(function(key) {
            if (data[key] === null) {
                data[key] = '';
            }
        })

        var nombre = data.primer_nombre + ' ' + data.segundo_nombre + ' ' + data.primer_apellido + ' ' + data.segundo_apellido
        return nombre.trim().replace(/\s\s+/g, ' ')
    },

    getTotalPagos(data) {
        data = data.filter(x => !x.anulado)

        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.total_cancelado)
        }, 0);

        return total
    },

    getTotalPagosAdeudo(data) {
        data = data.filter(x => !x.anulado)

        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.total - b.total_cancelado)
        }, 0);

        return total
    },

    getStatus(data) {
        let self = this
        if (data.anulado) {
            return {
                value: 'anulado',
                color: 'red'
            }
        }
        if (data.pagado) {
            return {
                value: 'pagado',
                color: 'green'
            }
        }
        if (data.is_credito) {
            return {
                value: 'Parcialmente pagado',
                color: 'black'
            }
        }
    },

    //funcion para imprimir
    print(pagos) {
        let self = this

        //setear header table with columns
        var headers = {
            fila_0: {
                col_1: { text: 'Serie factura', style: 'tableHeader', alignment: 'center' },
                col_2: { text: 'Apoderado', style: 'tableHeader', alignment: 'center' },
                col_3: { text: 'Nombre alumno', style: 'tableHeader', alignment: 'center' },
                col_4: { text: 'Concepto pago', style: 'tableHeader', alignment: 'center' },
                col_5: { text: 'Total a pagar', style: 'tableHeader', alignment: 'center' },
                col_6: { text: 'Total cancelado', style: 'tableHeader', alignment: 'center' },
                col_7: { text: 'Adeudo', style: 'tableHeader', alignment: 'center' },
                col_8: { text: 'Estado', style: 'tableHeader', alignment: 'center' }
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
                row.push(header.col_6)
                row.push(header.col_7)
                row.push(header.col_8)
                body.push(row);
            }
        }

        for (var key in pagos) {
            if (pagos.hasOwnProperty(key)) {
                var data = pagos[key]

                var row = new Array()
                row.push(data.serie.serie + '-' + store.state.global.formatCode(data.factura, String(data.serie.no_facturas).length))
                row.push(self.apoderadoName(data.apoderado))
                row.push(self.alumnoName(data.inscripcion.alumno))
                row.push(data.cuota.concepto_pago.nombre)
                row.push(store.state.global.formatPrice(data.total))
                row.push(store.state.global.formatPrice(data.total_cancelado))
                row.push(store.state.global.formatPrice(data.total - data.total_cancelado))
                row.push({ text: self.getStatus(data).value, color: self.getStatus(data).color })

                body.push(row);
            }
        }

        return {
            body: body,
            resumen: [
                ['Total pagos', pagos.length],
                ['Total pagado', store.state.global.formatPrice(self.getTotalPagos(pagos))],
                ['Total adeudo', store.state.global.formatPrice(self.getTotalPagosAdeudo(pagos))]
            ],

            widths: [40, '*', '*', '*', '*', '*', '*', 70]
        }
    }
}