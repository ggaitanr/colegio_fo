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
                  <el-tabs type="border-card">
                    <el-tab-pane>
                      <span slot="label"><i class="el-icon-edit"></i> Alumno</span>
                      
                      <v-card-title>
                      <span class="headline">{{form.primer_nombre}} {{form.segundo_nombre}}
                                              {{form.primer_apellido}} {{form.segundo_apellido}}
                      </span>
                      </v-card-title>
                        <v-form> 
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
                                                            <v-icon v-on="on" color="primary" @click="$refs.file.click()">insert_photo</v-icon> Cambiar fotografía
                                                        </template>
                                                        <span>Seleccionar fotografía de alumno</span>
                                                    </v-tooltip>
                                                  <input v-show="false" @change="selectedFile" ref="file" class="input-file hidden" type="file" accept="image/*" />
                                              </div>
                                            </v-flex>

                                            <v-flex xs12 md12 lg12>
                                              <v-layout column>
                                                  <v-avatar
                                                    :tile="true"
                                                    size="200"
                                                    color="blue lighten-4"
                                                    >
                                                    <img :src="image" />
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
                                          :error-messages="errors.collect('codigo')"
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
                                      :error-messages="errors.collect('primer_nombre')"
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
                                      :error-messages="errors.collect('primer_apellido')"
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
                                              hint="MM/DD/YYYY format"
                                              v-model="dateFormatted"
                                              label="Fecha de nacimiento"
                                              prepend-icon="event"
                                              v-on="on"
                                              v-validate="'required'"
                                              data-vv-name="fecha_nacimiento"
                                              :error-messages="errors.collect('fecha_nacimiento')"
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
                                      label="Genero"
                                      item-value="value"
                                      item-text="text"
                                      data-vv-name="genero"
                                      :error-messages="errors.collect('genero')"
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
                                      :error-messages="errors.collect('email')"
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
                          <v-layout justify-end>
                              <v-btn small color="primary" justify-end @click="beforeEdit"><v-icon>save</v-icon> Guardar</v-btn>
                          </v-layout>
                    </el-tab-pane>
                    <el-tab-pane>
                      <span slot="label"><i class="el-icon-s-custom"></i> Apoderados</span>
                      <apoderado></apoderado>
                    </el-tab-pane>
                  </el-tabs>
    </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment'
import Apoderado from './Apoderado'
export default {
  name: "AlumnoEdit",
  components: {
    Apoderado
  },
  props: {
      source: String
    },
  data() {
     return {
            loading: false,
            menu2: false,
            menu3: false,
            tile: false,
            image: null,
            alumno: {},
            avatarSize: 130,
            itemsB: [{
                    text: 'ALUMNOS',
                    disabled: false,
                    href: '#/alumno_index',
                },
                {
                    text: 'EDITAR ALUMNO',
                    disabled: true,
                }
            ],
            generos: [{text: 'Masculino',value: 'M'},{text: 'Femenino',value: 'F'}],

            form: {
                id: '',
                codigo: '',
                primer_nombre: '',
                segundo_nombre: '',
                tercer_nombre: '',
                primer_apellido: '',
                segundo_apellido: '',
                fecha_nac: '',
                genero: '',
                telefono: '',
                email: '',
                direccion: '',
                enfermedades: '',
                alergias: '',
                foto: ''
            }
        };
  },

  created() {
    let self = this
    var id = self.$route.params.id
    self.get(id)
  },

  methods: {

    get(id){
        let self = this
        self.loading = true
        let data = self.form
        self.$store.state.services.alumnoService
        .get(id)
        .then(r => {
            self.loading = false
            if(r.response){
                this.$toastr.error(r.response.data.error, 'error')
                return
            }
            self.alumno = r.data
            events.$emit('apoderado_alumno',self.alumno)
            self.map(r.data)

        }).catch(r => {});

    },

    //funcion para editar registro
    update(){
      let self = this
      self.loading = true
      let data = self.form
      self.$store.state.services.alumnoService
        .update(data)
        .then(r => {
          self.loading = false
          if(r.response){
            this.$toastr.error(r.response.data.error, 'error')
            return
          }
          this.$toastr.success('registro editado con éxito', 'éxito')
          self.$router.push('/alumno_index')
          self.clearData()

        })
        .catch(r => {});
    },

    //setear datos de apoderado
    map(data){
        let self= this
        self.form.id = data.id
        self.form.codigo = data.codigo
        self.form.primer_nombre = data.primer_nombre
        self.form.segundo_nombre = data.segundo_nombre
        self.form.tercer_nombre = data.tercer_nombre
        self.form.primer_apellido = data.primer_apellido
        self.form.segundo_apellido = data.segundo_apellido
        self.form.fecha_nac = data.fecha_nac
        self.form.email = data.email
        self.form.direccion = data.direccion
        self.form.telefono = data.telefono
        self.form.alergias = data.alergias
        self.form.enfermedades = data.enfermedades
        self.form.genero = data.genero
        data.foto !== null ? self.image = self.$store.state.base_url+data.foto : self.$store.state.base_url+'img/user_empty.png'
    },

    //limpiar data de formulario
    clearData(){
        let self = this
        self.$validator.reset()
    },

    //funcion, validar si se guarda o actualiza
    beforeEdit(){
      let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
                self.update()
          }
      });
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

      //console.log(files)
    },

    cancelar(){
      let self = this
    }
  },

  computed: {
      dateFormatted(){
          let self = this
          return moment(self.form.fecha_nac).format('DD/MM/YYYY')
      }
  },
};
</script>
