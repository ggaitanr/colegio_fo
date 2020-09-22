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
        <v-toolbar-title>Consulta Inscripciones </v-toolbar-title>
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
       <v-toolbar flat color="white">
                Desde:&nbsp;
                <v-text-field type="date"
                          class="text-xs-center" v-model="inicio"></v-text-field>
                Hasta:&nbsp;
                <v-text-field type="date"
                          class="text-xs-center" v-model="fin"></v-text-field>
                &nbsp;&nbsp;
                <v-flex>
                    <v-select v-model="grado_id"
                    item-value="id"
                    item-text="nombre"
                    @input="selectedGrado"
                    :items="grados" label="Seleccione grado">
                    </v-select>
                </v-flex>

                <v-btn color="primary" @click="buscar" small dark class="mb-2">Buscar</v-btn>
          <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
      </v-toolbar>

      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        class="elevation-1"
        :rows-per-page-items="[10,25]"
      >
        <template v-slot:items="props">
          <td>{{props.item.numero }}</td>
          <td>{{ props.item.alumno_name }}
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
      loading: false,
      menu: false,
      date: null,
      items: [],
      ciclo: null,
      grados: [],
      grado_id: 0,
      headers: [
        { text: 'Numero de inscripción', value: 'numero' },
        { text: 'Nombres alumno', value: "alumno_name" },
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
    self.getGradoNivel()
    //self.getAll()
  },

  methods: {
    getGradoNivel(){
      let self = this
      self.loading = true
      self.$store.state.services.gradoNivelEducativoService
        .getAll()
        .then(r => {
          self.loading = false
          r.data = r.data.map(obj=>({ ...obj, nombre: obj.grado.nombre+' '+obj.nivel_educativo.nombre}))
          r.data.push({id: 0, nombre: 'Todos'})
          self.grados = r.data
        })
        .catch(r => {});
    },

    getAll() {
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
           self.items.forEach(function(item){
              item.alumno_name = self.getName(item.alumno,true)
          })
        })
        .catch(r => {});
    },

    getAllByGrado() {
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getAllByGrado(self.grado_id)
        .then(r => {
          self.loading = false
          self.items = r.data
          self.items.forEach(function(item){
              item.alumno_name = self.getName(item.alumno,true)
          })
        })
        .catch(r => {});
    },

    getByFechas() {
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getByFechas(self.inicio, self.fin, self.grado_id)
        .then(r => {
          self.loading = false
          self.items = r.data
          self.items.forEach(function(item){
              item.alumno_name = self.getName(item.alumno,true)
          })
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

    buscar () {
      let self = this
      if(self.inicio !== "" && self.fin === ""){
         this.$toastr.error('debe seleccionar fecha fin', 'error')
          return
      }
      if(self.fin !== '' && self.inicio === ""){
         this.$toastr.error('debe seleccionar fecha inicio', 'error')
          return
      }
       if(self.inicio == '' && self.fin == '' && self.grado_id == 0){
         self.getAll()
         return
       }
       if(self.inicio == '' && self.fin == '' && self.grado_id !== 0){
         self.getAllByGrado()
         return
       }
       if(self.inicio !== ''){
         self.getByFechas()
       }
    },

    selectedGrado(id){
      let self = this
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
      
      print.print(data,self.inicio, self.fin)
    },

     getName(data){
        let self = this
        return self.$store.state.global.getFullName(data)
    },
    
  },

  computed: {
    },
};
</script>