<template>
      <v-container fluid fill-height v-loading="loading">
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-card-title primary-title>
                      <v-layout class="text-xs-center" wrap>
                        <v-flex md12 lg12 xs12>
                          <v-avatar
                            :tile="false"
                            size="90"
                            color="grey lighten-4"
                          >
                            <img :src="logo" alt="avatar">
                          </v-avatar>
                        </v-flex>
                        <v-flex md12 lg12 xs12>
                          <h3>Ingreso al sistema</h3>
                        </v-flex>
                      </v-layout>
                </v-card-title>
              <v-card-text>
                <v-form>
                  <v-text-field v-model="credentials.email" 
                    prepend-icon="person" 
                    name="email" 
                    label="Correo electronico" type="text"
                    v-validate="'required|email'"
                    :error-messages="errors.collect('email')"
                    data-vv-name="email"
                    data-vv-as="correo electronico"
                    required>
                  </v-text-field>

                  <v-text-field 
                      v-model="credentials.password" 
                      id="password" prepend-icon="lock" 
                      name="password" 
                      label="Contraseña" 
                      v-validate="'required|min:6'"
                      type="password"
                      data-vv-name="password"
                      data-vv-as="contraseña"
                      :error-messages="errors.collect('password')">
                  </v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-btn block color="primary" @click="beforeLogin"><v-icon>perm_identity</v-icon> iniciar sesión</v-btn>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
</template>

<script>
import auth from '../../auth'
  export default {
    components: {
    },
    data: () => ({
      drawer: null,
      loading: false,
      credentials: {
          email: '',
          password: ''
      }
    }),
    props: {
      source: String
    },

    methods: {
        login(){
            let self = this
            self.loading = true
            self.$store.state.services.loginService
            .login(self.credentials)
            .then(r => {
                self.loading = false
                if(r.response !== undefined){
                  self.$toastr.error(r.response.data.error, 'error')
                  return
                }
                self.$store.dispatch('guardarToken',r.data)
                self.$router.push('/home')
                auth.getUser()
                auth.getCicloActual()
            }).catch(e => {

            })
        },

        beforeLogin(){
          let self = this
          self.$validator.validateAll().then((result) => {
            if (result) {
                self.login()
              }
          });
        }
    },

    computed: {
      logo(){
        let self = this
        return self.$store.state.global.getLogo()
      }
    }
  }
</script>

