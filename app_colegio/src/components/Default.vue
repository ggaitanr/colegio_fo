<template>
  <v-layout v-if="data !== null" v-loading="loading" grid-list-md>
      <v-layout wrap v-if="isAdmin">
        <!-- Caja alta de Alumnos Registrados -->
        <v-flex xs6 md4 sm4 lg4>
          <v-card
            class="mx-auto"
            color="grey lighten-4"
            max-width="600"
          >
            <v-card-title>
              <v-icon large color="green darken-2" class="mr-5" size="64">person_pin</v-icon>
              <v-layout
                column
                align-start
              >
                <div class="caption grey--text text-uppercase">
                  ALUMNOS REGISTRADOS
                </div>
                <div>
                  <span
                    class="display font-weight-black"
                    v-text="data.total_alumnos"
                  ></span>
                  <strong>ALUMNOS</strong>
                </div>
              </v-layout>

              <v-spacer></v-spacer>

              <v-btn icon class="align-self-start" size="28" @click="$router.push(`/alumno_index`)">
                <v-icon>arrow_forward</v-icon>
              </v-btn>
            </v-card-title>
          </v-card>
        </v-flex>
        <!-- Caja alta de Ciclo -->
        <v-flex xs6 md4 sm4 lg4 style="padding-left: 5px;">
          <v-card
            class="mx-auto"
            color="grey lighten-4"
            max-width="600"
          >
            <v-card-title>
              <v-icon large color="green darken-2" class="mr-5" size="64">file_copy</v-icon>
              <v-layout
                column
                align-start
              >
                <div class="caption grey--text text-uppercase">
                  CICLO {{ciclo.ciclo}}
                </div>
                <div>
                  <span
                    class="display font-weight-black"
                    v-text="data.total_inscripciones"
                  ></span>
                  <strong>INSCRIPCIONES</strong>
                </div>
              </v-layout>

              <v-spacer></v-spacer>

              <v-btn @click="$router.push(`/consulta_ciclo`)" icon class="align-self-start" size="28">
                <v-icon>arrow_forward</v-icon>
              </v-btn>
            </v-card-title>
          </v-card>
        </v-flex>
        <!-- Caja alta de Pagos Ciclo -->
        <v-flex xs6 md4 sm4 lg4 style="padding-left: 5px;">
          <v-card
            class="mx-auto"
            color="grey lighten-4"
            max-width="600"
          >
            <v-card-title>
              <v-icon large color="green darken-2" class="mr-5" size="45">money</v-icon>
              <v-layout
                column
                align-start
              >
                <div class="caption grey--text text-uppercase">
                  PAGOS CICLO {{ciclo.ciclo}}
                </div>
                <div>
                  <span
                    class="display font-weight-black"
                    v-text="valueCurrency(data.total_pagos)"
                  ></span>
                  <strong></strong>
                </div>
              </v-layout>

              <v-spacer></v-spacer>

              <v-btn @click="$router.push(`/consulta_ciclo`)" icon class="align-self-start" size="28">
                <v-icon>arrow_forward</v-icon>
              </v-btn>
            </v-card-title>
          </v-card>
        </v-flex>
        
        <!-- Division de las graficas -->
        <el-divider></el-divider>

        <v-flex xs12 md12 lg12>
          <div>
            <inscripciones></inscripciones>
          </div>
        </v-flex>

        <v-flex xs12 md4 lg4>
          <div>
            <resumen></resumen>
          </div>
        </v-flex>

        <v-flex xs12 md8 lg8>
          <div>
            <conceptos></conceptos>
          </div>
        </v-flex>

      </v-layout>
      <v-layout v-else wrap>
        <v-flex>
            <v-alert
              :value="true"
              type="info"
            >
              BIENVENIDO {{userName | uppercase}} <br />
              <small>usted esta navegando navegando como usuario {{rol}}</small>
            </v-alert>
        </v-flex>
      </v-layout>
  </v-layout>
</template>

<script>
import Resumen from './dashboard/PagosResumen'
import Conceptos from './dashboard/PagosConceptos'
import Inscripciones from './dashboard/InscripcionesCiclo'
import {RotatingSpinner} from 'vue-image-spinner'
export default {
  name: "default",
  components: {
    Resumen,
    Conceptos,
    Inscripciones,
    RotatingSpinner
  },
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      search: '',
      data: null,
      ciclo: this.$store.state.ciclo
    };
  },

  created() {
    let self = this
    self.getData()
  },

  methods: {
    getData(){
        let self = this
        self.loading = true
        self.$store.state.services.cicloService
        .getData(0)
        .then(r => {
            self.loading = false
            if(r.response !== undefined){
              self.$toastr.error(r.response.data.error, 'error')
              return
            }
            self.data = r.data
            self.$nextTick(()=>{
              events.$emit('dashboard_pagos_resumen',self.data.resumen)
              events.$emit('dashboard_pagos_conceptos',self.data.pago_cuotas)
              events.$emit('dashboard_inscripciones',self.data.ciclo)
            })
            
        }).catch(e => {

        })
    },

    valueCurrency(value){
      let val = (value / 1).toFixed(2).replace('.', '.')
      return 'Q ' + ' ' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    },

    cancelar(){
      let self = this
    },

    close () {
        let self = this
    },
  },

  computed: {
    logo(){
      let self = this
      return self.$store.state.global.getLogo()
    },

    isAdmin(){
      let self = this
      var user = self.$store.state.usuario
      if(!_.isEmpty(user) && user.rol.rol === 'admin'){
        return true
      }
      return false
    },

    userName(){
      let self = this
      var user = self.$store.state.usuario
      if(!_.isEmpty(user)){
        return user.name
      }
      return ''
    },
    rol(){
      let self = this
      var user = self.$store.state.usuario
      if(!_.isEmpty(user)){
        return user.rol.rol
      }
      return ''
    }
  },
};
</script>