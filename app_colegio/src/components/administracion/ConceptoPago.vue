<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Concepto de Pagos</v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px" persistent>
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" small v-on="on">
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
                  <v-flex xs12 sm8 md8>
                    <v-text-field
                      v-model="form.nombre"
                      label="Nombre"
                      v-validate="'required'"
                      type="text"
                      data-vv-name="nombre"
                      :error-messages="errors.collect('nombre')"
                    ></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-select
                      v-model="form.forma_pago"
                      placeholder="seleccion forma de pago"
                      v-validate="'required'"
                      :items="options"
                      :error-messages="errors.collect('forma_pago')"
                      label="Forma de pago"
                      item-value="value"
                      item-text="name"
                      data-vv-name="forma_pago"
                      clearable
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm12 md12>
                    <v-switch
                      v-model="form.is_parcial"
                      :label="`¿se puede pagar al credito? ${form.is_parcial.toString() === 'false' ?'No':'Si'}`"
                    ></v-switch>
                    <v-switch
                      v-model="form.obligatorio"
                      :label="`¿es pago obligatorio? ${form.obligatorio.toString() === 'false' ?'No':'Si'}`"
                    ></v-switch>
                    <v-switch
                      v-model="form.mora"
                      :label="`¿aplica mora? ${form.mora.toString() === 'false' ?'No':'Si'}`"
                    ></v-switch>
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
          <td class="text-xs-left">{{ props.item.is_parcial ? 'Si':'No' }}</td>
          <td class="text-xs-left">{{ props.item.obligatorio ?'Si':'No' }}</td>
          <td class="text-xs-left">{{ props.item.mora ?'Si':'No' }}</td>
          <td class="text-xs-left">{{ props.item.forma_pago === 'A' ?'Anual':props.item.forma_pago === 'M' ? 'Mensual' : 'Ninguno' }}</td>
          <td class="text-xs-left">
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
export default {
  name: "ConceptoPago",
  props: {
    source: String
  },
  data() {
    return {
      dialog: false,
      search: "",
      loading: false,
      items: [],
      headers: [
        { text: "Nombre", value: "nombre" },
        { text: "¿pago al crédito?", value: "is_parcial" },
        { text: "¿obligatorio?", value: "obligatorio" },
        { text: "¿aplica mora?", value: "mora" },
        { text: "Forma pago", value: "forma_pago" },
        { text: "Acciones", value: "", sortable: false }
      ],
      pagination: {
        sortBy: "id"
      },

      form: {
        id: null,
        nombre: null,
        is_parcial: true,
        obligatorio: true,
        mora: true,
        forma_pago: ''
      },

      options: [
        {name: 'anual', value: 'A'},
        {name: 'mensual', value: 'M'},
        {name: 'ninguno', value: 'N'}
      ]
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

      self.$store.state.services.conceptoPagoService
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
      let estado = data.is_parcial === false ? 0 : 1;
      data.is_parcial = estado;
      self.$store.state.services.conceptoPagoService
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
      let estado = data.is_parcial === false ? 0 : 1;
      data.is_parcial = estado;
      self.$store.state.services.conceptoPagoService
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
        .catch(r => {
        });
    },

    //funcion para eliminar registro
    destroy(data) {
      let self = this;
      self
        .$confirm(
          "Seguro que desea eliminar Concepto de Pago " + data.nombre + "?"
        )
        .then(res => {
          self.loading = true;
          self.$store.state.services.conceptoPagoService
            .destroy(data)
            .then(r => {
              self.loading = false;
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
      self.form.nombre = data.nombre
      self.form.is_parcial = !!(data.is_parcial)
      self.form.obligatorio = !!(data.obligatorio)
      self.form.forma_pago = data.forma_pago
      self.form.mora = data.mora
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
    }
  }
};
</script>