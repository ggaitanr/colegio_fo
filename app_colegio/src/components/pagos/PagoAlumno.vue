<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
        <v-layout row wrap justify-end>
        <div>
            <v-breadcrumbs :items="itemsB">
            <template v-slot:divider>
                <v-icon>forward</v-icon>
            </template>
            </v-breadcrumbs>
        </div>

        </v-layout>
        
        <create-pago></create-pago>
        <v-card v-if="alumno !== null">
            <v-card-text>
                <v-layout row wrap>
                    <v-flex d-flex xs12 md4 sm4 lg4>
                        <v-avatar
                            :tile="true"
                            size="200"
                            color="blue lighten-4"
                            >
                            <img :src="getAvatar(alumno.foto)" />
                        </v-avatar>
                    </v-flex>
                    <v-flex d-flex xs12 md8 sm6 lg8>
                        <v-layout row wrap>
                            <v-flex xs12 lg12 md12>
                            <el-card shadow="always">
                                <div class="text item">
                                    <b>Nombre:</b>
                                    {{alumno.primer_nombre}} {{alumno.segundo_nombre}}
                                                            {{alumno.primer_apellido}} {{alumno.segundo_apellido}}
                                </div>
                                <div class="text item">
                                    <b>Dirección:</b>
                                    {{alumno.direccion}}
                                </div>
                                <div class="text item">
                                    <b>Edad:</b>
                                    {{getEdad(alumno.fecha_nac)+' años'}}
                                </div>
                                <div class="text item">
                                    <b>Grado:</b>
                                    {{getGrado(alumno.inscripciones)}}
                                </div>
                                <div class="text item">
                                    <b>Responsable (cui - nombre):</b>
                                    {{alumno.responsable.apoderado.cui}} - 
                                    {{alumno.responsable.apoderado.primer_nombre}}
                                    {{alumno.responsable.apoderado.segundo_nombre}}
                                    {{alumno.responsable.apoderado.primer_apellido}}
                                    {{alumno.responsable.apoderado.segundo_apellido}}
                                </div>
                            </el-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-card-text>
        </v-card>
        <el-divider></el-divider>
        <v-flex xs12 sm4 md4>
            <v-autocomplete
                v-model="inscripcion_id"
                label="Seleccione ciclo escolar"
                placeholder="Ciclo escolar"
                :items="inscripciones"
                item-text="ciclo.ciclo"
                item-value="id"
                @input="selectInscripcion"
                >
                </v-autocomplete>
        </v-flex>
        <v-layout v-if="inscripcion !== null">
            <v-flex sm12 lg12 md12>
                <el-tabs v-model="activeName" type="border-card" @tab-click="selectOption">
                    <el-tab-pane label="1" name="pagos">
                        <span slot="label"><i class="el-icon-date"></i> Pagos realizados</span>
                            <b> Pagos realizados ciclo {{inscripcion.ciclo.ciclo}}</b>
                            <el-divider></el-divider>
                            <pagos></pagos>
                    </el-tab-pane>
                    <el-tab-pane label="0" name="otros_pagos">
                        <span slot="label"><i class="el-icon-date"></i> Otros pagos</span>
                        <b> Otros pagos ciclo {{inscripcion.ciclo.ciclo}}</b>
                        <el-divider></el-divider>
                        <pagos></pagos>
                    </el-tab-pane>
                    <el-tab-pane name="pendientes">
                        <span slot="label"><i class="el-icon-date"></i> Pagos pendientes</span>
                        <b> Pagos pendientes ciclo {{inscripcion.ciclo.ciclo}} </b>
                        <el-divider></el-divider>
                        <pago-pendiente></pago-pendiente>
                    </el-tab-pane>
                    
                </el-tabs>
            </v-flex>
            
            <v-tooltip top>
                <template v-slot:activator="{ on }" v-if="show_button_new">
                        <v-btn
                            @click="openDialog"
                            v-on="on"
                            v-show="true"
                            color="red"
                            dark
                            right
                            fixed
                            fab
                            bottom
                        >
                            <v-icon>add</v-icon>
                        </v-btn>
                </template>
                <span>agregar nuevo pagoo</span>
            </v-tooltip>
        </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import PagoPendiente from './PagosPendientes'
import Pagos from './Pagos'
import CreatePago from './CreatePago'

export default {
  name: "PagoAlumno",
  components: {
      PagoPendiente,
      Pagos,
      CreatePago
  },
  props: {
      source: String
    },
  data() {
    return {
        itemsB: [{
                text: 'SELECCIONAR ALUMNO',
                disabled: false,
                href: '#/seleccionar_alumno',
            },
            {
                text: 'PAGOS',
                disabled: true,
            }
        ],
        activeName: 'pagos',
        loading: true,
        alumno:null,
        inscripcion: null,
        inscripcion_id: null,
        inscripciones: [],
        conceptos: [],
        concepto_id: null,
        pagos: [],
        search: '',
        show_button_new: false,
        dialog: false,
        form: {
            id: null,
            inscripcion_id: null,
            cuota_id: null,
            is_credito: false,
            total: false
        }
    };
  },

  created() {
    let self = this
    self.get(self.$route.params.id)
    events.$on('get_pagos_ciclo',self.onEventPagos)
  },

  beforeDestroy(){
    let self = this
    events.$off('get_pagos_ciclo',self.onEventPagos)
  },

  methods: {
    onEventPagos(inscripcion){
        let self = this
        self.activeName = 'pagos'
        self.inscripcion = inscripcion
        inscripcion !== null ? self.getPagos(inscripcion.id) : ''
    },
    
    get(id) {
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .get(id)
        .then(r => {
            self.loading = false
            self.alumno = r.data
            self.inscripciones = self.alumno.inscripciones
            self.inscripcion = self.getInscripcionActual(self.alumno.inscripciones)
            self.inscripcion_id = self.inscripcion.id
            self.getPagos(self.inscripcion_id)
        }).catch(r => {});
    },

    getPagos(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.inscripcionService
        .getPagos(id)
        .then(r => {
            self.loading = false
            self.pagos = r.data
            self.show_button_new = true
            this.$nextTick(() => {  
                events.$emit('update_pagos',self.inscripcion, self.pagos)
                events.$emit("get_cuotas",self.inscripcion, self.pagos, self.alumno.responsable.apoderado)
            });
        }).catch(r => {});
    },

    getAvatar(foto){
      let self = this
      return foto !== null ? self.$store.state.base_url+foto : self.$store.state.base_url+'img/user_empty.png'
    },

    getGrado(inscripciones){
        let self = this
        var grado = 'No inscrito ciclo actual'
        inscripciones.forEach(ins => {
            if(ins.ciclo.actual){
                grado = ins.grado_nivel_educativo.grado.nombre+' '+ins.grado_nivel_educativo.nivel_educativo.nombre
            }
        });

        return grado
    },

    //funcion para obtener inscripcion actual
    getInscripcionActual(inscripciones){
        var inscripcion = null
        inscripciones.forEach(ins => {
            if(ins.ciclo.actual){
                inscripcion = ins
            }
        });
        return inscripcion
    },

    getEdad(date){
      return moment().diff(date, 'years');
    },

    selectInscripcion(id){
        let self = this
        self.inscripcion = self.inscripciones.find(x=>x.id == id)
        self.onEventPagos(self.inscripcion)
    },

    openDialog(){
        let self = this
        events.$emit('open_dialog_pago',self.inscripcion, self.pagos, null, self.alumno.responsable.apoderado)
    },

    selectOption(option){
        let self = this
        var ob = parseInt(option.label)
        if(ob == 1 || ob == 0){
            this.$nextTick(() => {  
                events.$emit('update_pagos',self.inscripcion, self.pagos, ob)
            });
        }

    }
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