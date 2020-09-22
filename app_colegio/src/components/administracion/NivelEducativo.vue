<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Niveles Educativos</v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" small dark class="mb-2" v-on="on">
              <v-icon>add</v-icon>Nuevo
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{setTitle}}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm12 md12>
                    <v-text-field
                      v-model="form.nombre"
                      label="Nivel Educativo"
                      v-validate="'required'"
                      type="text"
                      data-vv-name="nivel_educativo"
                      :error-messages="errors.collect('nivel_educativo')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-text-field
                      v-model="form.resolucion"
                      label="No. resolución"
                      v-validate="'required'"
                      type="text"
                      data-vv-name="no_resolucion"
                      data-vv-as="numero deresolucion"
                      :error-messages="errors.collect('no_resolucion')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-menu
                      v-model="menu2"
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
                          clearable
                          v-model="dateFormattedFecha"
                          label="Fecha Resolución"
                          placeholder="Ingrese fecha resolución"
                          v-on="on"
                          v-validate="'required'"
                          data-vv-name="fecha"
                          data-vv-as="fecha resolucion"
                          :error-messages="errors.collect('fecha')"
                        ></v-text-field>
                      </template>
                      <v-date-picker locale="es" v-model="form.fecha" @input="menu2 = false"></v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs12 md2 lg2>
                    <v-checkbox v-model="form.es_carrera" label="¿es carrera?"></v-checkbox>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="createOrEdit">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-data-table :headers="headers" :items="items" :search="search" class="elevation-1">
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.nombre }}</td>
          <td class="text-xs-left">{{ props.item.resolucion }}</td>
          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon
                  v-on="on"
                  color="primary"
                  fab
                  dark
                  @click="$router.push(`/configurar_nivel/`+props.item.id)"
                >settings</v-icon>
              </template>
              <span>Configurar grados, cursos y secciones</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon v-on="on" color="warning" fab dark @click="edit(props.item)">edit</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                <v-icon v-on="on" color="error" fab dark @click="destroy(props.item)">delete</v-icon>
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
import moment from "moment";
export default {
  name: "NivelEducativo",
  props: {
    source: String
  },
  data() {
    return {
      dialog: false,
      search: "",
      menu2: null,
      loading: false,
      items: [],
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "Numero de resolución", value: "resolucion" },
        { text: "Acciones", value: "", sortable: false }
      ],

      form: {
        id: null,
        nombre: "",
        resolucion: "",
        fecha: new Date().toISOString().substr(0, 10),
        es_carrera: false
      }
    };
  },

  created() {
    let self = this;
    self.getAll();
  },

  methods: {
    getAll() {
      let self = this;
      self.loading = true;

      self.$store.state.services.nivelEducativoService
        .getAll()
        .then(r => {
          self.loading = false;
          self.items = r.data;
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create() {
      let self = this;
      self.loading = true;
      let data = self.form;

      self.$store.state.services.nivelEducativoService
        .create(data)
        .then(r => {
          self.loading = false;
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          this.$toastr.success("registro agregado con éxito", "éxito");
          self.getAll();
          self.clearData();
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update() {
      let self = this;
      self.loading = true;
      let data = self.form;

      self.$store.state.services.nivelEducativoService
        .update(data)
        .then(r => {
          self.loading = false;
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          self.getAll();
          this.$toastr.success("registro actualizado con éxito", "éxito");
          self.clearData();
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm("Seguro que desea eliminar nivel educativo " + data.nombre + "?")
        .then(res => {
          self.loading = true;
          self.$store.state.services.nivelEducativoService
            .destroy(data)
            .then(r => {
              self.loading = false;
              if (r.response) {
                this.$toastr.error(r.response.data.error, "error");
                return;
              }
              self.getAll();
              this.$toastr.success("registro eliminado con exito", "exito");
              self.clearData();
            })
            .catch(r => {});
        })
        .catch(cancel => {});
    },

    //limpiar data de formulario
    clearData() {
      let self = this;

      Object.keys(self.form).forEach(function(key, index) {
        if (typeof self.form[key] === "string") self.form[key] = "";
        else if (typeof self.form[key] === "boolean") self.form[key] = false;
        else if (typeof self.form[key] === "number") self.form[key] = null;
      });

      self.$validator.reset();
    },

    //editar registro
    edit(data) {
      let self = this;
      this.dialog = true;
      self.mapData(data);
    },

    //mapear datos a formulario
    mapData(data) {
      let self = this;
      self.form.id = data.id;
      self.form.nombre = data.nombre;
      self.form.resolucion = data.resolucion;
      self.form.fecha = data.fecha;
      self.form.es_carrera = data.es_carrera;
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          if (self.form.id > 0 && self.form.id !== null) {
            self.update();
          } else {
            self.create();
          }
        }
      });

      let self = this;
    },

    cancelar() {
      let self = this;
    },

    close() {
      let self = this;
      self.dialog = false;
      self.clearData();
    }
  },

  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? self.form.nombre : "Nuevo Registro";
    },
    dateFormattedFecha() {
      let self = this;
      return moment(self.form.fecha !== "" ? self.form.fecha : moment()).format(
        "DD/MM/YYYY"
      );
    }
  }
};
</script>