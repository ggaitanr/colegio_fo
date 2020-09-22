<template>
    <v-app id="app" :style="getFondo" style="background-color: #ecf0f5;">
       <!---->
        <!-- =============================================== -->
        <navegationmenu v-if="isLogin"></navegationmenu>
        <v-content>
          <v-container fluid fill-height>
            <v-slide-y-transition mode="out-in">
                <router-view></router-view>
            </v-slide-y-transition>
          </v-container>
        </v-content>

    <v-footer color="red darken-3" class="white--text" v-if="isLogin" app>
      <v-layout align-center justify-center>
        <span class="px-3">colegio {{getName}} &copy; {{ new Date().getFullYear() }}</span>
      </v-layout>
    </v-footer>
      
    </v-app>
</template>

<script>
import navegationmenu from "@/components/shared/NavegationMenu";

export default {
  components: {
    navegationmenu
  },
  data: ()=>({
    is_login: false
  }),

  created(){
    let self = this
    //self.$store.dispatch('autoLogin')
  },
  props: {
    source: String
  },

  methods:{
  },

  computed: {
    
    isLogin(){
      let self = this
      return self.$store.state.is_login
    },

    getName(){
      let self = this
      var name = self.$store.state.institucion.nombre
      return name !== undefined ? name.toLowerCase() : ''
    },

    getFondo(){
        let self = this
        var  fondo = self.$store.state.base_url+'img/fondo.jpg'
        if(!self.$store.state.is_login){
          return {
            "background-image": 'url('+fondo+')'
          } 
        }
      },
  }
};
</script>