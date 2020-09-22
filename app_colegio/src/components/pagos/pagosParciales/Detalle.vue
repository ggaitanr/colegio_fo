<template>
  <v-layout align-start>

    <v-dialog persistent v-model="dialog" max-width="800px">
          <v-card>
            <v-card-title>
              <span class="headline">Anular abono comprobante no {{abono.id}} </span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md v-if="form.id">
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
        <v-card v-if="pago !== null">
            <v-card-title>
            <div>
                <h3 class="grey--text">Detalle pago</h3>
                <h4>por concepto de {{pago.cuota.concepto_pago.nombre}} </h4>
                <h4>por inscripcion {{pago.cuota.ciclo.ciclo}}</h4>
                <h3 v-if="pago.anulado" class="red--text"> ANULADO  </h3>
            </div>
            </v-card-title>
            <v-card-actions>
            <v-btn flat color="blue">total a pagar: {{pago.total | currency('Q ')}}  </v-btn>
            <v-btn flat color="blue">total cancelado: {{pago.total_cancelado | currency('Q ')}} </v-btn>
            <v-btn flat color="blue">FALTANTE: {{pago.total - pago.total_cancelado | currency('Q ')}} </v-btn>
            </v-card-actions>
        </v-card>
        <el-divider></el-divider>
         <v-form v-if="pago !== null && !pago.pagado && !pago.anulado && form.id == null">
             <h3 class="grey--text">Registrar nuevo abono</h3>
            <v-container>
                <v-layout wrap>
                    <v-flex xs12 sm6 md6>
                    <v-text-field v-model="form.pago" 
                        label="Monto abonar"
                        v-validate="'required|min_value:1|max_value:'+getFaltante()+''"
                        type="text"
                        data-vv-name="abonar"
                        :error-messages="errors.collect('abonar')">
                    </v-text-field>
                  </v-flex><v-flex xs12 sm6 md6>
                    <v-menu
                        v-model="menu"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                        <v-text-field
                            prepend-icon="date_range"
                            clearable
                            v-model="dateFormatted"
                            label="Fecha"
                            placeholder="ingrese fecha de inscripción"
                            v-on="on"
                            v-validate="'required'"
                            data-vv-name="fecha"
                            :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                        </template>
                        <v-date-picker locale="es" v-model="form.fecha" @input="menu = false"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-layout row wrap justify-end>
                        <div>
                            <v-btn small color="primary" dark class="mb-2" @click="validate"><v-icon> add</v-icon> abonar</v-btn>
                        </div>
                    </v-layout>
                </v-layout>
            </v-container>
          </v-form>
        <v-layout row wrap>

            <v-flex sm12 lg12 md12>
                <el-table :data="items" size="mini">
                  <el-table-column
                    type="index"
                    width="50">
                  </el-table-column>
                  <el-table-column label="No. comprobante" width="*">
                  <template slot-scope="scope">
                    <span style="margin-left: 10px">{{getFormat(scope.row.id) }}</span>
                  </template>
                </el-table-column>
                <el-table-column label="Fecha" width="*">
                  <template slot-scope="scope">
                    <i class="el-icon-date"></i>
                    <span style="margin-left: 10px">{{ scope.row.fecha | moment('DD/MM/YYYY') }}</span>
                  </template>
                </el-table-column>
                <el-table-column label="Abono" width="*">
                  <template slot-scope="scope">
                    <span style="margin-left: 10px">{{ scope.row.pago | currency('Q ') }}</span>
                  </template>
                </el-table-column>
                <el-table-column label="Acciones">
                  <template slot-scope="scope">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-icon color="blue" v-on="on" @click="imprimirComprobante(scope.row)"> print</v-icon>
                        </template>
                        <span>imprimir comprobante</span>
                    </v-tooltip>
                    <span v-if="!scope.row.anulado">
                      <v-tooltip top>
                          <template v-slot:activator="{ on }">
                              <v-icon color="red" v-on="on" @click="anularAbono(scope.row)"> block</v-icon>
                          </template>
                          <span>anular pago</span>
                      </v-tooltip>
                    </span>
                    <span v-else>
                      <el-tag size="small" type="danger">Anulado</el-tag>
                    </span>
                  </template>
                </el-table-column>
                </el-table>
            </v-flex>
        </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
export default {
  name: "detalle_pagos_parciales",
  props: {
      source: String
    },
  data() {
    return {
        loading: true,
        alumno:null,
        search: '',
        abono: {},
        dialog: false,
        pago: null,
        items: [],
        menu: false,
      form: {
          id: null,
          pago_id: null,
          pago: null,
          fecha: '',
          motivo_anulado: ''
      }
    };
  },

  created() {
    let self = this
    events.$on('get_pagos_parciales',self.onEventPagos)
  },

  beforeDestroy(){
    let self = this
    events.$off('get_pagos_parciales',self.onEventPagos)
  },

  methods: {
      onEventPagos(pago){
          let self = this
          self.pago = pago
          self.getPagos(pago.id)
          self.clearData()
      },

      getPagos(id){
        let self = this
        self.loading = true
        self.$store.state.services.pagoService
        .getPagosParciales(id)
        .then(r => {
            self.loading = false
            self.items = r.data
        }).catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form
      data.pago_id = self.pago.id

      self.$store.state.services.pagoParcialService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('pago realizado con éxito', 'éxito')
          self.close()
          self.onEventPagos(r.data)
        })
        .catch(r => {});
    },

    //funcion para actualizar guardar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.pagoParcialService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('pago realizado con éxito', 'éxito')
          self.close()
          self.onEventPagos(r.data)
        })
        .catch(r => {});
    },

    //funcion, validar si se guarda o actualiza
    validate(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.form.id === null ? self.create() : self.update()
           }
      });
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = false
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.$validator.reset()
    },

    close(){
      let self = this
      self.dialog = false
      self.clearData()
    },

    
    getFaltante(){
      let self = this
      return self.pago.total - self.pago.total_cancelado
    },

    anularAbono(data){
      let self = this
      self.abono = data
      self.dialog = true
      self.form.id = data.id
    },

    imprimirComprobante(data){
      let self = this
      self.loading = true
      self.$store.state.services.pagoService
        .comprobanteAbono(data.id)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'comprobante_no_'+self.getFormat(data.id)); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    getFormat(n){
      let self = this
      return self.$store.state.global.formatCode(n,7)
    }
      
  },

  computed: {
    dateFormatted(){
        let self = this
        self.form.fecha = moment().format('YYYY-MM-DD')
        return moment(self.form.fecha !== '' ? self.form.fecha : moment()).format('DD/MM/YYYY')
      }
    },
    
};
</script>
<style>
.item {
    padding: 5px 0;
  }
</style>