<template>
  <v-layout align-start>
    <v-dialog persistent v-model="dialog" max-width="800px">
          <v-card>
            <v-card-title>
              <span class="headline" v-if="pago !== null">Anular pago {{pago.factura}} por concepto de {{pago.cuota.concepto_pago.nombre}}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm12 md12>
                    <v-textarea
                        placeholder="motivo anular pago"
                        prepend-icon="highlight_off"
                        v-model="form.motivo_anulado"
                        v-validate="'required|min:10'"
                        type="text"
                        data-vv-name="motivo"
                        data-vv-as="motivo anulado"
                        :error-messages="errors.collect('motivo')"
                        label="Motivo (Especifique)"
                        ></v-textarea>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="validate">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

    <v-flex>
        <v-layout row wrap>
          
            <v-flex sm12 lg12 md12>
                <v-data-table
                    :headers="headers"
                    :items="items"
                    :search="search"
                    class="elevation-1"
                >
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{'Serie "'+ props.item.serie.serie+'" - factura no.'+getFormat(props.item)}}</td>
                        <td class="text-xs-left">{{ props.item.cuota.concepto_pago.nombre}}</td>
                        <td class="text-xs-left">{{ props.item.mora | currency('Q ')}}</td>
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
                        <v-tooltip top v-if = "props.item.is_credito">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" color="success"  @click="pagosParciales(props.item)"> payment</v-icon>
                            </template>
                            <span>abonar a pago</span>
                        </v-tooltip>
                        <v-tooltip top v-if="!props.item.anulado">
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on" color="red" @click="anularPago(props.item)"> block</v-icon>
                            </template>
                            <span>anular pago</span>
                        </v-tooltip>
                        </td>
                        </template>
                        <template slot="no-data">
                            <h3>No existen pagos realizados.</h3>
                        </template>
                    </v-data-table>
            </v-flex>
        </v-layout>
    </v-flex>

    <el-drawer
      title="PAGOS PARCIALES"
      :visible.sync="drawer"
      :direction="direction"
      size="70%"
      :before-close="handleClose">
        <v-container>
          <detalle-pago></detalle-pago>
        </v-container>
    </el-drawer>
  </v-layout>
</template>

<script>
import moment from 'moment'
import detallePago from './pagosParciales/Detalle'
import ProgressBar from 'vue-simple-progress'
export default {
  name: "detalle_pago",
  components: {
    detallePago,
    ProgressBar
  },
  props: {
      source: String
    },
  data() {
    return {
        loading: true,
        alumno:null,
        search: '',
        dialog: false,
        dialog_factura: false,
        inscripcion: null,
        pago: null,
        items: [],
        headers: [
        { text: '# Serie - Factura', value: 'factura' },
        { text: 'Concepto pago', value: 'concepto_pago' },
        { text: 'Mora', value: 'mora' },
        { text: 'Monto', value: 'monto' },
        { text: 'Total cancelado', value: 'total_cacnelado' },
        { text: 'Forma pago', value: 'is_credito' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      drawer: false,
      direction: 'rtl',
      form:{
        id: null,
        motivo: ''
      }
    };
  },

  created() {
    let self = this
    events.$on('update_pagos',self.onEventPagos)
    events.$on('close_factura',self.onEventClose)
  },

  beforeDestroy(){
    let self = this
    events.$off('update_pagos',self.onEventPagos)
  },

  methods: {
      onEventPagos(inscripcion, pagos, obligatorio = 1){
          let self = this
          self.inscripcion = inscripcion
          pagos = pagos.filter(x=>x.cuota.concepto_pago.obligatorio == obligatorio)
          self.items = pagos
      },
      onEventClose(){
        let self = this
        self.dialog_factura = false
      },

      handleClose(done) {
        let self = this
        self.$confirm('Esta seguro de salir de detalle de pagos parciales?')
          .then(_ => {
            done()
            events.$emit('get_pagos_ciclo',self.inscripcion)
            //self.getPagos(self.inscripcion.id)
          })
          .catch(_ => {});
      },

      getPagos(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.inscripcionService
        .getPagos(id)
        .then(r => {
            self.loading = false
            self.items = r.data
        }).catch(r => {});
    },

    pagosParciales(data){
      let self = this
      self.drawer = true
      self.pago = data
      self.$nextTick(()=>{
        events.$emit('get_pagos_parciales',data)
      })
    },

    close(){
      let self = this
      self.dialog = false
      self.form.motivo_anulado = ''
      self.$validator.reset()
    },

    //obtener valor para pago al credito
    getVal(item){
      let self = this
      var val = (parseFloat(item.total_cancelado) * 100) / parseFloat(item.total)
      return val.toFixed(2)
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

    closeFactura(){
      let self = this
      self.dialog_factura = false
    },

    anularPago(data){
      let self = this
      self.pago = data
      self.dialog = true
      self.form.id = data.id
    },

    //funcion, validar si se guarda o actualiza
    validate(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.update()
           }
      });

      let self = this
    },

    //funcion para anular registro
    update(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.pagoService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('pago anulado con éxito', 'éxito')
          self.close()
          events.$emit('get_pagos_ciclo',self.inscripcion)
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