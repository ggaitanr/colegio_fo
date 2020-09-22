<template>
  <v-layout align-start>
    <v-flex>
        <v-card>
            <v-card-title class="headline red darken-4">
              <h4 class="white--text">Buscar alumno por codigo o nombre</h4>
            </v-card-title>
            <v-card-text>
              <v-layout row wrap>
                <v-flex sm12 lg12 md12>
                  <v-text-field v-model="search" append-icon="search" label="Buscar" @keyup="filterSearch" single-line hide-details></v-text-field>
                </v-flex>
                
                <v-subheader v-if="items.length === 0"> ninguna coincidencia</v-subheader>
                <v-flex xs12 sm6 md4 lg4
                v-loading="loading" 
                  element-loading-text="Buscando alumnos..."
                  element-loading-spinner="el-icon-loading"
                  element-loading-background="rgba(0, 0, 0, 0.8)"
                 v-for="item in items" :key="item.id">
                  <v-container>
                  <v-card @click="$router.push(`/pago_alumno/`+item.id)">
                      <v-avatar
                          :tile="true"
                          size="200"
                          color="blue lighten-4"
                          >
                          <img :src="getAvatar(item.foto)" />
                      </v-avatar>

                      <v-card-title primary-title>
                        <div>
                          <h3 class="headline mb-0">{{item.primer_nombre}} {{item.segundo_nombre}} 
                                                    {{item.primer_apellido}} {{item.segundo_apellido}}</h3>
                          <div>Direccion: {{ item.direccion }} </div>
                        </div>
                      </v-card-title>

                      <v-card-actions>
                        <v-btn flat color="success" @click="$router.push(`/pago_alumno/`+item.id)">
                          <v-icon color="success" fab dark> money</v-icon>
                          Pagos
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-container>
                </v-flex>
              </v-layout>
            </v-card-text>
           
          </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SelectAlumno",
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      isLoading: false,
      search: '',
      timeout: null,
      items: []
    };
  },

  created() {
    let self = this
  },

  methods: {
    filterSearch () {
      let self = this
      clearTimeout(self.timeout)
      self.timeout = setTimeout(() => {
        self.getAll();
      }, 700);	
    },

    getAll() {
      let self = this
      self.loading = true
      self.$store.state.services.alumnoService
        .searchQuery(self.search)
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    getAvatar(foto){
      let self = this
      return foto !== null ? self.$store.state.base_url+foto : self.$store.state.base_url+'img/user_empty.png'
    }
  },

  computed: {
    },
};
</script>