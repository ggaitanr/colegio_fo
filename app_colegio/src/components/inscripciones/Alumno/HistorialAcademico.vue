<template>
  <v-layout align-start>
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
      <v-toolbar flat color="white">
        <v-toolbar-title v-if="alumno !== null">Historial academico {{getName(alumno,true)}} </v-toolbar-title>
      </v-toolbar>
      <v-layout wrap>
          <v-flex xs12 md9 sm9 lg9>
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
                            <v-flex d-flex xs12 md8 sm8 lg8>
                                <v-layout row wrap>
                                    <v-flex xs12 lg12 md12>
                                    <el-card shadow="always">
                                        <div class="text item">
                                            <b>Nombre:</b>
                                            {{getName(alumno,true)}}
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
                                            {{getGrado(inscripciones)}}
                                        </div>
                                        <div class="text item">
                                            <b>Responsable (cui - nombre):</b>
                                            {{alumno.responsable.apoderado.cui}} - {{getName(alumno.responsable.apoderado)}}
                                        </div>
                                    </el-card>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                </v-card>
          </v-flex>
          <v-flex md4 sm4 xs12 lg3 style="padding-left: 5px;">
            <v-card v-if="alumno !== null">
                <v-card-title>
                    <h4 class="primary--text"><v-icon small color="blue">account_balance</v-icon> Cuenta # {{alumno.codigo}}</h4>
                </v-card-title>
                <v-card-text>
                    <v-btn block :color="saldo > 0 ? 'red lighten-2' : 'green lighten-2' " dark>{{saldo > 0 ? 'Alumno moroso':'Alumno solvente'}}</v-btn>
                    <v-btn block color="blue lighten-2" dark>Saldo total hasta fecha <br /> {{current_date | moment('DD/MM/YYYY')}}</v-btn>
                    <div class="text-xs-center">
                        <h3>{{saldo | currency('Q ')}}</h3>
                    </div>
                    <v-btn block color="warning" dark>adeudos por pagos <br /> al credito</v-btn>
                    <div class="text-xs-center">
                        <h3>{{pagosParciales(pagos) | currency('Q ')}}</h3>
                    </div>
                </v-card-text>
            </v-card>
            </v-flex>
      </v-layout>
        
        
        <el-divider></el-divider>
        <v-layout>
            <v-flex>
                <el-tabs type="border-card" @tab-click="selectOption">
                    <el-tab-pane>
                        <span slot="label"> <v-icon small color="blue">file_copy</v-icon> Inscripciones</span>
                            <b> Inscripciones</b>
                            <el-divider></el-divider>
                            <inscripciones></inscripciones>
                    </el-tab-pane>
                    <el-tab-pane label="1">
                        <span slot="label"><v-icon small color="blue">money</v-icon> Pagos</span>
                        <b> Pagos realizados</b>
                        <el-divider></el-divider>
                        <pagos></pagos>
                    </el-tab-pane>
                    <el-tab-pane label="0">
                        <span slot="label"><v-icon small color="blue">money</v-icon> Otros pagos</span>
                        <b> Otros pagos</b>
                        <el-divider></el-divider>
                        <pagos></pagos>
                    </el-tab-pane>
                    <el-tab-pane>
                        <span slot="label"><v-icon small color="blue">money</v-icon> Pagos atrasados</span>
                        <b> Pagos atrasados </b>
                        <el-divider></el-divider>
                        <pagos-atrasados></pagos-atrasados>
                    </el-tab-pane>
                </el-tabs>
            </v-flex>
        </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import Inscripciones from './detalle_historial/Inscripciones'
import Pagos from './detalle_historial/Pagos'
import PagosAtrasados from './detalle_historial/PagosAtrasados'

export default {
  name: "ExampleIndex",
  components: {
      Inscripciones,
      Pagos,
      PagosAtrasados
  },
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      alumno: null,
      current_date: moment(),
      saldo: 0,
      inscripciones: [],
      pagos: [],
      itemsB: [{
              text: 'ALUMNOS',
              disabled: false,
              href: '#/alumno_index',
          },
          {
              text: 'HISTORIAL ACADEMICO',
              disabled: true,
          }
      ],
    }
  },

  created() {
    let self = this
    self.getAlumno(self.$route.params.id)
    events.$on('update_saldo',self.onEventSaldo)
  },

  beforeDestroy(){
      let self = this
      events.$off('update_saldo',self.onEventSaldo)
  },

  methods: {
    onEventSaldo(saldo){
        let self = this
        self.saldo = saldo
    },
    //obtener alumno
    getAlumno(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .getHistorialAlumno(id)
        .then(r => {
            self.loading = false
            self.alumno = r.data.alumno
            self.inscripciones = r.data.inscripciones
            self.pagos = r.data.pagos
            self.$nextTick(() => {  
                events.$emit('historial_academico_inscripciones',self.inscripciones,self.alumno)
                events.$emit('historial_academico_pagos_atrasados',self.pagos,self.inscripciones)
            });
        }).catch(r => {});
    },

    getName(data, tercer_nombre = false){
        let self = this
        return self.$store.state.global.getFullName(data, tercer_nombre)
    },

    getEdad(date){
      return moment().diff(date, 'years');
    },

    getAvatar(foto){
      let self = this
      var default_phono = self.$store.state.base_url+'img/user_empty.png'
      return foto !== null && foto !== "" ? self.$store.state.base_url+foto : default_phono
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

    selectOption(option){
        let self = this
        var ob = parseInt(option.label)
        if(ob == 1 || ob == 0){
            this.$nextTick(() => {  
                events.$emit('historial_academico_pagos',self.pagos, ob)
            });
        }

    },

    pagosParciales(pagos){
        let self = this
        pagos = pagos.filter(x=>!x.anulado && !x.pagado)

        return self.totalParciales(pagos)
    },

    totalParciales(data){
        let self = this
        var total = data.reduce((a, b) => {
            return a + parseFloat(b.total - b.total_cancelado)
        },0);

        return total
    }
  },

  computed: {
    },
};
</script>