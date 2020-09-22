import Axios from 'axios'
import moment from 'moment'
import store from '../../store/index'

export default {
    items: [],

    alumnoName(data) {
        Object.keys(data).forEach(function(key) {
            if (data[key] === null) {
                data[key] = '';
            }
        })

        return data.primer_nombre + ' ' + data.segundo_nombre + ' ' + data.tercer_nombre + ' ' + data.primer_apellido + ' ' + data.segundo_apellido
    },

    getEdad(date) {
        return moment().diff(date, 'years');
    },

    getApoderado(data) {
        if (data !== null) {
            Object.keys(data.apoderado).forEach(function(key) {
                if (data.apoderado[key] === null) {
                    data.apoderado[key] = '';
                }
            })
            return data.apoderado.primer_nombre + ' ' + data.apoderado.segundo_nombre + ' ' + data.apoderado.primer_apellido + ' ' + data.apoderado.segundo_apellido
        } else {
            return 'sin responsable actual'
        }

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
                col_3: { text: 'Edad', style: 'tableHeader', alignment: 'center' },
                col_4: { text: 'Dirección', style: 'tableHeader', alignment: 'center' },
                col_5: { text: 'Reponsable actual', style: 'tableHeader', alignment: 'center' }
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
                row.push(header.col_4)
                row.push(header.col_5)
                body.push(row);
            }
        }

        for (var key in alumnos) {
            if (alumnos.hasOwnProperty(key)) {
                var data = alumnos[key]

                var row = new Array()
                row.push(data.codigo)
                row.push(self.alumnoName(data))
                row.push(self.getEdad(data.fecha_nac))
                row.push(data.direccion)
                row.push(self.getApoderado(data.responsable))
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
                            text: store.state.institucion.definicion+' '+store.state.institucion.nombre +'\nREPORTE DE ALUMNOS REGISTRADOS ',
                            width: '*',
                            alignment: 'center'
                        },
                        {
                            text: 'TOTAL INSCRIPCIONES: ' + alumnos.length,
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
                        widths: [50, '*', 50, '*', '*'],
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

        //pdfMake.createPdf(docDefinition).open()
        pdfMake.createPdf(docDefinition).download('lista_alumnos')
    }
}