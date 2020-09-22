<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-tooltip top v-if="items.length > 0">
            <template v-slot:activator="{ on }">
              <v-btn small @click="printReporte" v-on="on"><v-icon>print</v-icon></v-btn>
            </template>
            <span>Imprimir pdf</span>
        </v-tooltip>
        <v-toolbar-title v-if="ciclo !== null">Consulta pagos ciclo escolar {{ciclo.ciclo}} </v-toolbar-title>
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
                    :rows-per-page-items="[10,25]"
                    class="elevation-1"
                >
        <template v-slot:items="props">
            <td class="text-xs-left">{{'Serie "'+ props.item.serie.serie+'" - factura no.'+getFormat(props.item)}}</td>
            <td>{{ props.item.apoderado_name }}
          </td>
            <td>{{ props.item.alumno_name }}
          </td>
            <td class="text-xs-left">{{ props.item.cuota.concepto_pago.nombre}} {{getMeses(props.item.pagos_meses)}} </td>
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
import print from '../reportes/pagos'
import detalle from '../reportes/detalles/detalle_pago'
import ProgressBar from 'vue-simple-progress'
export default {
  name: "consulta_pago_ciclo",
  components: {
    ProgressBar
  },
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      loading: false,
      menu: false,
      date: null,
      items: [],
      ciclo: null,
      headers: [
        { text: '# Serie - Factura', value: 'numero' },
        { text: 'Nombres poderado', value: 'apoderado_name' },
        { text: 'Nombres alumno', value: 'alumno_name' },
        { text: 'Concepto pago', value: 'concepto_pago.nombre' },
        { text: 'Total a pagar', value: 'total' },
        { text: 'Total cancelado', value: 'total_cacnelado' },
        { text: 'Forma pago', value: 'is_credito' },
        { text: 'Acciones', value: '', sortable: false }
      ],
    };
  },

  created() {
    let self = this
    events.$on('get_ciclo_pagos',self.onEventCiclo)
  },

  beforeDestroy(){
      let self = this
      events.$off('get_ciclo_pagos',self.onEventCiclo)
  },

  methods: {
    onEventCiclo(ciclo){
        let self = this
        self.ciclo = ciclo
        self.getAll(ciclo.id)
    },
    getAll(id) {
      let self = this
      self.loading = true
      self.$store.state.services.cicloService
        .getPagos(id)
        .then(r => {
          self.loading = false
          self.items = r.data
          self.items.forEach(function(item){
              item.alumno_name = self.getName(item.inscripcion.alumno,true)
              item.apoderado_name = self.getName(item.apoderado)
          })
        })
        .catch(r => {});
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

    printReporte(){
      let self = this
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

    getMeses(data){
        return data.map(item => item.mes.mes).join(', ')
    },

     printReporte(){
      let self = this
      var contado = self.items.filter(x=>!x.is_credito) 
      var credito = self.items.filter(x=>x.is_credito) 
      var data = []

      var table_c = detalle.print(contado)
      data.push({
        nombre: 'AL CONTADO',
        table: table_c
      })

      var table_cr = detalle.print(credito)
      data.push({
        nombre: 'AL CREDITO',
        table: table_cr
      })
      print.print(data,self.inicio,self.fin,self.ciclo)
    },
    
  },

  computed: {
    },
};
</script>

