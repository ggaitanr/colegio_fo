import Axios from 'axios'
import moment from 'moment'
import store from '../../store/index'

export default {

    getAdeudos(data) {
        var adeudos = []
        data.forEach(d => {
            adeudos.push(d.descripcion)
        })

        return adeudos
    },

    print(alumnos) {
        let self = this
        var pdfMake = require('pdfmake/build/pdfmake.js')

        if (pdfMake.vfs == undefined) {
            var pdfFonts = require('pdfmake/build/vfs_fonts.js')
            pdfMake.vfs = pdfFonts.pdfMake.vfs;
        }

        var headers = {
            fila_0: {
                col_1: { text: 'Codigo', style: 'tableHeader', alignment: 'center' },
                col_2: { text: 'Nombre', style: 'tableHeader', alignment: 'center' },
                col_3: { text: 'Descripcion', style: 'tableHeader', alignment: 'center' }
            }
        }

        var body = [] //cuerpo de la tabla que se llena con arrays

        for (var key in headers) {
            if (headers.hasOwnProperty(key)) {
                var header = headers[key]
                var row = new Array()
                row.push(header.col_1)
                row.push(header.col_2)
                row.push(header.col_3)
                body.push(row);
            }
        }

        for (var key in alumnos) {
            if (alumnos.hasOwnProperty(key)) {
                var data = alumnos[key]

                var row = new Array()

                row.push(data.codigo)
                row.push(data.alumno)
                row.push({
                    stack: [{
                        ul: self.getAdeudos(data.detalle)
                    }]
                })
                body.push(row);
            }
        }

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
                            text: store.state.institucion.definicion+' '+store.state.institucion.nombre + '\nREPORTE DE ALUMNOS MOROSOS ',
                            width: '*',
                            alignment: 'center'
                        },
                        {
                            text: 'TOTAL ALUMNOS: ' + alumnos.length,
                            alignment: 'justify',
                            bold: true,
                            width: 'auto'
                        }
                    ]
                },

                '\n\n',
                {
                    style: 'tableStyle',
                    table: {
                        widths: [50, 100, '*'],
                        // keepWithHeaderRows: 1,
                        body: body
                    },

                    layout: {
                        /*hLineColor: function (i, node) {
                            return (i === 0 || i===1 || i === node.table.body.length) ? 'black' : 'white';
                        }*/
                    }
                }
            ],

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
                tableHeader: {
                    bold: true,
                    fontSize: 9,
                    color: 'black',
                    fillColor: '#a1dae7'
                }
            }
        }

        pdfMake.createPdf(docDefinition).download('lista_alumnos_morosos')
    }
}