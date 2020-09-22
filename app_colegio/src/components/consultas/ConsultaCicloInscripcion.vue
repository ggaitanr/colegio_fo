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
        <v-toolbar-title v-if="ciclo !== null">Consulta Inscripciones ciclo escolar {{ciclo.ciclo}}  </v-toolbar-title>
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
        class="elevation-1 backgroud_table"
        :rows-per-page-items="[10,25]"
      >
        <template v-slot:items="props">
          <td>{{props.item.numero }}</td>
          <td>{{ props.item.alumno.primer_nombre }}
                                   {{ props.item.alumno.segundo_nombre }}
                                   {{ props.item.alumno.tercer_nombre }}
                                   {{ props.item.alumno.primer_apellido }}
                                   {{ props.item.alumno.segundo_apellido }}
          </td>
          <td>{{ props.item.ciclo.ciclo }}</td>
          <td>{{ props.item.grado_nivel_educativo.grado.nombre }} {{ props.item.grado_nivel_educativo.nivel_educativo.nombre }}</td>
          <td class="justify-center layout px-0">
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
import print from '../reportes/inscripciones'
import detalle from '../reportes/detalles/inscripcion_grado'
export default {
  name: "inscripciones",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      ciclo: null,
      loading: false,
      menu: false,
      date: null,
      items: [],
      headers: [
        { text: 'Numero de inscripción', value: 'numero' },
        { text: 'Alumno', value: "alumno.primer_nombre" },
        { text: 'Ciclo', value: 'ciclo.ciclo' },
        { text: 'Grado', value: "grado_nivel_educativo.grado.nombre" },
        { text: 'Acciones', value: '', sortable: false }
      ],
    };
  },

  created() {
    let self = this
    events.$on('get_ciclo_inscripciones',self.onEventCiclo)
  },

  beforeDestroy(){
      let self = this
      events.$off('get_ciclo_inscripciones',self.onEventCiclo)
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
        .getInscripciones(id)
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
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
      var items = _(self.items)
          .groupBy('grado_nivel_educativo_id')
          .map(function(items, grado_nivel_educativo_id) {
           var grado_nivel = items.find(x=>x.grado_nivel_educativo_id === parseInt(grado_nivel_educativo_id))
          return {
              grado_nivel_educativo_id: parseInt(grado_nivel_educativo_id),
              nombre: grado_nivel.grado_nivel_educativo.grado.nombre+' '+grado_nivel.grado_nivel_educativo.nivel_educativo.nombre,
              inscripciones: items.filter(x=>grado_nivel_educativo_id === grado_nivel_educativo_id)
          };
      }).value();
      
      var data = []

      items.forEach(item => {
        var table = detalle.print(item.inscripciones, item.nombre)
        data.push({
          grado: item.nombre,
          table: table,
          total: item.inscripciones.length
        })
      })

      
      print.print(data,'', '',self.ciclo)
    },
    
  },

  computed: {
    },
};
</script>