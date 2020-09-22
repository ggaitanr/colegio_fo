<template>
  <v-layout align-start v-loading="loading">
    <v-flex>

      <v-data-table
            :headers="headers"
            :items="items"
            class="elevation-1"
            :rows-per-page-items="[10,25]"
        >
        <template v-slot:items="props">
            <td class="text-xs-left">{{'Serie "'+ props.item.serie.serie+'" - factura no.'+getFormat(props.item)}}</td>
            <td class="text-xs-left">{{ props.item.cuota.concepto_pago.nombre}}</td>
            <td class="text-xs-left">{{ props.item.total | currency('Q ')}}</td>
            <td class="text-xs-left">{{ props.item.total_cancelado | currency('Q ')}}</td>
            <td class="text-xs-left">{{ props.item.is_credito ? 'Al credito' : 'Al contado'}} 
                <label v-if="props.item.anulado" class="red--text"> (Anulado)</label>
                <progress-bar v-if="props.item.is_credito" :val="getVal(props.item)" :text="getVal(props.item)+'% cancelado'" />
            </td>
            <td class="text-xs-left">
                <v-tooltip top v-if="props.item.pagado">
                <template v-slot:activator="{ on }">
                    <v-icon color="blue" v-on="on" @click="imprimirFactura(props.item)"> print</v-icon>
                </template>
                <span>imprimir factura</span>
            </v-tooltip>
            </td>
            </template>
            <template slot="no-data">
                <h3>No existen pagos realizados.</h3>
            </template>
        </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
import ProgressBar from 'vue-simple-progress'
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
      headers: [
        { text: '# Serie - Factura', value: 'factura', width: '3%' },
        { text: 'Concepto pago', value: 'concepto_pago' },
        { text: 'Total a pagar', value: 'monto' },
        { text: 'Total cancelado', value: 'total_cancelado' },
        { text: 'Forma pago', value: 'is_credito' },
        { text: 'Acciones', value: '', sortable: false }
      ],
    };
  },

  created() {
    let self = this
    events.$on('historial_academico_pagos',self.onEventAcademico)
  },

  beforeDestroy(){
      let self = this
      events.$off('historial_academico_pagos',self.onEventAcademico)
  },
  

  methods: {
    onEventAcademico(pagos, obligatorio) {
      let self = this
      pagos = pagos.filter(x=>x.cuota.concepto_pago.obligatorio == obligatorio)
      self.items = pagos
    },

     imprimirFactura(data){
      let self = this
      self.loading = true
      self.$store.state.services.pagoService
        .comprobante(data.id)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'pago_'+self.getFormat(data)); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    getFormat(item){
      let self = this
      var n = item.factura
      var len = String(item.serie.no_facturas).length
      return self.formatCode(n,len)
    },

     //formatear codigo para tarjeta de reponsabilidades
    formatCode(n, len = 4) {
        return (new Array(len + 1).join('0') + n).slice(-len)
    },

    //obtener valor para pago al credito
    getVal(item){
      let self = this
      var val = (parseFloat(item.total_cancelado) * 100) / parseFloat(item.total)
      return val.toFixed(2)
    },

    getName(data, tercer_nombre = false){
        let self = this
        return self.$store.state.global.getFullName(data, tercer_nombre)
    },
    
  },

  computed: {
    },
};
</script>

