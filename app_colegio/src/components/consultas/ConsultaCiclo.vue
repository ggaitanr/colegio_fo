<template>
  <v-layout align-left v-loading="loading">
    <v-flex>
        <v-toolbar flat color="white">
        <v-toolbar-title>Consulta Ciclos </v-toolbar-title>
          <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
        <v-flex>
            <v-select v-model="ciclo_id"
            item-value="id"
            item-text="ciclo"
            @input="selectedCiclo"
            :items="ciclos" label="Seleccione ciclo">
            </v-select>
        </v-flex>
      </v-toolbar>
      <v-layout>
        <v-flex>
          <el-tabs type="border-card">
            <el-tab-pane>
              <span slot="label"><i class="el-icon-notebook-1"></i> Inscripciones</span>
              <inscripciones></inscripciones>
            </el-tab-pane>
            <el-tab-pane>
              <span slot="label"><i class="el-icon-money"></i> Pagos</span>
              <pagos></pagos>
            </el-tab-pane>
            <el-tab-pane>
              <span slot="label"><i class="el-icon-money"></i> Resumen</span>
              <resumen-ciclo></resumen-ciclo>
            </el-tab-pane>
          </el-tabs>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import Pagos from './ConsultaCicloPago'
import Inscripciones from './ConsultaCicloInscripcion'
import ResumenCiclo from './ResumenCiclo'
export default {
  name: "default",
  components:{
    Pagos,
    Inscripciones,
    ResumenCiclo
  },
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      search: '',
      data: null,
      ciclo_id: null,
      ciclo: null,
      ciclos: []
    };
  },

  created() {
    let self = this
    self.getAll()
  },

  methods: {
    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.cicloService
        .getAll()
        .then(r => {
          self.loading = false
          self.ciclos = r.data
          var ciclo_id = self.$store.state.ciclo.id
          self.ciclo_id = ciclo_id
          self.selectedCiclo(ciclo_id)
        })
        .catch(r => {});
    },

     get(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.cicloService
        .get(id)
        .then(r => {
          self.loading = false
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          self.ciclo = r.data
          this.$nextTick(() => {    
            events.$emit('get_ciclo_inscripciones',self.ciclo)
            events.$emit('get_ciclo_pagos',self.ciclo)
            events.$emit('get_ciclo_data',self.ciclo)
          })
        })
        .catch(r => {});
    },

    selectedCiclo(id){
      let self = this
      self.get(id)
    }
  },

  computed: {
  },
};
</script>