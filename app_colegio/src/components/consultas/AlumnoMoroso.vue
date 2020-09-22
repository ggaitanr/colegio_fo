<template>
  <v-layout align-start>
    <v-flex>
      <v-toolbar flat color="white">
        <v-tooltip top v-if="items.length > 0">
            <template v-slot:activator="{ on }">
              <v-btn small @click="printReporte" v-on="on"><v-icon>print</v-icon></v-btn>
            </template>
            <span>Imprimir pdf</span>
        </v-tooltip>
        <v-toolbar-title>Alumnos morosos </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar"
            single-line
            hide-details
          ></v-text-field>
      </v-toolbar>
      <v-data-table
                :headers="headers"
                :items="items"
                :search="search"
                :expand="false"
                class="elevation-1"
                disable-initial-sort
                item-key="codigo"
                :rows-per-page-items="[10,25]"
            >
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-left">
                            <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                        {{ props.item.codigo }}</td>
                        <td class="text-xs-left">{{props.item.alumno}}
                        </td>
                    </tr>
                
                </template>
                <template v-slot:expand="props">
                    <v-card flat>
                        <el-card class="box-card">
                            <div v-for="o in props.item.detalle" :key="o.descripcion" class="text-moroso item">
                                {{'.- ' + o.descripcion }}
                            </div>
                        </el-card>
                    </v-card>
                </template>
                <template v-slot:no-data>
                <v-btn color="primary" @click="getAll">Reset</v-btn>
                </template>
            </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import print from '../reportes/alumnos_morosos'
export default {
  name: "AlumnosMorosos",
  props: {
      source: String
    },
  data() {
    return {
      search: '',
      items: [],
      conceptos: [],
      ciclos: [],
      alumnos: [],
      headers: [
        { text: 'Codigo', value: 'codigo' },
        { text: 'Nombre completo', value: 'alumno' }
      ],
    };
  },

  created() {
    let self = this

    var promises = [
        self.getConceptos(),
        self.getAlumnos()
    ];

    Promise.all(promises).then(results => {
        self.getAlumnosMorosos()
    })
  },

  methods: {
    getConceptos() {
      let self = this
      self.loading = true

      return new Promise(function(resolve, reject) {
        self.$store.state.services.conceptoPagoService
        .getAll()
        .then(r => {
            self.loading = false
            self.conceptos = r.data.filter(x=>x.obligatorio)
            resolve(self.conceptos)
        })
        .catch(r => {
            reject(r)
        });
      })
    },

    getPagos() {
      let self = this
      self.loading = true

      return new Promise(function(resolve, reject) {
        self.$store.state.services.pagoService
        .getAll()
        .then(r => {
            self.loading = false
            r.data = r.data.filter(x=>!x.anulado)
            self.pagos = r.data
            resolve(self.pagos)
        })
        .catch(r => {
            reject(r)
        });
      })
    },

    getCiclos() {
      let self = this
      self.loading = true
      return new Promise(function(resolve, reject) {
        self.$store.state.services.cicloService
        .getAll()
        .then(r => {
            self.loading = false
            self.ciclos = r.data
            resolve(self.ciclos)
        })
        .catch(r => {
            reject(r)
        });
      })
    },

    getAlumnos() {
      let self = this
      self.loading = true

      return new Promise(function(resolve, reject) {
        self.$store.state.services.alumnoService
        .getHistorial()
        .then(r => {
            self.loading = false
            self.alumnos = r.data
            resolve(self.alumnos)
        })
        .catch(r => {
            reject(r)
        });
      })
    },

    //funcion para llenar array y objeter alumnos morosos
    getAlumnosMorosos(){
        let self = this
        self.alumnos.forEach(alumno => {
          var detalle = []
          self.conceptos.forEach(concepto => {
            alumno.inscripciones.forEach(inscripcion => {
              var m = self.verifyIsMoroso(concepto,inscripcion)
              if(m !== ''){
                  detalle.push({descripcion: m})
              }
            });
             //m !== "" ? moroso = moroso+', '+m : ''
          })
          var parciales = self.verityIfPagoParcial(alumno.inscripciones)
          detalle = [...detalle, ...parciales]
          if(detalle.length > 0){
            self.items.push({codigo: alumno.codigo, alumno: self.getName(alumno),detalle: detalle})
          }
        })
    },

    verifyIsMoroso(concepto,inscripcion){
        let self = this
        var debe = ''
        var existe = inscripcion.pagos.some(x=>x.cuota.concepto_pago.id === concepto.id)
        if(concepto.forma_pago === "A"){
          if(!existe){
            debe = debe+ ' debe '+concepto.nombre + ' por ciclo escolar '+inscripcion.ciclo.ciclo
          }
        }else{
            if(concepto.forma_pago === "M"){
                var meses = inscripcion.pagos.filter(x=>x.cuota.concepto_pago.id === concepto.id)
                var debe_mes = self.verifyIfPagoMonths(meses,inscripcion.ciclo, concepto.mora)
                if(debe_mes !== ""){
                    debe = "debe por concepto de "+concepto.nombre+' el mes de '+debe_mes+' correspondiente al ciclo escolar '+ inscripcion.ciclo.ciclo
                }
            }
        }
        return debe
    },

    verityIfPagoParcial(inscripciones){
        let self = this
        var detalle = []
        inscripciones.forEach(inscripcion=>{
            var pagos = inscripcion.pagos.filter(x=>!x.pagado)
            pagos.forEach(pago=>{
                var factura = self.formatCode(pago.factura,String(pago.serie.no_facturas).length)
                detalle.push({descripcion: 'debe de pago al credito, factura no '+pago.serie.serie+'-'+factura + '  la cantidad de Q '
                            +  (pago.total - pago.total_cancelado).toFixed(2)+' por concepto de '+pago.cuota.concepto_pago.nombre+ ', correspondiente al ciclo escolar '+inscripcion.ciclo.ciclo})
            })
        })

        return detalle
    },

    //veriricar si pago todos los meses
    verifyIfPagoMonths(pago_meses,ciclo, mora){
        let self = this
        if(ciclo.ciclo > moment().year()){
          return ''
        }
        var meses = self.monthsOfDate(ciclo.inicio, ciclo.fin)
        if(ciclo.ciclo == moment().year()){
          var date_mora = moment(ciclo.ciclo+'-'+(moment().format('M'))+'-05')
          if(mora && moment() > date_mora){
            meses = meses.filter(x=>x.id <= moment().format('M'))
          }else{
            meses = meses.filter(x=>x.id <= moment().month())
          }
        }
        var meses_pagados = []
        pago_meses.forEach(pago => {
            meses_pagados = [...meses_pagados, ...pago.pagos_meses]
        })
        
        meses_pagados.forEach(mes => {
            meses = meses.filter(x=>x.id !== mes.mes_id)
        })

        
        
        return meses.map(item => item.mes).join(', ')  
    },

    monthsOfDate(startDate,endDate){
        let self = this
        startDate = moment(startDate)
        endDate = moment(endDate)
        var meses = []
        while (startDate.isBefore(endDate)) {
            var id = parseInt(startDate.format("M"))
            var mes = moment().month(id - 1).format('MMMM')
            meses.push({
                id: id,
                mes: mes
            })

            startDate.add(1, 'month')
        }
        return meses
    },

    getName(data){
        Object.keys(data).forEach(function(key) {
            if (data[key] === null) {
                data[key] = '';
            }
        })
        var pn = data.primer_nombre
        var sn = data.segundo_nombre
        var tn = data.tercer_nombre
        var pa = data.primer_apellido
        var sa = data.segundo_apellido
        return pn+' '+sn+' '+tn+' '+pa+' '+sa
    },

    //formatear codigo para tarjeta de reponsabilidades
    formatCode(n, len = 4) {
        return (new Array(len + 1).join('0') + n).slice(-len)
    },

    printReporte(){
      let self = this
      print.print(self.items)
    },
  },

  computed: {
    },
};
</script>

<style>
  .text-moroso {
    font-size: 14px;
    color: red;
  }

  .item {
    padding: 5px 0;
  }

  .box-card {
    width: 100%;
  }
</style>