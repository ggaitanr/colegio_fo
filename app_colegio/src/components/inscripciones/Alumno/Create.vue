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
        <v-stepper v-model="datos" vertical>
            <v-card-title>
              <span class="headline">Nuevo alumno</span>
            </v-card-title>
            <v-stepper-step step="1" v-bind:complete="datos > 1">
                Datos del alumno
                <small>Ingresar información relacionada con los datos del nuevo alumno alumno</small>
            </v-stepper-step>

            <v-stepper-content step="1">
                <v-form data-vv-scope="form_alumno" > 
                    <v-container fluid grid-list-md>
                    <v-layout row wrap>
                        <v-flex d-flex xs12 sm6 md4>
                                    <v-layout>
                                      <v-container>
                                          <v-layout row wrap>
                                            <v-flex>
                                              <div id="uploader">
                                                  <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-icon v-on="on" color="primary" @click="$refs.file.click()">insert_photo</v-icon> Cargar fotografía
                                                        </template>
                                                        <span>Seleccionar fotografía de alumno</span>
                                                    </v-tooltip>
                                                  <input v-show="false" @change="selectedFile" class="input-file hidden" ref="file" type="file" accept="image/*" />
                                              </div>
                                            </v-flex>

                                            <v-flex xs12 md12 lg12>
                                              <v-layout column>
                                                  <v-avatar
                                                    :tile="true"
                                                    size="200"
                                                    color="blue lighten-4"
                                                    >
                                                    <img :src="image !== null ? image : image_default" />
                                                </v-avatar>
                                              </v-layout>
                                            </v-flex>
                                          </v-layout>
                                        </v-container>
                                    </v-layout>
                                  </v-flex>
                        <v-flex d-flex xs12 sm6 md8>
                                  <v-layout row wrap>
                                      <v-flex xs12 md6>
                                      <v-text-field
                                          placeholder="ingrese codigo alumno"
                                          v-model="form.codigo"
                                          label="Codigo"
                                          v-validate="'required'"
                                          type="text"
                                          data-vv-name="codigo"
                                          :error-messages="errors.collect('form_alumno.codigo')"
                                          prepend-icon="code"
                                      ></v-text-field>
                                  </v-flex>
                                  <v-flex xs12 md6>
                                  <v-text-field
                                      placeholder="ingrese primer nombre"
                                      prepend-icon="add"
                                      v-model="form.primer_nombre"
                                      label="Primer nombre"
                                      v-validate="'required'"
                                      type="text"
                                      data-vv-name="primer_nombre"
                                      :error-messages="errors.collect('form_alumno.primer_nombre')"
                                  ></v-text-field>
                                  </v-flex>
                                  
                                  <v-flex xs12 md6>
                                      <v-text-field
                                          placeholder="ingrese segundo nombre"
                                          prepend-icon="add"
                                          v-model="form.segundo_nombre"
                                          label="Segundo nombre"
                                          type="text"
                                      ></v-text-field>
                                  </v-flex>

                                  <v-flex xs12 md4>
                                  <v-text-field
                                      placeholder="ingrese tercer nombre"
                                      prepend-icon="add"
                                      v-model="form.tercer_nombre"
                                      label="Tercer nombre"
                                      type="text"
                                  ></v-text-field>
                                  </v-flex>

                                  <v-flex xs12 md6>
                                  <v-text-field
                                      placeholder="ingrese primer apellido"
                                      prepend-icon="add"
                                      v-model="form.primer_apellido"
                                      label="Primer apellido"
                                      v-validate="'required'"
                                      type="text"
                                      data-vv-name="primer_apellido"
                                      :error-messages="errors.collect('form_alumno.primer_apellido')"
                                  ></v-text-field>
                                  </v-flex>

                                  <v-flex xs12 md6>
                                  <v-text-field
                                      placeholder="ingrese segundo apellido"
                                      prepend-icon="add"
                                      v-model="form.segundo_apellido"
                                      label="Segundo apellido"
                                      type="text"
                                  ></v-text-field>
                                  </v-flex>

                                  </v-layout>
                                </v-flex>

                        <v-flex xs12 sm6 md4>
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
                                    placeholder="ingrese fecha nacimiento"
                                    clearable
                                    readonly
                                    v-model="dateFormatted"
                                    label="Fecha de nacimiento"
                                    prepend-icon="event"
                                    v-on="on"
                                    v-validate="'required'"
                                    data-vv-name="fecha_nacimiento"
                                    :error-messages="errors.collect('form_alumno.fecha_nacimiento')"
                                ></v-text-field>
                                </template>
                                <v-date-picker 
                                locale="es" v-model="form.fecha_nac" @input="menu2 = false"></v-date-picker>
                            </v-menu>
                            </v-flex>

                        <v-flex xs12 md4>
                            <v-select
                            placeholder="seleccione genero"
                            prepend-icon="person_add"
                            v-model="form.genero"
                            v-validate="'required'"
                            :items="generos"
                            :error-messages="errors.collect('form_alumno.genero')"
                            label="Genero"
                            item-value="value"
                            item-text="text"
                            data-vv-name="genero"
                            required
                            ></v-select>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            placeholder="ingrese tel+efono"
                            prepend-icon="contact_phone"
                            v-model="form.telefono"
                            label="Teléfono"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            placeholder="ingrese email"
                            prepend-icon="email"
                            v-model="form.email"
                            label="email"
                            v-validate="'email'"
                            data-vv-name="email"
                            :error-messages="errors.collect('form_alumno.email')"
                            type="email"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md8>
                        <v-text-field
                            placeholder="ingrese dirección"
                            prepend-icon="format_textdirection_l_to_r"
                            v-model="form.direccion"
                            label="Dirección"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                            <v-textarea
                            placeholder="especifique padecimiento de enfermedades"
                            prepend-icon="highlight_off"
                            v-model="form.enfermedades"
                            label="Enfermedades(Especifique)"
                            ></v-textarea>
                        </v-flex>

                        <v-flex xs6>
                            <v-textarea
                            placeholder="especifique padecimiento de alergias"
                            prepend-icon="highlight_off"
                            v-model="form.alergias"
                            label="Alergias(Especifique)"
                            ></v-textarea>
                        </v-flex>

                    </v-layout>
                    </v-container>
                </v-form>

                <v-btn color="primary" small dark @click.native="beforeCreate('form_alumno')"><v-icon>keyboard_arrow_right</v-icon>Siguiente</v-btn>

            </v-stepper-content>

            <v-stepper-step step="2" v-bind:complete="datos > 2">
                Datos del responsable
                <small>Ingresar información relacionada con los datos del responsable </small>
            </v-stepper-step>

            <v-stepper-content step="2">
                <v-form data-vv-scope="form_representante"> 
                    <v-container>
                    <v-layout wrap>
                        <v-flex xs12 md4
                        >
                        <v-text-field
                            v-model="form.cui"
                            label="Cui"
                            v-validate="'required|min:13|max:15'"
                            type="text"
                            placeholder="ingrese cui"
                            data-vv-name="cui"
                            :error-messages="errors.collect('form_representante.cui')"
                            prepend-icon="code"
                        ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md4 lg4
                        >
                        <v-text-field
                            v-model="form.nit"
                            label="Nit"
                            type="text"
                            placeholder="ingrese nit"
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
                        <v-flex xs12 md4>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn @click="getApoderado(form.cui)" :disabled="form.cui.length < 13" v-on="on" color="primary" small>
                                    <v-icon>check_circle</v-icon> validar
                                    </v-btn>
                                </template>
                                <span>Validar si existe representante</span>
                            </v-tooltip>
                        </v-flex>

                        <v-flex xs12 md4>
                            <v-select
                            placeholder="seleccione tipo apoderado"
                            prepend-icon="person_add"
                            v-model="form.tipo_apoderado"
                            v-validate="'required'"
                            :items="tipo_apoderados"
                            :error-messages="errors.collect('form_representante.tipo_apoderado')"
                            label="Tipo apoderado"
                            item-value="value"
                            item-text="text"
                            data-vv-name="tipo_apoderado"
                            required
                            ></v-select>
                        </v-flex>
                        
                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.primer_nombre_a"
                            label="Primer nombre"
                            v-validate="'required'"
                            type="text"
                            placeholder="ingrese primer nombre"
                            data-vv-name="primer_nombre"
                            :error-messages="errors.collect('form_representante.primer_nombre')"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                            <v-text-field
                                :readonly="form.representante_id !== null"
                                prepend-icon="add"
                                v-model="form.segundo_nombre_a"
                                placeholder="ingrese segundo nombre"
                                label="Segundo nombre"
                                type="text"
                            ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.primer_apellido_a"
                            label="Primer apellido"
                            v-validate="'required'"
                            placeholder="ingrese primer apellido"
                            type="text"
                            data-vv-name="primer_apellido"
                            :error-messages="errors.collect('form_representante.primer_apellido')"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.segundo_apellido_a"
                            placeholder="ingrese segundo apellido"
                            label="Segundo apellido"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md4>
                            <v-menu
                                :disabled="form.representante_id !== null"
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
                                    :readonly="form.representante_id !== null"
                                    v-model="dateFormattedR"
                                    label="Fecha de nacimiento"
                                    placeholder="ingrese fecha de nacimiento"
                                    prepend-icon="event"
                                    v-on="on"
                                    v-validate="'required'"
                                    data-vv-name="fecha_nacimiento"
                                    :error-messages="errors.collect('form_representante.fecha_nacimiento')"
                                ></v-text-field>
                                </template>
                                <v-date-picker 
                                :readonly="form.representante_id !== null" locale="es" v-model="form.fecha_nac_a" @input="menu3 = false"></v-date-picker>
                            </v-menu>
                            </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="contact_phone"
                            v-model="form.telefono_a"
                            placeholder="ingrese telefono de contacto"
                            label="Télefono celular"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="email"
                            v-model="form.email_a"
                            label="email"
                            placeholder="ingrese correo electronico"
                            v-validate="'email'"
                            data-vv-name="email"
                            :error-messages="errors.collect('form_representante.email')"
                            type="email"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.ocupacion"
                            label="Profesión u oficio"
                            placeholder="ingrese profesion"
                            v-validate="'required'"
                            data-vv-name="profesion"
                            :error-messages="errors.collect('form_representante.profesion')"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md6>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.estado_civil"
                            label="Estado civil"
                            placeholder="ingrese estado civil"
                            v-validate="'required'"
                            data-vv-name="estado_civil"
                            :error-messages="errors.collect('form_representante.estado_civil')"
                            type="text"
                        ></v-text-field>
                        </v-flex>
                        <v-flex xs12 md4>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="add"
                            v-model="form.nacionalidad"
                            label="Nacionalidad"
                            placeholder="ingrese nacionalidad"
                            v-validate="'required'"
                            data-vv-name="nacionalidad"
                            :error-messages="errors.collect('form_representante.nacionalidad')"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                        <v-flex xs12 md12>
                        <v-text-field
                            :readonly="form.representante_id !== null"
                            prepend-icon="format_textdirection_l_to_r"
                            v-model="form.direccion_a"
                            placeholder="ingrese residencia"
                            label="Residencia"
                            type="text"
                        ></v-text-field>
                        </v-flex>

                    </v-layout>
                    </v-container>
                </v-form>
                <v-btn small color="primary" dark @click.native="goBack"><v-icon>keyboard_arrow_left</v-icon> Anterior</v-btn>
                <v-btn small color="primary" dark @click.native="beforeCreate('form_representante')"><v-icon>keyboard_arrow_right</v-icon> Siguiente</v-btn>
            </v-stepper-content>

            <v-stepper-step step="3" v-bind:complete="datos > 3">
                Completo 
                <small>Visualizar y confirmar información ingresada</small>
            </v-stepper-step>
            <v-stepper-content step="3">
                
                <template>
                    <v-layout>
                        <v-flex md6 xs12 lg6>
                            <v-card flat>
                                
                             <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Datos del alumno</h3>
                                </div>
                            </v-card-title>
                                <v-img
                                    :src="form.foto !== '' ? form.foto: image_default"
                                    aspect-ratio="2.75"
                                    >
                                </v-img>
                            <v-card-text> 
                            <b>Nombres:</b> {{form.primer_nombre}} {{form.segundo_nombre}}
                                            {{form.primer_apellido}} {{form.segundo_apellido}}<br />
                            <b>Fecha de nacimiento:</b> {{form.fecha_nac}}<br />
                            <b>Telefono:</b> {{form.telefono}}<br />
                            <b>Correo electronico:</b> {{form.email}}<br />
                            <b>Dirección:</b> {{form.direccion}}<br />
                            <b>Genero:</b> {{form.genero ==='M' ? 'Masculino':'Femenino'}}<br />
                            <b>Alergias:</b> {{form.alergias}}<br />
                            <b>Enfermedades:</b> {{form.enfermedades}}<br />
                            </v-card-text>
                            </v-card>
                        </v-flex>
                        <v-flex md6 xs12 lg6>
                        <v-card flat>
                            <v-card-title primary-title>
                                <div>
                                    <h3 class="headline mb-0">Datos del apoderado</h3>
                                </div>
                            </v-card-title>
                            <v-card-text> 
                            <b>Nombres:</b> {{form.primer_nombre_a}} {{form.segundo_nombre_a}}
                                            {{form.primer_apellido_a}} {{form.segundo_apellido_a}}<br />
                            <b>Tipo apoderado:</b> {{form.tipo_apoderado === 'P' ? 'PADRE' : form.tipo_apoderado === 'M' ? 'MADRE' : 'REPRESENTANTE'}}<br />
                            <b>Fecha de nacimiento:</b> {{form.fecha_nac_a}}<br />
                            <b>Telefono:</b> {{form.telefono_a}}<br />
                            <b>Correo electronico:</b> {{form.email_a}}<br />
                            <b>Dirección:</b> {{form.direccion_a}}<br />
                            <b>Ocupación:</b> {{form.ocupacion}}<br />
                            </v-card-text>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </template>
                <v-flex xs12>
                    <v-btn small dark color="primary" @click="goBack"> <v-icon>keyboard_arrow_left</v-icon> Anterior</v-btn>
                    <v-btn small dark color="primary" @click="create"> <v-icon>check_circle</v-icon> Confirmar</v-btn>
                </v-flex>
            </v-stepper-content>
        </v-stepper>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
export default {
  name: "AlumnoCreate",
  props: {
      source: String
    },
  data() {
     return {
            active: null,
            menu2: false,
            menu3: false,
            datos: 1,
            image:null,
            avatarSize: 130,
            image_default: this.$store.state.base_url+'img/user_empty.png',
            loading: false,
            municipios: [],
            itemsB: [{
                    text: 'ALUMNOS',
                    disabled: false,
                    href: '#/alumno_index',
                },
                {
                    text: 'NUEVO ALUMNO',
                    disabled: true,
                }
            ],
            generos: [{text: 'Masculino',value: 'M'},{text: 'Femenino',value: 'F'}],

            tipo_apoderados: [{text: 'Padre',value: 'P'},
                              {text: 'Madre',value: 'M'},
                              {text: 'Representante',value: 'R'}
            ],

            form: {
                id: '',
                codigo: '',
                primer_nombre: '',
                segundo_nombre: '',
                tercer_nombre: '',
                primer_apellido: '',
                segundo_apellido: '',
                fecha_nac: new Date().toISOString().substr(0, 10),
                genero: '',
                telefono: '',
                email: '',
                direccion: '',
                enfermedades: '',
                alergias: '',
                observaciones: '',
                foto: '',
                representante_id: null,
                cui: '',
                nit: '',
                primer_nombre_a: '',
                segundo_nombre_a: '',
                primer_apellido_a: '',
                segundo_apellido_a: '',
                fecha_nac_a: new Date().toISOString().substr(0, 10),
                email_a: '',
                direccion_a: '',
                ocupacion: '',
                tipo_apoderado: '',
                telefono_a: '',
                municipio_id: null,
                estado_civil: '',
                nacionalidad: ''
            }
        };
  },

  created() {
    let self = this
    self.getMunicipios()
  },

  methods: {
    //funcion para guardar registro
    create(){
      let self = this
      let data = self.form
      self.loading = true
      self.$store.state.services.alumnoService
        .create(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro agregado con éxito', 'éxito')
          self.$router.push('/alumno_index')
          self.clearData()

        })
        .catch(r => {});
    },

    getApoderado(cui){
        let self= this
        self.loading = true
        self.$store.state.services.apoderadoService
            .get(cui)
            .then(r => {
                self.loading = false
                if(r.response){
                    this.$toastr.error(r.response.data.error, 'error')
                    return
                }
                self.mapApoderado(r.data)
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

    //setear datos de apoderado
    mapApoderado(data){
        let self= this
        self.form.representante_id = data.id
        self.form.nit = data.nit
        self.form.primer_nombre_a = data.primer_nombre
        self.form.segundo_nombre_a = data.segundo_nombre
        self.form.primer_apellido_a = data.primer_apellido
        self.form.segundo_apellido_a = data.segundo_apellido
        self.form.fecha_nac_a = data.fecha_nac
        self.form.email_a = data.email
        self.form.direccion_a = data.direccion
        self.form.telefono_a= data.telefonos[0].telefono
        self.form.ocupacion = data.ocupacion
        self.form.estado_civil = data.estado_civil
        self.form.nacionalidad = data.nacionalidad
        self.form.municipio_id = data.municipio_id
    },

    //limpiar data de formulario
    clearData(){
        let self = this


        self.$validator.reset()
    },

    //funcion, validar si se guarda o actualiza
    beforeCreate(scope){
      let self = this
      this.$validator.validateAll(scope).then((result) => {
          if (result) {
             self.datos++
           }
      });
    },
    
    goBack(){
        let self = this
        self.datos--
    },

    cancelar(){
      let self = this
    },

    openDialogFiles() {
      document.querySelector("#uploader .input-file").click()
    },

    selectedFile() {
      let self = this
      var input = document.querySelector("#uploader .input-file")
      var files = input.files
      var oFReader = new FileReader();
      oFReader.readAsDataURL(files[0]);
      oFReader.onload = function (oFREvent) {
          self.image = oFREvent.target.result
          self.form.foto = self.image
      };
    },
  },

  computed: {
    dateFormatted(){
        let self = this
        return moment(self.form.fecha_nac !== '' ? self.form.fecha_nac : moment()).format('DD/MM/YYYY')
    },
    dateFormattedR(){
        let self = this
          return moment(self.form.fecha_nac_a !== '' ? self.form.fecha_nac_a : moment()).format('DD/MM/YYYY')
      }
  },
};
</script>