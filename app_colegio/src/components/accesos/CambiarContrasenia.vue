<template>
  <v-layout align-start>
    <v-flex>
        <v-alert
        v-if="exit"
        :value="true"
        type="warning"
        >
        {{exit_message}}
        </v-alert>
      <v-toolbar flat color="white">
        <v-toolbar-title>Cambiar contraseña </v-toolbar-title>
      </v-toolbar>
          <v-card>
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                    <v-flex v-if="form.id == null" xs12 sm4 md4>
                        <v-text-field v-model="form.old_password" 
                            ref="password"
                            label="Contraseña anterior"
                            v-validate="'required|min:6'"
                            type="password"
                            data-vv-name="contraseña"
                            data-vv-as="contraseña anterior"
                            :error-messages="errors.collect('contraseña')">
                        </v-text-field>
                    </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm4 md4>
                    <v-text-field v-model="form.new_password" 
                        ref="password"
                        label="Nueva contraseña"
                        v-validate="'required|min:6'"
                        type="password"
                        data-vv-name="contraseña"
                        data-vv-as="nueva contraseña"
                        :error-messages="errors.collect('contraseña')">
                    </v-text-field>
                    </v-flex>
                    <v-flex v-if="form.id == null" xs12 sm4 md4>
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
              <v-btn color="red darken-1" flat @click="cancelar">Cancelar</v-btn>
              <v-btn color="blue darken-1" flat @click="beforeChange">Cambiar</v-btn>
            </v-card-actions>
          </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "cambio_contraseña",
  props: {
      source: String
    },
  data() {
    return {
      exit: false,
      exit_message: '',
      form: {
        user_id: '',
        old_password: '',
        new_passoword: '',
        password_confirmation: ''
      },
    };
  },

  created() {
    let self = this
  },

  methods: {

      //funcion para guardar registro
    change(){
      let self = this
      var data = self.form
      data.user_id = self.$store.state.usuario.id
      self.$store.state.services.usuarioService.changePassword(data)
      .then(r=>{
        if(r.response){
          this.$toastr.error(r.response.data.error, 'error')
          return
        }

        self.setTimeClose()
        
      }).catch(e =>{

      })
    },

    setTimeClose(){
      let self = this
      var counter = 5
      self.exit = true
      self.exit_message = 'contraseña ah sido reiniciado correctamente, vuelve a iniciar sessión en '+counter
      var close = setInterval(function()
        { 
            counter --
            if(counter == 0){
                clearInterval(close)
                self.$store.dispatch("logout")
                self.$router.push('/login')
            }
        }, 1000);
    },

    //funcion, validar si se guarda o actualiza
    beforeChange(){
        let self = this
      this.$validator.validateAll().then((result) => {
          if (result) {
              self.change()
           }
      })
    },

    cancelar(){
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
    }
  },

  computed: {
    },
};
</script>