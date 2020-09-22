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
        <v-toolbar-title>Inscripciones alumno {{alumno.primer_nombre}} {{alumno.segundo_nombre}}
                                              {{alumno.primer_apellido}} {{alumno.segundo_apellido}}</v-toolbar-title>

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
        <v-dialog persistent v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }" >
            <v-btn v-if="show_nuevo" color="primary" small dark class="mb-2" v-on="on"><v-icon>add</v-icon> Inscribir ciclo {{ciclo_actual.ciclo}}</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{setTitle}}, {{alumno.primer_nombre}} {{alumno.segundo_nombre}}
                                              {{alumno.primer_apellido}} {{alumno.segundo_apellido}}</span>
            </v-card-title>
             <v-layout row wrap justify-center v-if="form.numero !== ''">
              <v-toolbar-title>Inscripción no: {{form.numero}}</v-toolbar-title>
            </v-layout>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm6 md6>
                    <v-select
                      v-model="nivel_educativo"
                      placeholder="seleccion nivel educativo"
                      v-validate="'required'"
                      :items="nivel_educativos"
                      :error-messages="errors.collect('nivel_educativo')"
                      label="Nivel educativo"
                      item-value="id"
                      item-text="nombre"
                      data-vv-name="nivel_educativo"
                      @input="setGrados"
                      clearable
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 sm6 md6>
                    <v-select
                      v-model="form.grado_nivel_educativo_id"
                      placeholder="seleccione grado"
                      v-validate="'required'"
                      :items="grados"
                      :error-messages="errors.collect('grado')"
                      label="Grado"
                      item-value="id"
                      item-text="grado.nombre"
                      data-vv-name="grado"
                      clearable
                    ></v-select>
                  </v-flex>
                  <v-flex xs12 md6>
                            <v-select
                            placeholder="seleccione jornada"
                            v-model="form.jornada"
                            v-validate="'required'"
                            :items="jornadas"
                            :error-messages="errors.collect('jornada')"
                            label="jornada"
                            item-value="value"
                            item-text="text"
                            data-vv-name="jornada"
                            required
                            ></v-select>
                        </v-flex>
                  <v-flex xs12 sm6 md6>
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
                                    v-model="dateFormatted"
                                    label="Fecha"
                                    placeholder="ingrese fecha de inscripción"
                                    v-on="on"
                                    v-validate="'required'"
                                    data-vv-name="fecha"
                                    :error-messages="errors.collect('fecha')"
                                ></v-text-field>
                                </template>
                                <v-date-picker locale="es" v-model="form.fecha" @input="menu = false"></v-date-picker>
                            </v-menu>
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
        <v-dialog persistent v-model="dialog_documento" max-width="800px">
          <v-card>
            <v-card-title>
              <span class="headline">subir contrato documento </span>
            </v-card-title>
             <v-layout row wrap justify-center v-if="form.numero !== ''">
              <v-toolbar-title>Inscripción no: {{form.numero}}</v-toolbar-title>
            </v-layout>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex>
                    <div id="uploader">
                      <v-tooltip top>
                              <template v-slot:activator="{ on }">
                                  <v-icon v-on="on" color="error" @click="$refs.file.click()">attach_file</v-icon> seleccionar contrato
                              </template>
                              <span>Seleccionar contrato</span>
                          </v-tooltip>
                        <input  v-show="false" @change="selectedDocumento" ref="file" class="input-file hidden" type="file" accept="application/pdf" />
                    
                    </div>
                  </v-flex>
                </v-layout>
                <iframe v-if="file_name !== ''" :src="file_name" style="width: 100%;" height="500"/>
              </v-container>
            </v-card-text>
  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="red darken-1" flat @click="close">Volver</v-btn>
              <v-btn v-if="file_name !== ''" color="blue darken-1" flat @click="updateDocumento">Guardar</v-btn>
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
          <td class="text-xs-left">{{ props.item.numero}}</td>
          <td class="text-xs-left">{{ props.item.ciclo.ciclo }} <v-chip
              v-if="props.item.ciclo.actual"
              small
              color="blue"
              text-color="white"
            >Actual</v-chip></td>
          <td class="text-xs-left">{{ props.item.fecha | moment("dddd DD MMMM, YYYY")}}</td>
          <td class="text-xs-left">{{ props.item.grado_nivel_educativo.grado.nombre }} {{ props.item.grado_nivel_educativo.nivel_educativo.nombre }}</td>
          <td class="text-xs-left">{{ props.item.jornada === 'M' ? 'Matutina' : 'Vespertina'}}</td>
          <td class="text-xs-left">
            <v-tooltip top v-if="props.item.documento !== null">
                <template v-slot:activator="{ on }">
                    <v-icon color="error" v-on="on" @click="descargarContrato(props.item.documento)"> attach_file</v-icon>
                </template>
                <span>imprimir contrato firmado</span>
            </v-tooltip>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon color="error" v-on="on" @click="documento(props.item)"> file_copy</v-icon>
                </template>
                <span>subir contrato</span>
            </v-tooltip>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-icon v-on="on" @click="printContrato(props.item)"> local_printshop</v-icon>
                </template>
                <span>imprimir contrato sin firmar</span>
            </v-tooltip>
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
import moment from 'moment'
import contrato from './contrato.js'
export default {
  name: "InscripcionAlumno",
  props: {
      source: String
    },
  components: {
  },
  data() {
    return {
      dialog: false,
      dialog_documento: false,
      menu: false,
      show_nuevo: true,
      search: '',
      ciclo_actual: {},
      nivel_educativo: {},
      loading: false,
      alumno: {},
      items: [],
      nivel_educativos: [],
      grados: [],
      file_name: '',
      pageCount: 2,
      jornadas: [{text: 'Matutina',value: 'M'},{text: 'Vespertina',value: 'V'}],
      itemsB: [{
              text: 'ALUMNOS',
              disabled: false,
              href: '#/alumno_index',
          },
          {
              text: 'INSCRIPCIONES',
              disabled: true,
          }
      ],
      headers: [
        { text: 'Numero', value: 'numero' },
        { text: 'Ciclo', value: 'ciclo' },
        { text: 'Fecha inscripción', value: 'fecha_inscripcion' },
        { text: 'Grado / Nivel educativo', value: 'nivel_educativo' },
        { text: 'Jornada/Plan', value: 'jornada' },
        { text: 'Acciones', value: '', sortable: false }
      ],

      form: {
        id: null,
        numero: '',
        alumno_id: null,
        ciclo_id: null,
        jornada: '',
        grado_nivel_educativo_id: null,
        documento: '',
        fecha: new Date().toISOString().substr(0, 10)
      },
    };
  },

  created() {
    let self = this
    self.getAlumno(self.$route.params.id)
    self.getCicloActual()
    self.getNivelesEducativos()
  },

  methods: {
    //obtener alumno
    getAlumno(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .get(id)
        .then(r => {
            self.loading = false
            self.alumno = r.data
        }).catch(r => {});
    },

     //obtener ciclo actual
    getCicloActual(id){
        let self = this
        self.loading = true
        self.$store.state.services.inscripcionService
        .getCicloActual(id)
        .then(r => {
            self.loading = false
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                self.$router.push('/alumno_index')
                return
            }
            self.ciclo_actual = r.data
            self.getAll(self.$route.params.id)

        }).catch(r => {});

    },

     getAll(id) {
      let self = this
      self.loading = true

      self.$store.state.services.alumnoService
        .getInscripciones(id)
        .then(r => {
          self.loading = false
          self.items = r.data
          var e = self.items.filter(i=>i.ciclo_id === self.ciclo_actual.id)
          e.length > 0 ? self.show_nuevo = false : self.show_nuevo = true
        })
        .catch(r => {});
    },

     getNivelesEducativos(id) {
      let self = this
      self.loading = true

      self.$store.state.services.nivelEducativoService
        .getAll(id)
        .then(r => {
          self.loading = false
          self.nivel_educativos = r.data
        })
        .catch(r => {});
    },

    //funcion para guardar registro
    create(){
      let self = this
      let data = self.form
      if(self.items.some(i=>i.grado_nivel_educativo_id == data.grado_nivel_educativo_id)){
        self.$toastr.error('no se puede inscribir por que ya esta inscrito a este grado y nivel educativo', 'error')
        return
      }
      data.alumno_id = self.$route.params.id
      data.ciclo_id = self.ciclo_actual.id
      self.loading = true
      self.$store.state.services.inscripcionService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.$alert(self.alumno.primer_nombre+' '+self.alumno.segundo_nombre+' '+self.alumno.primer_apellido+' '+self.alumno.segundo_apellido
                        +" ah sido inscrito con exito al ciclo escolar "
                        +self.ciclo_actual.ciclo +' el codigo de inscripción y contrato generado es '+r.data.numero, 
            'éxito', {
            confirmButtonText: 'OK'
          });
          self.getAll(data.alumno_id)
          self.close()

        })
        .catch(r => {});
    },

    //funcion para actualizar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
      self.$store.state.services.inscripcionService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll(data.alumno_id)
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar inscipción ?').then(res => {
        self.loading = true
            self.$store.state.services.inscripcionService
            .destroy(data)
            .then(r => {
                self.loading = false
                 if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                  }
                
                self.getAll(data.alumno_id)
                this.$toastr.success('inscripción eliminada con exito', 'exito')
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

        self.file_name = ''
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
        self.form.fecha = data.fecha
        self.form.ciclo_id = data.ciclo_id  
        self.form.jornada = data.jornada
        self.nivel_educativo = data.grado_nivel_educativo.nivel_educativo.id
        self.form.grado_nivel_educativo_id = data.grado_nivel_educativo_id
        self.form.alumno_id = data.alumno_id
        self.setGrados(self.nivel_educativo)
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

    //setear grados
    setGrados(nivel){
      let self = this
      var nivel_d = self.nivel_educativos.find(n=>n.id == nivel)
      console.log(nivel_d)
      self.grados = nivel_d.grados
    },

    //imprimir contrato
    printContrato(inscripcion){
      let self = this
      self.loading = true
      self.$store.state.services.inscripcionService
        .getContrato(inscripcion.id)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          const url = window.URL.createObjectURL(new Blob([r.data], { type: 'application/pdf' }));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'inscripcion_'+inscripcion.numero); 
          //link.target = '_blank'
          link.click();
        })
        .catch(r => {});
    },

    documento(item){
      let self = this
      self.$refs.file.value = ''
      self.dialog_documento = true
      self.mapData(item)
    },

    selectedDocumento() {
      let self = this
      var input = document.querySelector("#uploader .input-file")
      var files = input.files
      self.form.documento = files[0]
      var oFReader = new FileReader();
      oFReader.readAsDataURL(files[0]);

      oFReader.onload = function (oFREvent) {
          self.file_name = oFREvent.target.result
      }
    },

    close () {
        let self = this
        self.dialog = false
        self.dialog_documento = false
        self.clearData()
    },

    getFormData(object) {
        const formData = new FormData()
        Object.keys(object).forEach(key => formData.append(key, object[key]))
        return formData;
    },

    updateDocumento(){
      let self = this
      var data = self.getFormData(self.form)
      self.loading = true
      self.$store.state.services.inscripcionService
        .updateDocumento(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          self.getAll(self.alumno.id)
          this.$toastr.success('documento guardado con éxito', 'éxito')
          self.close()
        })
        .catch(r => {});
    },

    descargarContrato(documento){
      let self = this
      var url = self.$store.state.base_url+'documentos/'+documento
      window.open(url, '_blank');
    }
  },

  computed: {
    setTitle(){
      let self = this
      return self.form.id !== null ? self.form.id : 'Nueva inscripcion '+self.ciclo_actual.ciclo
    },

    dateFormatted(){
        let self = this
        return moment(self.form.fecha !== '' ? self.form.fecha : moment()).format('DD/MM/YYYY')
    }
  },
};
</script>