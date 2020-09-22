
import Axios from 'axios'
import moment from 'moment'

export default {
    print(){
        let self = this

        var pdfMake = require('pdfmake/build/pdfmake.js')

        if (pdfMake.vfs == undefined){
        var pdfFonts = require('pdfmake/build/vfs_fonts.js')
        pdfMake.vfs = pdfFonts.pdfMake.vfs;
        }

        var docDefinition = {
            //pageOrientation: 'portraint',
            pageSize: "A4",

            header: function() {
                return {
                    columns: [
                        {
                            //image: imagen,
                            //width: 100,
                            //height: 50
                        },
                        {
                            text: '\nCOLEGIO DE CIENCIAS COMERCIALES "SAN JOSE"',
                            //width: 400,
                            alignment: 'center'
                        },
                        {

                        }
                    ]
                }
            },

            footer: function(currentPage, pageCount) {
                return { text:'Pagina '+ currentPage.toString() + ' de ' + pageCount, alignment: 'center',margin:[0,30,0,0] };
            },

            content: [
                {

                }
            ],

            styles: {
                header: {
                    fontSize: 12,
                    bold: false
                },
                myStyle:
                {   
                    alignment: 'justify'
                },
                tableStyle: {
                    fontSize: 9
                },
            }
        }

        pdfMake.createPdf(docDefinition).open()
    },
  }