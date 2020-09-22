<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Usuarios </v-toolbar-title>
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
        <v-dialog v-model="dialog" max-width="800px" persistent>
          <template v-slot:activator="{ on }">
            <v-btn color="primary" small dark class="mb-2" v-on="on"><v-icon>add</v-icon> Nuevo</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{setTitle}}</span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm12 md12>
                    <v-text-field v-model="form.name" 
                        label="Nombre"
                        v-validate="'required'"
                        type="text"
                        data-vv-name="nombre"
                        :error-messages="errors.collect('nombre')">
                    </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                    <v-text-field v-model="form.email" 
                        label="Correo Electronico"
                        v-validate="'required|email'"
                        type="text"
                        data-vv-name="correo"
                        :error-messages="errors.collect('correo')">
                    </v-text-field>
                    </v-flex>
                    <v-flex xs12 sm6 md6>
                    <v-select
                      v-model="form.rol_id"
                      placeholder="seleccion rol usuario"
                      v-validate="'required'"
                      :items="roles"
                      :error-messages="errors.collect('rol')"
                      label="Rol usuario"
                      item-value="id"
                      item-text="rol"
                      data-vv-name="rol"
                      clearable
                    ></v-select>
                  </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm6 md6>
                    <v-text-field v-model="form.password" 
                        ref="password"
                        label="Contraseña"
                        v-validate="'required|min:6'"
                        type="password"
                        data-vv-name="contraseña"
                        :error-messages="errors.collect('contraseña')">
                    </v-text-field>
                    </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm6 md6>
                    <v-text-field v-model="form.password_confirmation" 
                        label="Confirmar Contraseña"
                        v-validate="'required|confirmed:password'" data-vv-as="password"
                        type="password"
                        data-vv-name="confirmar_contraseña"
                        :error-messages="errors.collect('confirmar_contraseña')">
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
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.name }}</td>
          <td class="text-xs-left">{{ props.item.email }}</td>
          <td class="text-xs-left">{{ props.item.rol.rol }}</td>
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
  name: "usuario",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      loading: false,
      items: [],
      roles: [],
      headers: [
        { text: 'Nombre', value: 'name' },
        { text: 'Correo electronico', value: 'email' },
        { text: 'Rol', value: 'rol.rol' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      form: {
        id: null,
        rol_id: null,
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
    };
  },

  created() {
    let self = this
    self.getAll()
    self.getRoles()
  },

  methods: {
     getAll() {
      let self = this
      self.loading = true

      self.$store.state.services.usuarioService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    getRoles() {
      let self = this
      self.loading = true

      self.$store.state.services.rolService
        .getAll()
        .then(r => {
          self.loading = false
          self.roles = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.usuarioService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.getAll()
          self.clearData()

        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.usuarioService
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
            self.$store.state.services.usuarioService
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
        self.form.name = data.name
        self.form.email = data.email
        self.form.rol_id =data.rol_id
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