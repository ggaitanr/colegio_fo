<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Ciclos</v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark small class="mb-2" v-on="on">
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
                  <v-flex xs12 sm4 md4>
                    <v-text-field
                      v-model="form.ciclo"
                      label="Ciclo"
                      v-validate="'required'"
                      type="number"
                      data-vv-name="ciclo"
                      :error-messages="errors.collect('ciclo')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md64>
                      <v-menu
                          v-model="menu"
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
                              v-model="dateFormattedInicio"
                              label="Fecha inicio"
                              placeholder="ingrese fecha de inicio"
                              v-on="on"
                              v-validate="'required'"
                              data-vv-name="inicio"
                              data-vv-as="fecha de inicio"
                              :error-messages="errors.collect('fecha')"
                          ></v-text-field>
                          </template>
                          <v-date-picker locale="es" v-model="form.inicio" @input="menu = false"></v-date-picker>
                      </v-menu>
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
                              v-model="dateFormattedFin"
                              label="Fecha fin"
                              placeholder="ingrese fecha fin"
                              v-on="on"
                              v-validate="'required'"
                              data-vv-name="fin"
                              data-vv-as="fecha fin"
                              :error-messages="errors.collect('fin')"
                          ></v-text-field>
                          </template>
                          <v-date-picker locale="es" v-model="form.fin" @input="menu2 = false"></v-date-picker>
                      </v-menu>
                  </v-flex>
                  
                    <v-switch v-if="form.id !== null"
                      v-model="form.actual"
                      :label="`Actual: ${form.actual.toString() === 'false' ?'No':'Si'}`"
                    ></v-switch>
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
          <td class="text-xs-left">{{ props.item.ciclo }}<v-chip
              v-if="props.item.actual"
              small
              color="blue"
              text-color="white"
            >Actual</v-chip></td>
          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="success" fab dark @click="$router.push(`/configurar_cuota/`+props.item.id)">money</v-icon>
              </template>
              <span>Configurar cuotas</span>
            </v-tooltip>
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="warning" fab dark @click="edit(props.item)">edit</v-icon>
              </template>
              <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="items.length > 1">
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
import moment from 'moment'
import auth from '../../auth/index'
export default {
  name: "Ciclo",
  props: {
    source: String
  },
  data() {
    return {
      dialog: false,
      search: "",
      menu: null,
      menu2: null,
      loading: false,
      items: [],
      headers: [
        { text: "Ciclo", value: "ciclo" },
        { text: "Acciones", value: "", sortable: false }
      ],
      pagination: {
        sortBy: "id"
      },

      form: {
        id: null,
        ciclo: null,
        actual: true,
        inicio: new Date().toISOString().substr(0, 10),
        fin: new Date().toISOString().substr(0, 10)
      }
    };
  },

  created() {
    let self = this;
    self.getAll();
  },

  methods: {
    getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.cicloService
        .getAll()
        .then(r => {
          self.loading = false;
          self.items = r.data;
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create() {
      let self = this
      self.loading = true
      let data = self.form
      if(self.items.some(x=>x.ciclo == data.ciclo)){
        this.$toastr.error('ciclo ya fue agregado','error')
        return
      }

      let estado = data.actual === false ? 0 : 1
      data.actual = estado
      self.$store.state.services.cicloService
        .create(data)
        .then(r => {
          self.loading = false;
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          this.$toastr.success("registro agregado con éxito", "éxito");
          self.getAll();
          self.close();
        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update() {
      let self = this
      let data = self.form

      var items_filter = self.items.filter(x=>x.id !== data.id)
      if(items_filter.some(x=>x.ciclo == data.ciclo)){
        this.$toastr.error('ciclo ya fue agregado','error')
        return
      }

      if(!data.actual && items_filter.filter(x=>x.actual).length == 0){
        this.$toastr.error('debe haber al menos un ciclo activo','error')
        return
      }
      
      let estado = data.actual === false ? 0 : 1
      data.actual = estado
      self.loading = true
      self.$store.state.services.cicloService
        .update(data)
        .then(r => {
          self.loading = false;
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          if(data.actual){
            auth.getCicloActual()
          }
          self.getAll()
          this.$toastr.success("registro actualizado con éxito", "éxito");
          self.close()
        })
        .catch(r => {
        });
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm("Seguro que desea eliminar ciclo " + data.ciclo + "?")
        .then(res => {
          self.loading = true;
          self.$store.state.services.cicloService
            .destroy(data)
            .then(r => {
              self.loading = false;
              if (r.response) {
                this.$toastr.error(r.response.data.error, "error");
                return;
              }
              self.getAll();
              this.$toastr.success("registro eliminado con exito", "exito");
              self.close();
            })
            .catch(r => {});
        })
        .catch(cancel => {});
    },

    //limpiar data de formulario
    clearData() {
      let self = this;

      Object.keys(self.form).forEach(function(key, index) {
        if (typeof self.form[key] === "string") self.form[key] = '';
        else if (typeof self.form[key] === "boolean") self.form[key] = true;
        else if (typeof self.form[key] === "number") self.form[key] = true;
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
      let self = this
      self.form.id = data.id
      self.form.ciclo = data.ciclo
      self.form.actual = data.actual === 1 || null ? true : false
      self.form.inicio = data.inicio
      self.form.fin = data.fin
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit() {
      let self = this
      this.$validator.validateAll().then(result => {
        if (result) {
          if (self.form.id > 0 && self.form.id !== null) {
            self.update();
          } else {
            self.create();
          }
        }
      });

    },

    cancelar() {
      let self = this
    },

    close() {
      let self = this
      self.dialog = false
      self.clearData()
    }
  },

  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? self.form.ciclo : "Nuevo Registro"
    },

    dateFormattedInicio(){
        let self = this
        return moment(self.form.inicio !== '' ? self.form.inicio : moment()).format('DD/MM/YYYY')
    },

    dateFormattedFin(){
        let self = this
        return moment(self.form.fin !== '' ? self.form.fin : moment()).format('DD/MM/YYYY')
    }
  }
};
</script>