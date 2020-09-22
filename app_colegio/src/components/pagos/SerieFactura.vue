<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Serie Facturas </v-toolbar-title>
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
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" small v-on="on"><v-icon>add</v-icon> Nuevo</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{setTitle}}</span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.serie" 
                        label="Serie"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="serie"
                        :error-messages="errors.collect('serie')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.no_facturas" 
                        label="No. Facturas"
                        v-validate="'required|numeric|min_value:1'"
                        type="text"
                        data-vv-name="no_facturas"
                        data-vv-as="numero de facturas"
                        :error-messages="errors.collect('no_facturas')">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 sm4 md4>
                    <v-text-field v-model="form.no_inicial" 
                        label="Iniciar con"
                        v-validate="'required|min_value:1|max_value:'+(form.no_facturas -1) +''"
                        type="text"
                        data-vv-name="no_inicial"
                        data-vv-as="numero inicial"
                        :error-messages="errors.collect('no_inicial')">
                    </v-text-field>
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
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        :pagination.sync="pagination"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.serie }} 
              <v-chip
              v-if="props.item.actual"
              small
              color="blue"
              text-color="white"
            >Actual</v-chip>
          </td>
          <td class="text-xs-left">{{ props.item.no_facturas }}</td>
          <td class="text-xs-left">{{ props.item.no_inicial }}</td>
          <td class="text-xs-left">{{ props.item.no_actual }}</td>
          <td class="text-xs-left">
              <v-tooltip top v-if="props.item.actual">
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on"  color="warning" fab dark @click="edit(props.item)"> edit</v-icon>
                </template>
                <span>Editar</span>
            </v-tooltip>
            <v-tooltip top v-if="props.item.actual">
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
export default {
  name: "serie_factura",
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
        { text: 'Serie', value: 'serie' },
        { text: '# facturas', value: 'no_facturas' },
        { text: '# inical', value: 'no_inicial' },
        { text: '# no_actual', value: 'no_actual' },
        { text: 'Acciones', value: '', sortable: false }
      ],
      pagination: {
        sortBy: 'id'
      },

      form: {
        id: null,
        serie: '',
        no_facturas: null,
        no_inicial: null
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

      self.$store.state.services.serieFacturaService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.serieFacturaService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.getAll()
          self.close()

        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.serieFacturaService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll()
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar curso'+ data.nombre+'?').then(res => {
        self.loading = true
            self.$store.state.services.serieFacturaService
            .destroy(data)
            .then(r => {
                self.loading = false
                self.getAll()
                this.$toastr.success('registro eliminado con exito', 'exito')
                self.close()
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

    //limpiar data de formulario
    clearData(){
        let self = this

        Object.keys(self.form).forEach(function(key,index) {
          if(typeof self.form[key] === "string") 
            self.form[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.form[key] = true
          else if (typeof self.form[key] === "number") 
            self.form[key] = null
        });

        self.$validator.reset()
    },

    //editar registro
    edit(data){
        let self = this
        this.dialog = true
        self.mapData(data)   
    },

    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.serie = data.serie
        self.form.no_facturas = data.no_facturas
        self.form.no_inicial = data.no_inicial
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      this.$validator.validateAll().then((result) => {
          if (result) {
              if(self.form.id > 0 && self.form.id !== null){
                self.update()
              }else{
                self.create()
              }
           }
      });

      let self = this
    },
    

    cancelar(){
      let self = this
    },

    close () {
        let self = this
        self.dialog = false
        self.clearData()
    },

  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? self.form.nombre : 'Nuevo Registro'
    }
  },
};
</script>