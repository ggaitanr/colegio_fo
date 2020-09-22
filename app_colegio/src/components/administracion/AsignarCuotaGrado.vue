<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
        <v-layout row wrap justify-end>
        <div>
          <v-breadcrumbs :items="itemsB">
            <template v-slot:divider>
              <v-icon>forward</v-icon>
            </template>
          </v-breadcrumbs>
        </div>
      </v-layout>

      <v-dialog v-model="dialog_edit" max-width="600px" persistent>
          <v-card>
            <v-card-title>
              <span class="headline" v-if="grado !== null && concepto !== null">Editar cuota {{concepto.nombre}} {{grado.grado.nombre+' '+grado.nivel_educativo.nombre | lowercase()}} </span>
            </v-card-title>

            <v-card-text v-if="concepto !== null">
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs6 sm6 md6>
                    <v-text-field
                      v-model="form.cuota"
                      :label="'cuota '+concepto.nombre"
                      v-validate="'required|decimal'"
                      data-vv-name="cuota"
                      :data-vv-as="'cuota '+concepto.nombre"
                      :error-messages="errors.collect(concepto.nombre)"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="createOrEdit">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

      <v-toolbar flat color="white">
        <v-toolbar-title v-if="grado !== null">Asignación de cuotas {{grado.grado.nombre+' '+grado.nivel_educativo.nombre | lowercase()}}</v-toolbar-title>
        <v-divider class="mx-2" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="1000px" persistent>
          <template v-slot:activator="{ on }">
            <v-btn @click="nuevo" color="primary" small dark class="mb-2" v-on="on">
              <v-icon>add</v-icon> Asignar
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline" v-if="grado !== null">Nuevas cuotas {{grado.grado.nombre+' '+grado.nivel_educativo.nombre | lowercase()}} </span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                  <h4 v-if="form_create.forms.length == 0"> ya se han registrado todas las cuotas</h4>
                <v-layout wrap>
                  <v-flex xs6 sm3 md3 v-for="f in form_create.forms" :key="f.id">
                    <v-text-field
                      v-model="f.cuota"
                      :label="f.nombre"
                     v-validate="'required|decimal'"
                      :data-vv-name="f.nombre"
                      :data-vv-as="'cuota '+f.nombre"
                      :error-messages="errors.collect(f.nombre)"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click="close">Volver</v-btn>
              <v-btn color="blue darken-1" flat @click="createOrEdit" v-if="form_create.forms.length > 0">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-data-table :headers="headers" :items="items" :search="search" class="elevation-1">
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.concepto_pago.nombre }}</td>
          <td class="text-xs-left">{{ props.item.cuota | currency('Q ') }}</td>
          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="warning" @click="mapData(props.item)" fab dark>edit</v-icon>
              </template>
              <span>Editar</span>
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
  name: "ConfigurarCuota",
  props: {
    source: String
  },
  data() {
    return {
      dialog: false,
      dialog_edit: false,
      search: "",
      loading: false,
      ciclo_id: null,
      grado_nivel_educativo_id: null,
      items: [],
      conceptos: [],
      concepto: null,
      grado: null,
      headers: [
        { text: "Concepto pago", value: "concepto_pago" },
        { text: "Cuota", value: "cuota" },
        { text: "Acciones", value: "", sortable: false }
      ],

      form: {
          id: null,
          cuota: 0
      },

      form_create: {
        forms: []
      },

      itemsB: []
    };
  },

  created() {
    let self = this
    self.ciclo_id = self.$route.params.id
    self.grado_nivel_educativo_id = self.$route.params.grado_id
    self.getCuotas(self.grado_nivel_educativo_id, self.ciclo_id)
    self.getGrado(self.grado_nivel_educativo_id)
    self.setBreadCrub()
  },

  methods: {
      setBreadCrub(){
          let self = this
          self.itemsB.push({text: "CICLOS ESCOLARES",disabled: false,href: "#/ciclo"},
                          {text: "CUOTAS", disabled: false, href: "#/configurar_cuota/"+self.$route.params.id},
                          {text: "ASIGNACIÓN CUOTAS",disabled: true,href: "#"}
        )
      },

      getCuotas(id,ciclo_id){
            let self = this
            self.loading = true
            let data = self.form
            self.$store.state.services.gradoNivelEducativoService
            .getCuotasCiclo(id,ciclo_id)
            .then(r => {
                self.loading = false
                self.items = r.data
                self.getConceptos()
            }).catch(r => {});
        },

        getGrado(id){
            let self = this
            self.loading = true
            let data = self.form
            self.$store.state.services.gradoNivelEducativoService
            .get(id)
            .then(r => {
                self.loading = false
                self.grado = r.data
            }).catch(r => {});
        },

         getConceptos(){
            let self = this
            self.loading = true
            let data = self.form
            self.$store.state.services.conceptoPagoService
            .getAll()
            .then(r => {
                self.loading = false
                self.conceptos = r.data
            }).catch(r => {});
        },

         //funcion para guardar registro
        create(){
            let self = this
            self.loading = true
            let data = self.form_create

            self.$store.state.services.cuotaService
                .create(data)
                .then(r => {
                self.loading = false
                if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                this.$toastr.success('registro agregado con éxito', 'éxito')
                self.getCuotas(self.$route.params.grado_id,self.ciclo_id)
                self.close()

                })
                .catch(r => {});
        },

         //funcion para actualizar registro
        update(){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.cuotaService
            .update(data)
            .then(r => {
            self.loading = false
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                return
            }
            self.getCuotas(self.$route.params.grado_id,self.ciclo_id)
            this.$toastr.success('registro actualizado con éxito', 'éxito')
            self.close()
            })
            .catch(r => {});
        },

        setForm(conceptos){
            let self = this
            self.form_create.forms = []
            conceptos.forEach(item => {
                var existe = self.items.some(x=>x.concepto_pago_id === item.id)
                if(!existe){
                    self.form_create.forms.push({
                        cuota: 0,
                        ciclo_id: self.ciclo_id,
                        nombre: item.nombre,
                        concepto_pago_id: item.id,
                        grado_nivel_educativo_id: self.grado_nivel_educativo_id
                    })
                }
            })
        },

        mapData(data){
            let self = this
            self.form.id = data.id
            self.form.cuota = data.cuota
            self.concepto = self.conceptos.find(x=>x.id == data.concepto_pago_id)
            self.dialog_edit = true
        },

        createOrEdit(){
          let self = this
          this.$validator.validateAll().then(result => {
                if (result) {
                    self.form.id == null ? self.create() : self.update()
                }
            });
        },

        nuevo(){
            let self = this
            self.dialog = true
            self.setForm(self.conceptos)
        },

        //limpiar data de formulario
        clearData() {
        let self = this;

        Object.keys(self.form).forEach(function(key, index) {
            if (typeof self.form[key] === "string") self.form[key] = '';
            else if (typeof self.form[key] === "boolean") self.form[key] = true;
            else if (typeof self.form[key] === "number") self.form[key] = true;
        });
        self.forms = []
        self.$validator.reset();
        },

      close(){
          let self = this
          self.dialog = false
          self.dialog_edit = false
          self.clearData()
      }
  },
  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? "Editar Cuota" : "Agregar Cuota";
    }
  }
};
</script>
