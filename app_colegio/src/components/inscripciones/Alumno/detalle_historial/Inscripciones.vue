<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1"
        hide-actions
        :rows-per-page-items="[10,25]"
      >
        <template v-slot:items="props">
          <td>{{props.item.numero }}</td>
          <td>{{ props.item.ciclo.ciclo }}</td>
          <td>{{ props.item.grado_nivel_educativo.grado.nombre }} {{ props.item.grado_nivel_educativo.nivel_educativo.nombre }}</td>
          <td>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" color="primary" fab dark @click="printContrato(props.item)"> print</v-icon>
                </template>
                <span>Imprimir contrato</span>
            </v-tooltip>
          </td>
        </template>
        <template slot="no-data">
            <h3>No se encontraron inscripciones.</h3>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "inscripciones_alumno_historial",
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
      grados: [],
      grado_id: 0,
      headers: [
        { text: 'Numero de inscripción', value: 'numero' },
        { text: 'Ciclo', value: 'ciclo.ciclo' },
        { text: 'Grado', value: "grado_nivel_educativo.grado.nombre" },
        { text: 'Acciones', value: '', sortable: false }
      ],

      inicio: '',
      fin: ''
    };
  },

  created() {
    let self = this
    //self.getAll()
    events.$on('historial_academico_inscripciones',self.onEventAcademico)
  },

  beforeDestroy(){
      let self = this
      events.$off('historial_academico_inscripciones',self.onEventAcademico)
  },

  methods: {
    onEventAcademico(inscripciones,alumno) {
      let self = this
      inscripciones.forEach(function(item){
        item.alumno_name = self.getName(alumno,true)
      })
      self.items = inscripciones
    },

    printContrato (item) {
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getContrato(item.id)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'inscripcion_'+item.numero); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    printReporte(){
      let self = this
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