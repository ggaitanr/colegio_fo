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
        <v-toolbar-title>Alumnos </v-toolbar-title>
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
        <v-spacer></v-spacer>
          <v-btn color="primary" small @click="$router.push(`/alumno_create`)" dark class="mb-2"><v-icon>add</v-icon> Nuevo</v-btn>
      </v-toolbar>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        class="elevation-1"
        disable-initial-sort
      >
        <template v-slot:items="props">
          <td>
            <v-flex xs4 sm2 md1>
              <v-avatar
                size="36px"
              >
                <img
                  :src="getAvatar(props.item.foto)"
                  alt="Avatar"
                >

              </v-avatar>
            </v-flex>
          </td>
          <td class="text-xs-left">{{ props.item.codigo }}</td>
          
          <td class="text-xs-left">{{ props.item.primer_nombre }} {{ props.item.segundo_nombre }} 
                                    {{ props.item.primer_apellido }} {{ props.item.segundo_apellido }}</td>
          <td class="text-xs-left">{{ setEdad(props.item.fecha_nac) }} años
          <td class="text-xs-left">
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" color="success" fab dark @click="$router.push(`/historial_academico/`+props.item.id)"> how_to_reg</v-icon>
                </template>
                <span>ver historial academico</span>
            </v-tooltip>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" color="primary" fab dark @click="$router.push(`/inscripcion/`+props.item.id)"> file_copy</v-icon>
                </template>
                <span>visualizar e ingresar inscripciones</span>
            </v-tooltip>

              <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" color="warning" fab dark @click="$router.push(`/alumno_edit/`+props.item.id)"> edit</v-icon>
                </template>
                <span>Editar</span>
            </v-tooltip>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" color="error" fab dark @click="destroy(props.item)"> delete</v-icon>
                </template>
                <span>Eliminar</span>
            </v-tooltip>
          </td>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="getAll">Reset</v-btn>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import print from '../../reportes/alumnos'
export default {
  name: "AlumnoIndex",
  props: {
      source: String
    },
  data() {
    return {
      search: '',
      loading: false,
      items: [],
      headers: [
        { text: 'Foto', value: 'foto' },
        { text: 'Codigo', value: 'codigo' },
        { text: 'Nombres', value: 'primer_nombre' },
        { text: 'Edad', value: 'fecha_nac' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      itemsB: [
        {
          text: 'ALUMNOS',
          disabled: true,
          href: '#/alumno_index',
        }
      ],
    };
  },

  created() {
    let self = this
    self.getAll()
  },

  methods: {
     getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.alumnoService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar alumno'+ data.codigo+'?').then(res => {
        self.loading = true
            self.$store.state.services.alumnoService
            .destroy(data)
            .then(r => {
                self.loading = false
                if(r.response){
                  this.$toastr.error(r.response.data.error, 'error')
                  return
                }
                self.getAll()
                this.$toastr.success('registro eliminado con exito', 'exito')
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

    setEdad(date){
      return moment().diff(date, 'years');
    },

    getAvatar(foto){
      let self = this
      return foto !== null ? self.$store.state.base_url+foto : self.$store.state.base_url+'img/user_empty.png'
    },

    printReporte(){
      let self = this
      print.print(self.items)
    }
  },

  computed: {
    
  },
};
</script>