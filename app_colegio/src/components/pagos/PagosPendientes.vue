<template>
  <v-layout align-start>
    <v-flex>
        <v-layout>
            <v-flex sm12 lg12 md12>  
                <v-data-table
                    :headers="headers"
                    :items="items"
                    :search="search"
                    class="elevation-1"
                    :rows-per-page-items="[10,25]"
                >
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{props.item.concepto_pago.nombre}}</td>
                        <td class="text-xs-left">{{props.item.cuota | currency('Q ')}}
                           {{props.item.concepto_pago.forma_pago == 'A' ? 'Anual' : 'Mensual' }}
                        </td>
                        <td class="text-xs-left">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-icon v-on="on" color="success" fab dark @click="openDialog(props.item)"> payment</v-icon>
                                </template>
                                <span>Pagar</span>
                            </v-tooltip>
                        </td>
                        </template>
                    </v-data-table>
            </v-flex>
        </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import ProgressBar from 'vue-simple-progress'

export default {
  name: "pagos_pendientes",
  components: {
      ProgressBar
  },
  props: {
      
    },
  data() {
    return {
        loading: true,
        inscripcion:null,
        responsable: null,
        search: '',
        items: [],
        pagos: [],
        headers: [
        { text: 'Concepto pago', value: 'concepto_pago' },
        { text: 'Cuota', value: 'monto' },
        { text: 'Acciones', value: '', sortable: false }
      ],
    };
  },

  created() {
    let self = this
    events.$on('get_cuotas',self.onEventCuotas)
  },

  beforeDestroy(){
      let self = this
      events.$off('get_cuotas',self.onEventCuotas)
  },

  methods: {
      onEventCuotas(inscripcion, pagos, responsable){
          let self = this
          self.reponsable = responsable
          self.pagos = pagos.filter(x=>!x.anulado)
          self.inscripcion = inscripcion
          inscripcion !== null ? self.getCuotas(inscripcion.grado_nivel_educativo_id, inscripcion.ciclo_id) : ''
      },

      filterCuotas(data){
        let self = this
        self.items = []
        data.forEach(item => {
            var pago = self.pagos.find(x=>x.cuota.id === item.id)
            if(_.isEmpty(pago)){
              self.items.push(item)
            }else{
              if(item.concepto_pago.forma_pago === "M"){
                    var meses = self.pagos.filter(x=>x.cuota.id === item.id)
                    var debe_meses = self.verifyIfPagoMonths(meses, self.inscripcion.ciclo)
                    if(debe_meses.length > 0){
                        self.items.push(item)
                    }
                }
            }
        })
    },

        //veriricar si pago todos los meses
    verifyIfPagoMonths(pago_meses,ciclo){
        let self = this
        var meses = self.monthsOfDate(ciclo.inicio, ciclo.fin)
        meses = meses.filter(x=>x.id <= moment().month())
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

      getCuotas(id,ciclo_id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.gradoNivelEducativoService
        .getCuotasCiclo(id,ciclo_id)
        .then(r => {
            self.loading = false
            if(r.data.length == 0){
                self.$toastr.error('no se han configurado los montos de las cuotas para ciclo escolar seleccionado, por favor vuelta y configure cuotas')
                self.$router.push('/asignar_cuota/'+ciclo_id+'-'+id)
                return
            }
            r.data = r.data.filter(x=>!!(x.concepto_pago.obligatorio))
            self.filterCuotas(r.data)
        }).catch(r => {});
    },

    openDialog(data){
        let self = this
        events.$emit('open_dialog_pago',self.inscripcion, self.pagos, data.id, self.reponsable)
    },
  },

  computed: {
    },
};
</script>
<style>
.item {
    padding: 5px 0;
  }
</style>