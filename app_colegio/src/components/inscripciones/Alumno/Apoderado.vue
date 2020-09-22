<template>
  <v-layout align-start v-loading="loading">
      <v-flex sm12 lg12 md12>
      <v-toolbar flat color="white">
        <v-toolbar-title>Apoderados de {{alumno.primer_nombre}} {{alumno.segundo_nombre}}
                                              {{alumno.primer_apellido}} {{alumno.segundo_apellido}} </v-toolbar-title>
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
        <v-dialog persistent v-model="dialog" max-width="1000px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" small dark class="mb-2" v-on="on"><v-icon>add</v-icon> Nuevo</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline"> </span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex>
                            <v-layout wrap>
                                <v-flex xs12 md12 lg12>
                                    
                                    <v-card-title>
                                    <span class="headline">{{setTitle}}
                                    </span>
                                    
                                    </v-card-title>
                                </v-flex>
                                <v-flex xs12 md12 lg12>
                                        <v-form> 
                                            <v-container>
                                            <v-layout wrap>
                                                <v-flex xs12 md3 lg3
                                                >
                                                <v-text-field
                                                    v-model="form.cui"
                                                    label="Cui"
                                                    v-validate="'required|min:13|max:15'"
                                                    type="text"
                                                    placeholder="ingrese cui"
                                                    data-vv-name="cui"
                                                    :error-messages="errors.collect('cui')"
                                                    prepend-icon="code"
                                                ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 md4>
                                                    <v-autocomplete
                                                        v-model="form.municipio_id"
                                                        label="Extendido en"
                                                        placeholder="Departamento / Municipio"
                                                        :items="municipios"
                                                        item-text="nombre"
                                                        item-value="id"
                                                        >
                                                        </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 md2 lg2 v-if="form.id === null">
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn @click="getApoderado(form.cui)" :disabled="form.cui.length < 13" v-on="on" color="primary" small>
                                                            <v-icon>check_circle</v-icon> validar
                                                            </v-btn>
                                                        </template>
                                                        <span>Validar si existe representante</span>
                                                    </v-tooltip>
                                                </v-flex>
                                                <v-flex xs12 md2 lg2
                                                >
                                                <v-text-field
                                                    v-model="form.nit"
                                                    label="Nit"
                                                    type="text"
                                                    placeholder="ingrese nit"
                                                    prepend-icon="code"
                                                ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md3 lg3>
                                                    <v-checkbox
                                                        v-model="form.responsable"
                                                        label="Asignar como responsable"
                                                        ></v-checkbox>
                                                </v-flex>

                                                <v-flex xs12 md4>
                                                    <v-select
                                                    placeholder="seleccione parentesco"
                                                    prepend-icon="person_add"
                                                    v-model="form.tipo_apoderado"
                                                    v-validate="'required'"
                                                    :items="tipo_apoderados"
                                                    :error-messages="errors.collect('tipo_apoderado')"
                                                    label="Parentesco"
                                                    item-value="value"
                                                    item-text="text"
                                                    data-vv-name="tipo_apoderado"
                                                    required
                                                    ></v-select>
                                                </v-flex>
                                                
                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="add"
                                                    v-model="form.primer_nombre"
                                                    label="Primer nombre"
                                                    v-validate="'required'"
                                                    type="text"
                                                    placeholder="ingrese primer nombre"
                                                    data-vv-name="primer_nombre"
                                                    :error-messages="errors.collect('primer_nombre')"
                                                ></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 md4>
                                                    <v-text-field
                                                        :readonly="exists"
                                                        prepend-icon="add"
                                                        v-model="form.segundo_nombre"
                                                        placeholder="ingrese segundo nombre"
                                                        label="Segundo nombre"
                                                        type="text"
                                                    ></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="add"
                                                    v-model="form.primer_apellido"
                                                    label="Primer apellido"
                                                    v-validate="'required'"
                                                    placeholder="ingrese primer apellido"
                                                    type="text"
                                                    data-vv-name="primer_apellido"
                                                    :error-messages="errors.collect('primer_apellido')"
                                                ></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="add"
                                                    v-model="form.segundo_apellido"
                                                    placeholder="ingrese segundo apellido"
                                                    label="Segundo apellido"
                                                    type="text"
                                                ></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 sm6 md4>
                                                    <v-menu
                                                        :readonly="exists"
                                                        v-model="menu3"
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
                                                            :readonly="exists"
                                                            v-model="dateFormatted"
                                                            label="Fecha de nacimiento"
                                                            placeholder="ingrese fecha de nacimiento"
                                                            prepend-icon="event"
                                                            v-on="on"
                                                            v-validate="'required'"
                                                            data-vv-name="fecha_nacimiento"
                                                            :error-messages="errors.collect('fecha_nacimiento')"
                                                        ></v-text-field>
                                                        </template>
                                                        <v-date-picker 
                                                        :readonly="exists" locale="es" v-model="form.fecha_nac" @input="menu3 = false"></v-date-picker>
                                                    </v-menu>
                                                    </v-flex>

                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="contact_phone"
                                                    v-model="form.telefono"
                                                    placeholder="ingrese telefono de contacto"
                                                    label="Télefono"
                                                    type="text"
                                                ></v-text-field>
                                                </v-flex>

                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="email"
                                                    v-model="form.email"
                                                    label="email"
                                                    placeholder="ingrese correo electronico"
                                                    v-validate="'email'"
                                                    data-vv-name="email"
                                                    :error-messages="errors.collect('email')"
                                                    type="email"
                                                ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md4>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="add"
                                                    v-model="form.ocupacion"
                                                    label="Profesión u oficio"
                                                    placeholder="ingrese profesion"
                                                    type="text"
                                                ></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 md6>
                                                    <v-text-field
                                                        :readonly="exists"
                                                        prepend-icon="add"
                                                        v-model="form.estado_civil"
                                                        label="Estado civil"
                                                        placeholder="ingrese estado civil"
                                                        v-validate="'required'"
                                                        data-vv-name="estado_civil"
                                                        :error-messages="errors.collect('estado_civil')"
                                                        type="text"
                                                    ></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 md6>
                                                    <v-text-field
                                                        :readonly="exists"
                                                        prepend-icon="add"
                                                        v-model="form.nacionalidad"
                                                        label="Nacionalidad"
                                                        placeholder="ingrese nacionalidad"
                                                        v-validate="'required'"
                                                        data-vv-name="nacionalidad"
                                                        :error-messages="errors.collect('nacionalidad')"
                                                        type="text"
                                                    ></v-text-field>
                                                    </v-flex>

                                                <v-flex xs12 md12>
                                                <v-text-field
                                                    :readonly="exists"
                                                    prepend-icon="format_textdirection_l_to_r"
                                                    v-model="form.direccion"
                                                    placeholder="ingrese residencia"
                                                    label="Residencia"
                                                    type="text"
                                                    v-validate="'required'"
                                                    data-vv-name="residencia"
                                                    :error-messages="errors.collect('residencia')"
                                                ></v-text-field>
                                                </v-flex>

                                            </v-layout>
                                            </v-container>
                                        </v-form>
                                </v-flex>
                            </v-layout>
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
      </v-toolbar>
            <v-data-table
                :headers="headers"
                :items="items"
                :search="search"
                :expand="false"
                class="elevation-1"
                disable-initial-sort
            >
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-left">
                            <v-icon @click="props.expanded = !props.expanded">{{!props.expanded ? 'keyboard_arrow_right' : 'keyboard_arrow_down' }} </v-icon>
                        {{ props.item.apoderado.cui }}</td>
                
                        <td class="text-xs-left">{{ props.item.apoderado.primer_nombre }} {{ props.item.apoderado.segundo_nombre }} 
                                                    {{ props.item.apoderado.primer_apellido }} {{ props.item.apoderado.segundo_apellido }}</td>
                        <td class="text-xs-left">{{ setEdad(props.item.apoderado.fecha_nac) }} años
                        <td class="text-xs-left">{{props.item.tipo_apoderado === 'P' ? 'Padre' : props.item.tipo_apoderado === 'M' ? 'Madre' : 'Representante'}}
                            
                            <v-chip small color="indigo" text-color="white" v-if="props.item.responsable">
                                <v-avatar>
                                    <v-icon>account_circle</v-icon>
                                </v-avatar>
                                Responsable
                            </v-chip>
                        </td>
                        <td class="text-xs-left">
                            {{concatTels(props.item.apoderado.telefonos)}}
                        </td>
                        <td class="text-xs-left">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-icon color="warning" @click="edit(props.item)" v-on="on"> edit</v-icon>
                                </template>
                                <span>Editar</span>
                            </v-tooltip>
                            <v-tooltip top v-if="!props.item.responsable">
                                <template v-slot:activator="{ on }">
                                    <v-icon color="error" @click="destroy(props.item)" v-on="on"> remove_circle</v-icon>
                                </template>
                                <span>Quitar </span>
                            </v-tooltip>
                        </td>

                    </tr>
                
                </template>
                <template v-slot:expand="props">
                    <v-card flat>
                    <v-card-text>
                        <hr /><hr />
                        <v-container>
                            <v-layout wrap>
                                <v-flex xs12 md12 lg12>
                                    <h3>{{ props.item.apoderado.primer_nombre }} {{ props.item.apoderado.segundo_nombre }} 
                                                    {{ props.item.apoderado.primer_apellido }} {{ props.item.apoderado.segundo_apellido }}</h3>
                                                    <hr />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                    <b>Cui: </b> {{props.item.apoderado.cui}}<br />
                                    <b>Nit: </b> {{props.item.apoderado.nit}}<br />
                                    <b>Parentesco:</b> {{props.item.tipo_apoderado === 'P' ? 'Padre' : props.item.tipo_apoderado === 'M' ? 'Madre' : 'Representante'}}<br />
                                    <b>Edad:</b> {{setEdad(props.item.apoderado.fecha_nac) }} años<br />
                                    <b>Correo electronico:</b> {{props.item.apoderado.email}}<br />
                                    <b>Dirección:</b> {{props.item.apoderado.direccion}}<br />
                                    <b>Profesión u oficio:</b> {{props.item.apoderado.ocupacion}}<br />
                                    <b>Estado civil:</b> {{props.item.apoderado.estado_civil}}<br />
                                    <b>Nacionalidad:</b> {{props.item.apoderado.nacionalidad}}<br />
                                </v-flex>
                                <v-flex xs12 md6 lg6>
                                            <v-flex xs12 md12 lg12>
                                                <form data-vv-scope="form_telefono">
                                                    
                                                 <v-layout wrap>
                                                    <v-flex xs12 md6>
                                                        <v-select
                                                        placeholder="seleccione tipo telefono"
                                                        v-model="form_t.tipo_telefono"
                                                        v-validate="'required'"
                                                        :items="tipo_telefonos"
                                                        :error-messages="errors.collect('form_telefono.tipo_telefono')"
                                                        label="Tipo"
                                                        item-value="value"
                                                        item-text="text"
                                                        data-vv-as="tipo telefono"
                                                        data-vv-name="tipo_telefono"
                                                        required
                                                        ></v-select>
                                                    </v-flex>
                                                    <v-flex xs12 md6>
                                                        <v-text-field
                                                            v-model="form_t.telefono"
                                                            label="Telefono"
                                                            v-validate="'required'"
                                                            data-vv-name="telefono"
                                                            :error-messages="errors.collect('form_telefono.telefono')"
                                                            append-icon="add"
                                                            @click:append="addTelefonos(props.item.apoderado)"
                                                            required
                                                        ></v-text-field>
                                                    </v-flex>
                                                 </v-layout>
                                                </form>
                                            </v-flex>
                                    
                                    <h3> Telefonos <v-icon color="primary" small> contact_phone</v-icon></h3> <hr />
                                    <div v-for="tel in props.item.apoderado.telefonos">
                                        <b style="margin-right: 25px; padding: 10px;">{{tel.tipo_telefono == 'P' ? 'Celular:   ': tel.tipo_telefono == 'C' ? 'Casa:   ' : 'Oficina:   '}} 
                                            {{tel.telefono}}
                                        </b>
                                        <v-icon small color="error" @click="destroyTelefono(tel)"> remove_circle</v-icon>
                                        <hr />
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                                <hr /><hr />
                    </v-card-text>
                    </v-card>
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
export default {
  name: "AlumnoEdit",
  props: {

    },
  data() {
     return {
            search: '',
            dialog: false,
            loading: false,
            menu2: false,
            menu3: false,
            tile: false,
            exists: false,
            items: [],
            municipios: [],
            tipo_apoderados: [{text: 'Padre',value: 'P'},
                              {text: 'Madre',value: 'M'},
                              {text: 'Representante',value: 'R'}
            ],
            tipo_telefonos: [{text: 'Celular',value: 'P'},
                              {text: 'Casa',value: 'C'},
                              {text: 'Oficina',value: 'O'}
            ],
            headers: [
                { text: 'Cui', value: 'cui'},
                { text: 'Nombres', value: 'primer_nombre' },
                { text: 'Edad', value: 'fecha_nac' },
                { text: 'Parentesco', value: 'tipo_apoderado' },
                { text: 'Telefonos', value: 'telefonos' },
                { text: 'Acciones', value: '', sortable: false }
            ],
            alumno: {},
            form: {
                id: null,
                apoderado_id: null,
                cui: '',
                codigo: '',
                nit: '',
                primer_nombre: '',
                segundo_nombre: '',
                primer_apellido: '',
                segundo_apellido: '',
                fecha_nac: new Date().toISOString().substr(0, 10),
                telefono: '',
                email: '',
                direccion: '',
                tipo_apoderado: '',
                responsable: false,
                alumno_id: null,
                municipio_id: null,
                estado_civil: '',
                nacionalidad: ''
            },

            form_t: {
                tipo_telefono: '',
                apoderado_id: null,
                telefono: ''
            }
        };
  },

  created() {
    let self = this
    var id = self.$route.params.id
    events.$on('apoderado_alumno',self.onEventAlumno)
    self.get(id)
    self.getMunicipios()
  },

  beforeDestroy(){
      let self = this
      events.$off('apoderado_alumno',self.onEventAlumno)
  },

  methods: {
    onEventAlumno(alumno){
        let self = this
        self.alumno = alumno
    },

    get(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .getApoderados(id)
        .then(r => {
            self.loading = false
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                return
            }
            self.items = r.data
            var padre = self.items.some(e => e.tipo_apoderado === 'P')
            /*if(padre){
                var index = self.tipo_apoderados.findIndex(t => t.value === 'P')
                self.tipo_apoderados.splice(index,1)
            }
            var madre = self.items.some(e => e.tipo_apoderado === 'M')
            if(madre){
                var index = self.tipo_apoderados.findIndex(t => t.value === 'M')
                self.tipo_apoderados.splice(index,1)
            }*/

        }).catch(r => {});

    },

    getApoderado(cui){
        let self= this
        self.loading = true
        self.$store.state.services.apoderadoService
            .get(cui)
            .then(r => {
                self.loading = false
                if(r.response){
                    self.exits = false
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                self.exists = true
                self.map(r.data)
            }).catch(e=>{})
    },

    getMunicipios(){
        let self= this
        self.loading = true
        self.$store.state.services.municipioService
            .getAll()
            .then(r => {
                self.loading = false
                if(r.response){
                    self.exits = false
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                r.data.map(obj=> ({ ...obj.nombre = obj.departamento.nombre+' / '+obj.nombre }))
                self.municipios = r.data
            }).catch(e=>{})
    },

    //funcion para editar registro
    create(){
      let self = this
      let data = self.form
      data.alumno_id = self.$route.params.id
      var exists = self.items.some(e => e.apoderado.cui === data.cui)
      if(exists){
          self.$toastr.error('apoderado ya fue asignado a este alumno','error')
          return
      }
      self.loading = true
      self.$store.state.services.apoderadoAlumnoService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro editado con éxito', 'éxito')
          self.get(self.alumno.id)
          self.clearData()
          self.dialog = false

        })
        .catch(r => {});
    },

    update(){
      let self = this
      let data = self.form
      data.alumno_id = self.$route.params.id
      self.loading = true
      self.$store.state.services.apoderadoAlumnoService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro actualizado con éxito', 'éxito')
          self.get(data.alumno_id)
          self.clearData()
          self.dialog = false

        })
        .catch(r => {});
    },

    //setear datos de apoderado
    map(data){
        let self= this
        self.form.apoderado_id = data.id
        self.form.cui = data.cui
        self.form.nit = data.nit
        self.form.primer_nombre = data.primer_nombre
        self.form.segundo_nombre = data.segundo_nombre
        self.form.primer_apellido = data.primer_apellido
        self.form.segundo_apellido = data.segundo_apellido
        self.form.fecha_nac = data.fecha_nac
        self.form.email = data.email
        self.form.direccion = data.direccion
        self.form.ocupacion = data.ocupacion
        self.form.estado_civil = data.estado_civil
        self.form.nacionalidad = data.nacionalidad
        self.form.municipio_id = data.municipio_id
        self.form.telefono= data.telefonos[0].telefono
    },

    mapApoderadoAlumno(data){
        let self = this
        self.form.id = data.id
        self.form.tipo_apoderado = data.tipo_apoderado
        self.form.apoderado_id = data.apoderado_id
        self.form.responsable = !!(data.responsable)
        self.form.tipo_apoderado = data.tipo_apoderado
    },

    edit(data){
        let self = this
        self.dialog = true
        self.mapApoderadoAlumno(data)
        self.map(data.apoderado)
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

    //funcion para eliminar registro
    destroy(data){
      let self = this
      self.$confirm('Seguro que desea eliminar apoderado'+ data.apoderado.cui+'?').then(res => {
        self.loading = true
            self.$store.state.services.apoderadoAlumnoService
            .destroy(data)
            .then(r => {
                self.loading = false
                if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                this.$toastr.success('registro eliminado con exito', 'exito')
                self.get(data.alumno_id)
                self.close()
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
    },

    //funcion, validar si se guarda o actualiza
    createOrEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
                self.form.id === null ? self.create() : self.update()
          }
      });
    },

    setEdad(date){
      return moment().diff(date, 'years');
    },

    concatTels(tels){
        return tels.map(e => e.telefono).join(", ")
    },

    addTelefonos(apoderado){
        let self = this
        this.$validator.validateAll('form_telefono').then((result) => {
            if (result) {
                self.form_t.apoderado_id = apoderado.id
                self.createTelefono()
            }
        });
    },

        //funcion para crear  telefono
    createTelefono(){
      let self = this
      let data = self.form_t
      self.loading = true
      self.$store.state.services.telefonoApoderadoService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('telefono a sido registrado', 'éxito')
          self.get(self.$route.params.id)
          self.form_t.tipo_telefono = ''
          self.form_t.telefono = ''
          self.$validator.reset()
        })
        .catch(r => {});
    },

        //funcion para eliminar telefono
    destroyTelefono(data){
      let self = this
      self.$confirm('Seguro que desea eliminar telefono '+ data.telefono+'?').then(res => {
        self.loading = true
            self.$store.state.services.telefonoApoderadoService
            .destroy(data)
            .then(r => {
                self.loading = false
                if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                this.$toastr.success('telefono ah sido removido con exito', 'exito')
                self.get(self.$route.params.id)
                self.close()
            })
            .catch(r => {});

      }).catch(cancel =>{

      })
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
      dateFormatted(){
          let self = this
          return moment(self.form.fecha_nac !== '' ? self.form.fecha_nac : moment()).format('DD/MM/YYYY')
      },
      setTitle(){
        let self = this
        return self.form.id !== null ? self.form.primer_nombre+' '+self.primer_apellido : 'Nuevo Registro'
    }
  },
};
</script>
