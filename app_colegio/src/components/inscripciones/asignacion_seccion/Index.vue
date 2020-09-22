<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Asignación de alumnos a secciones </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="buscar"
            single-line
            hide-details
          ></v-text-field>
        <v-spacer></v-spacer>
      </v-toolbar>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.grado_nivel_educativo.grado.nombre }}</td>
          <td class="text-xs-left">{{ props.item.grado_nivel_educativo.nivel_educativo.nombre }}</td>
          <td class="text-xs-left">{{ props.item.seccion.seccion }}</td>
          <td class="text-xs-left">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on"  color="primary" @click="$router.push(`/configurar_nivel/`+props.item.id)" fab dark> edit</v-icon>
                </template>
                <span>Asignar</span>
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
export default {
  name: "AsingnacionIndex",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      loading: false,
      items: [],
      headers: [
        { text: 'Grado', value: 'grado_nivel_educativo.grado.nombre' },
        { text: 'Nivel educativo', value: 'grado_nivel_educativo.nivel_educativo.nombre' },
        { text: 'Sección', value: 'seccion.seccion' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      form: {
        id: null,
        seccion: '',
      },
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

      self.$store.state.services.gradoNivelEducativoService
        .getSecciones()
        .then(r => {
          self.loading = false
          /*r.data = r.data.map(obj=>({ ...o, 
                                        grado: o.grado_nivel_educativo.grado.nombre+' '+
                                                      o.grado_nivel_educativo.nivel_educativo.nombre+' sección'+ 
                                                      o.seccion.seccion}))*/
          self.items = r.data
        })
        .catch(r => {});
    },

    asignar(){
        let self = this

    }

  },

  computed: {
  },
};
</script>