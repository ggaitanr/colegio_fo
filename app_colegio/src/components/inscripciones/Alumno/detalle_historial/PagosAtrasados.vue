<template>
  <v-layout align-start v-loading="loading">
    <v-flex>

      <v-data-table
            :headers="headers"
            :items="items"
            hide-actions
            class="elevation-1"
        >
        <template v-slot:items="props">
            <td class="text-xs-left">{{props.item.descripcion}}</td>
            <td class="text-xs-left">{{props.item.cantidad | currency('Q ')}}</td>
            </template>
            <template slot="no-data">
                <h3>No existen pagos atrasados.</h3>
            </template>
        </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
import ProgressBar from 'vue-simple-progress'
import moment from 'moment'
export default {
  name: "inscripciones",
  components: {
    ProgressBar
  },
  props: {
      source: String
    },
  data() {
    return {
      search: '',
      loading: false,
      menu: false,
      date: null,
      items: [],
      cuotas: [],
      alumno: null,
      headers: [
        { text: 'Descripción', value: 'descripcion'},
        { text: 'Cantidad', value: 'cantidad'}
      ],
    };
  },

  created() {
    let self = this
    events.$on('historial_academico_pagos_atrasados',self.onEventAcademico)
  },

  beforeDestroy(){
      let self = this
      events.$off('historial_academico_pagos_atrasados',self.onEventAcademico)
  },
  

  methods: {
    onEventAcademico(pagos, inscripciones) {
      let self = this
      pagos = pagos.filter(x=>!x.anulado)
      self.pagos = pagos
      self.inscripciones = inscripciones
      self.getCuotas()
    },

    getCuotas() {
      let self = this
      self.loading = true
        self.$store.state.services.conceptoPagoService
        .getAll()
        .then(r => {
            self.loading = false
            self.conceptos = r.data.filter(x=>x.obligatorio)
            self.getPagosAtrasados()
        })
        .catch(r => {
        });
    },

    //funcion para llenar array y objeter alumnos morosos
    getPagosAtrasados(){
        let self = this
        self.conceptos.forEach(concepto => {
          self.inscripciones.forEach(inscripcion => {
            var m = self.verifyIsMoroso(concepto,inscripcion)
            if(m !== ""){
                self.items.push({
                    descripcion: m.descripcion,
                    cantidad: m.cantidad
                })
            }
          });
        });

        var saldo = self.totalSaldo(self.items)
        self.$nextTick(() => {  
          events.$emit('update_saldo', saldo)
        });
    },

    verifyIsMoroso(concepto, inscripcion){
        let self = this
        var debe = ''
        var cantidad = 0
        //var existe = pagos.some(x=>x.cuota.concepto_pago.id === concepto.id)
        var cuota = concepto.cuotas.find(x=>x.grado_nivel_educativo_id === inscripcion.grado_nivel_educativo_id && x.ciclo_id == inscripcion.ciclo_id)
        var pagos = self.pagos.filter(x=>x.inscripcion_id == inscripcion.id)
        var pago = pagos.find(x=>x.cuota.concepto_pago.id === concepto.id)
        var meses_ciclo = self.monthsOfDate(inscripcion.ciclo.inicio, inscripcion.ciclo.fin)

        if(concepto.forma_pago === 'A'){
          if(_.isEmpty(pago)){
            debe = 'debe por concepto de '+concepto.nombre + ' por ciclo escolar '+inscripcion.ciclo.ciclo
            cantidad = cuota.cuota
          }
        }else{
            if(concepto.forma_pago === "M"){
                var meses = pagos.filter(x=>x.cuota.concepto_pago.id === concepto.id)
                var debe_meses = self.verifyIfPagoMonths(meses, inscripcion.ciclo, concepto.mora)
                if(debe_meses.length > 0){
                    var d_m = debe_meses.map(item => item.mes).join(', ')
                    debe = "debe por concepto de "+concepto.nombre+' el mes de '+d_m+' correspondiente al ciclo escolar '+ inscripcion.ciclo.ciclo
                    cantidad = debe_meses.length * cuota.cuota
                }
            }
        }
        if(debe !== '' && cantidad > 0){
            return {descripcion: debe, cantidad: cantidad }
        }
        return ''
    },

    //veriricar si pago todos los meses
    verifyIfPagoMonths(pago_meses,ciclo,mora){
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
        
        return meses  
    },

    //obtener los meses del ciclo lectivo
     monthsOfDate(startDate,endDate){
        let self = this
        startDate = moment(startDate);
        endDate = moment(endDate);
        var meses = [];    
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

    totalSaldo(data) {
        var total = data.reduce(function(a, b) {
            return a + parseFloat(b.cantidad)
        }, 0);

        return total
    },
    
    
  },

  computed: {
    },
};
</script>

