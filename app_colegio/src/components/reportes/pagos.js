import Axios from 'axios'
import moment from 'moment'
import store from '../../store/index'

export default {
    print(reportes = [], inicio, fin, ciclo = null) {
        let self = this
        var c = ciclo !== null ? 'CICLO ESCOLAR ' + ciclo.ciclo : ''
        var pdfMake = require('pdfmake/build/pdfmake.js')

        if (pdfMake.vfs == undefined) {
            var pdfFonts = require('pdfmake/build/vfs_fonts.js')
            pdfMake.vfs = pdfFonts.pdfMake.vfs;
        }

        var desde = inicio !== '' ? moment(inicio).format('DD/MM/YYYY') : ''
        var hasta = fin !== '' ? moment(fin).format('DD/MM/YYYY') : ''


        var content = []
        reportes.forEach(function(rep, index) {
            var page_break = index + 1 < reportes.length ? 'after' : ''
            content.push({
                    style: 'header',
                    margin: [-20, -80, 0, 0],
                    columns: [{
                            image: store.state.global.getLogo(),
                            height: 60,
                            alignment: 'center',
                            width: 100,
                        },
                        {
                            text: store.state.institucion.definicion+' '+store.state.institucion.nombre +'\n REPORTE DE PAGOS AL ' + rep.nombre + ' ' + c,
                            width: '*',
                            alignment: 'center'
                        },
                        {
                            style: 'tableResume',
                            table: {
                                widths: ['auto', 'auto'],
                                body: rep.table.resumen
                            },
                            alignment: 'justify',
                            margin: [0, 0, -20, 0],
                            bold: true,
                            width: 'auto'
                        }
                    ]
                },

                '\n',
                (inicio != '' && fin !== '') ? 'Informe de pagos del ' + desde + ' al ' + hasta + '\n\n' : 'Informe del total de pagos\n\n',

                {
                    style: 'tableExample',
                    table: { widths: rep.table.widths, body: rep.table.body },
                    pageBreak: page_break
                }
            )
        })


        var docDefinition = {
            pageMargins: [40, 100, 40, 55],
            pageOrientation: 'landscape',
            pageSize: "C4",

            footer: function(currentPage, pageCount) {
                return { text: 'Pagina ' + currentPage.toString() + ' de ' + pageCount, alignment: 'center', margin: [0, 30, 0, 0] };
            },

            content: content,

            styles: {
                header: {
                    fontSize: 12,
                    bold: true,
                    alignment: 'center',
                    margin: [0, 0, 0, -40]
                },

                tableExample: {
                    margin: [-20, 0, -20, 0],
                    fontSize: 10,
                    alignment: 'center'
                },
                tableResume: {
                    fontSize: 9,
                    bold: true,
                    fillColor: '#a1dae7'
                },
                tableHeader: {
                    bold: true,
                    fontSize: 9,
                    color: 'black',
                    fillColor: '#a1dae7'
                }
            }


        }

        pdfMake.createPdf(docDefinition).download('pagos_' + c)
    }
}