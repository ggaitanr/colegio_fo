import Axios from 'axios'
import moment from 'moment'
import store from '../../store/index'

export default {

    getTotal(data) {
        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.total)
        }, 0);

        return total
    },

    print(conceptos, resumen, ciclo, total) {
        let self = this
        console.log(total)
        var pdfMake = require('pdfmake/build/pdfmake.js')

        if (pdfMake.vfs == undefined) {
            var pdfFonts = require('pdfmake/build/vfs_fonts.js')
            pdfMake.vfs = pdfFonts.pdfMake.vfs;
        }

        var body = [] //cuerpo de la tabla que se llena con arrays
        var body2 = [] //cuerpo de la tabla que se llena con arrays

        body.push([{ text: 'Resumen por conceptos de pago', style: 'tableHeader', colSpan: 2, alignment: 'center' }, {}])


        for (var key in conceptos) {
            if (conceptos.hasOwnProperty(key)) {
                var data = conceptos[key]

                var row = new Array()

                row.push(data.nombre)
                row.push(store.state.global.formatPrice(data.total))
                body.push(row);
            }
        }

        body.push([{ text: 'TOTAL: ' + store.state.global.formatPrice(self.getTotal(conceptos)), style: 'tableFooter', colSpan: 2, alignment: 'right' }, {}])

        body2.push([{ text: 'Resumen completo', style: 'tableHeader', colSpan: 2, alignment: 'center' }, {}])
        for (var key in resumen) {
            if (resumen.hasOwnProperty(key)) {
                var data = resumen[key]

                var row = new Array()

                row.push(data.text)
                row.push(store.state.global.formatPrice(data.total))
                body2.push(row);
            }
        }

        body2.push([{ text: 'TOTAL: ' + store.state.global.formatPrice(self.getTotal(resumen)), style: 'tableFooter', colSpan: 2, alignment: 'right' }, {}])

        var docDefinition = {
            pageMargins: [40, 100, 40, 55],
            pageOrientation: 'landscape',
            pageSize: "C4",

            footer: function(currentPage, pageCount) {
                return { text: 'Pagina ' + currentPage.toString() + ' de ' + pageCount, alignment: 'center', margin: [0, 30, 0, 0] };
            },

            content: [{
                    style: 'header',
                    margin: [0, -80, 0, 0],
                    columns: [{
                            image: store.state.global.getLogo(),
                            height: 60,
                            alignment: 'center',
                            width: 100,
                        },
                        {
                            text: store.state.institucion.definicion+' '+store.state.institucion.nombre +'\nREPORTE DE CICLO ESCOLAR ' + ciclo.ciclo,
                            width: '*',
                            alignment: 'center'
                        },
                        {
                            text: 'TOTAL PAGOS: ' + store.state.global.formatPrice(total),
                            alignment: 'justify',
                            bold: true,
                            width: 'auto'
                        }
                    ]
                },

                '\n\n',
                {
                    columns: [{
                            style: 'tableExample',
                            table: {
                                widths: ['*', '*'],
                                // keepWithHeaderRows: 1,
                                body: body
                            },
                        },
                        {
                            style: 'tableExample',
                            table: {
                                widths: ['*', '*'],
                                // keepWithHeaderRows: 1,
                                body: body2
                            }
                        }
                    ]
                },
            ],

            styles: {
                header: {
                    fontSize: 12,
                    bold: true,
                    alignment: 'center',
                    margin: [0, 0, 0, -40]
                },

                tableExample: {
                    margin: [0, 0, 40, 0],
                    fontSize: 10,
                    alignment: 'center'
                },
                tableHeader: {
                    bold: true,
                    fontSize: 9,
                    color: 'black',
                    fillColor: '#a1dae7'
                },
                tableFooter: {
                    bold: true,
                    fontSize: 12,
                    color: 'black',
                    fillColor: '#a1dae7'
                }
            }
        }

        pdfMake.createPdf(docDefinition).download('pagos_' + ciclo.ciclo)
    }
}