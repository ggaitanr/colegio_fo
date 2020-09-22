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
      <v-toolbar flat color="white">
        <v-toolbar-title>Asignacion de Grados a {{nivel_academico.nombre}} </v-toolbar-title>
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
        <v-dialog persistent  v-model="dialog" max-width="1200px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" small dark class="mb-2" v-on="on"><v-icon> add</v-icon> Asignación</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{setTitle}}</span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-select
                      v-model="form.grado_id"
                      v-validate="'required'"
                      :items="grados"
                      :error-messages="errors.collect('grado')"
                      label="Grado"
                      item-value="id"
                      item-text="nombre"
                      data-vv-name="grado"
                      required
                    ></v-select>
                </v-flex>
                <v-flex xs12 md6>
                  <span class="headline">Asignar Secciones</span>
                  <v-layout row wrap>
                        <v-flex v-for="(seccion,index) in secciones" :key="seccion[index]" xs2>
                          <v-checkbox light :label="seccion.seccion" :value="seccion.id" v-model="form.secciones">
                          </v-checkbox>
                        </v-flex>
                  </v-layout>
              </v-flex>
                <v-flex xs12 md6>
                  <span class="headline">Asignar Cursos</span>
                  <v-layout row wrap>
                        <v-flex v-for="(curso,index) in cursos" :key="curso[index]" xs4>
                          <v-checkbox light :label="curso.nombre" :value="curso.id" v-model="form.cursos">
                          </v-checkbox>
                        </v-flex>
                  </v-layout>
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

        <v-dialog persistent  v-model="dialogEdit" max-width="800px">
          <v-card>
            <v-card-text>
              
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Secciones y cursos asignados </v-toolbar-title>
              </v-toolbar>
              <v-tabs>
                <v-tab>
                   Secciones
                </v-tab>
                <v-tab>
                    Cursos
                </v-tab>

                <v-tab-item>
                  <v-card flat>
                    <v-card-text> Secciones asignadas </v-card-text>
                    <v-layout wrap>
                      <v-flex xs6 md3>
                        <v-select
                          v-model="formSeccion.seccion_id"
                          :items="secciones"
                          label="Seccion"
                          item-value="id"
                          item-text="seccion"
                        ></v-select>
                    </v-flex>
                    <v-flex md2>
                    <v-btn @click="createSeccion()" color="primary" small dark class="mb-2"><v-icon> add</v-icon></v-btn>
                    </v-flex>
                    </v-layout>
                  <v-spacer></v-spacer>
                      <v-data-table
                          :headers="headersS"
                          :items="grado_nivel.secciones"
                          :search="search"
                          class="elevation-1"
                        >
                          <template v-slot:items="props">
                            <td class="text-xs-left">{{ props.item.seccion.seccion }}</td>
                            <td class="text-xs-left">
                              <v-tooltip top>
                                  <template v-slot:activator="{ on }">
                                      <v-icon v-on="on" @click="destroySeccion(props.item)" color="error" fab dark> remove</v-icon>
                                  </template>
                                  <span>Eliminar</span>
                              </v-tooltip>
                            </td>
                          </template>
                        </v-data-table>
                    </v-card>
                  </v-tab-item>
                  <v-tab-item>
                    <v-card flat>
                      <v-card-text> Cursos asignados</v-card-text>
                      <v-layout wrap>
                      <v-flex xs6 md3>
                        <v-select
                          v-model="formCurso.curso_id"
                          :items="cursos"
                          label="Curso"
                          item-value="id"
                          item-text="nombre"
                        ></v-select>
                    </v-flex>
                    <v-flex md2>
                    <v-btn @click="createCurso()" small color="primary" dark class="mb-2"><v-icon> add</v-icon></v-btn>
                    </v-flex>
                    </v-layout>
                      <v-spacer></v-spacer>
                      <v-data-table
                          :headers="headersC"
                          :items="grado_nivel.cursos"
                          :search="search"
                          class="elevation-1"
                        >
                          <template v-slot:items="props">
                            <td class="text-xs-left">{{ props.item.curso.nombre }}</td>
                            <td class="text-xs-left">
                              <v-tooltip top>
                                  <template v-slot:activator="{ on }">
                                      <v-icon v-on="on" color="error" @click="destroyCurso(props.item)" fab dark> remove</v-icon>
                                  </template>
                                  <span>Eliminar</span>
                              </v-tooltip>
                            </td>
                          </template>
                        </v-data-table>
                    </v-card>
                  </v-tab-item>
                </v-tabs>
    
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red darken-1" flat @click="closeEdit">Volver</v-btn>
              </v-card-actions>
            </v-card-text>
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
          <td class="text-xs-left">{{ props.item.grado.nombre }}</td>
          <td class="text-xs-left">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on"  color="warning" fab dark @click="edit(props.item)"> edit</v-icon>
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
export default {
  name: "NivelEducativo",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      dialogEdit: false,
      search: '',
      loading: false,
      nivel_academico: {},
      grado_nivel: {},
      items: [],
      grados: [],
      cursos: [],
      secciones: [],
      headers: [
        { text: 'Grado', value: 'grado.nombre' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      headersS: [
        { text: 'Seccion', value: '' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      headersC: [
        { text: 'Curso', value: '' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      form: {
        id: null,
        nivel_educativo_id: null,
        grado_id: null,
        cursos: [],
        secciones: []
      },

      formSeccion: {
        grado_nivel_educativo_id: null,
        seccion_id: null
      },

      formCurso: {
        grado_nivel_educativo_id: null,
        curso_id: null
      },

      itemsB: [
        {
          text: 'NIVELES ACADEMICOS',
          disabled: false,
          href: '#/nivel_educativo',
        },
        {
          text: 'ASIGNACION GRADOS',
          disabled: true,
          href: '#',
        },
      ],
    };
  },

  created() {
    let self = this
    self.form.nivel_educativo_id = self.$route.params.id
    self.getNivelAcademico(self.$route.params.id)
    self.getAll(self.$route.params.id)
    self.getGrados()
    self.getCursos()
    self.getSecciones()
  },

  methods: {
    getAll(id) {
      let self = this
      self.loading = true

      self.$store.state.services.nivelEducativoService
        .getGrados(id)
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    getNivelAcademico(id) {
      let self = this
      self.loading = true

      self.$store.state.services.nivelEducativoService
        .get(id)
        .then(r => {
          self.loading = false
          self.nivel_academico = r.data
        })
        .catch(r => {});
    },

    getGrados() {
      let self = this
      self.loading = true

      self.$store.state.services.gradoService
        .getAll()
        .then(r => {
          self.loading = false
          self.grados = r.data
        })
        .catch(r => {});
    },

    getCursos() {
      let self = this
      self.$store.state.services.cursoService
        .getAll()
        .then(r => {
          self.cursos = r.data
        })
        .catch(r => {});
    },

    get(id) {
      let self = this

      self.$store.state.services.gradoNivelEducativoService
        .get(id)
        .then(r => {
          self.grado_nivel = r.data
        })
        .catch(r => {});
    },

    getSecciones() {
      let self = this

      self.$store.state.services.seccionService
        .getAll()
        .then(r => {
          self.secciones = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      self.loading = true
      let data = self.form

      self.$store.state.services.gradoNivelEducativoService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.getAll(data.nivel_educativo_id)
          self.clearData()
          self.dialog = false

        })
        .catch(r => {});
    },
    //funcion para guardar registro
    createCurso(){
      let self = this
      let data = self.formCurso
      if(data.curso_id ===null){
        self.$toastr.error("seleccione un curso",'error');
        return;
      }

      if(self.validateIFexistsCurso(data.curso_id)){
        self.$toastr.error("curso ya fue asignada a grado",'error');
        return;
      }

      data.grado_nivel_educativo_id = self.grado_nivel.id
      self.loading = true
      self.$store.state.services.gradoNivelEducativoService
        .createCurso(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.get(data.grado_nivel_educativo_id)
          self.clearData()
          self.dialog = false

        })
        .catch(r => {});
    },

    //funcion para guardar registro
    createSeccion(){
      let self = this
      let data = self.formSeccion
      data.grado_nivel_educativo_id = self.grado_nivel.id
      if(data.seccion_id ===null){
        self.$toastr.error("seleccione una seccion",'error');
        return;
      }

      if(self.validateIFexistsSeccion(data.seccion_id)){
        self.$toastr.error("seccion ya fue asignada a grado",'error');
        return;
      }

      self.loading = true
      self.$store.state.services.gradoNivelEducativoService
        .createSeccion(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('seccion agregado con éxito', 'éxito')
          self.get(data.grado_nivel_educativo_id)
          self.clearData()
          self.dialog = false

        })
        .catch(r => {});
    },

    validateIFexistsCurso(id){
      let self = this
      var exists = false
      self.grado_nivel.cursos.forEach(function(curso){
        if(curso.curso.id ==id){
          exists = true
        }
      })

      return exists
    },

    validateIFexistsSeccion(id){
      let self = this
      var exists = false
      self.grado_nivel.secciones.forEach(function(seccion){
        if(seccion.seccion.id ==id){
          exists = true
        }
      })

      return exists
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
       
      self.$store.state.services.gradoNivelEducativoService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll()
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.clearData()
          self.dialog = false
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar grado '+ data.grado.nombre+'?').then(res => {
        self.loading = true
            self.$store.state.services.gradoNivelEducativoService
            .destroy(data)
            .then(r => {
                self.loading = false
                self.getAll(data.nivel_educativo_id)
                this.$toastr.success('registro remover con exito', 'exito')
                self.clearData()
                self.dialog = false
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

    //funcion para eliminar secciones
    destroyCurso(data){
      let self = this
      self.$confirm('Seguro que desea remove curso '+ data.curso.nombre+'?').then(res => {
        self.loading = true
            self.$store.state.services.gradoNivelEducativoService
            .destroyCurso(data)
            .then(r => {
                self.loading = false
                self.get(data.grado_nivel_educativo_id)
                this.$toastr.success('registro removido con exito', 'exito')
                self.clearData()
                self.dialog = false
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

     //funcion para eliminar curso
    destroySeccion(data){
      let self = this
      self.$confirm('Seguro que desea eliminar seccion '+ data.seccion.seccion+'?').then(res => {
        self.loading = true
            self.$store.state.services.gradoNivelEducativoService
            .destroySeccion(data)
            .then(r => {
                self.loading = false
                self.get(data.grado_nivel_educativo_id)
                this.$toastr.success('registro removido con exito', 'exito')
                self.clearData()
                self.dialog = false
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

        Object.keys(self.formCurso).forEach(function(key,index) {
          if(typeof self.formCurso[key] === "string") 
            self.formCurso[key] = ''
          else if (typeof self.form[key] === "boolean") 
            self.formCurso[key] = true
          else if (typeof self.formCurso[key] === "number") 
            self.formCurso[key] = null
        });

        Object.keys(self.formSeccion).forEach(function(key,index) {
          if(typeof self.formSeccion[key] === "string") 
            self.formSeccion[key] = ''
          else if (typeof self.formSeccion[key] === "boolean") 
            self.formSeccion[key] = true
          else if (typeof self.formSeccion[key] === "number") 
            self.formSeccion[key] = null
        });

        self.$validator.reset()
        self.form.nivel_educativo_id = self.$route.params.id
    },

    //editar registro
    edit(data){
        let self = this
        this.dialogEdit = true  
        self.get(data.id)
    },

    //mapear datos a formulario
    mapData(data){
        let self = this
        self.form.id = data.id
        self.form.nivel_educativo_id = data.nivel_educativo_id
        self.form.grado_id = data.grado_id
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

    closeEdit () {
        let self = this
        self.dialogEdit = false
    }
  },

   computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? 'Editar Grados' : 'Asignar Grados'
    }
  },
};
</script>