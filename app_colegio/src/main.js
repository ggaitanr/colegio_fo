// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'

import Vue2Filters from 'vue2-filters'
Vue.use(Vue2Filters)

import Vuetify from 'vuetify'
Vue.use(Vuetify, {
  iconfont: 'md',
  lang: {
    locales: {
        es
    },
      current: 'es'
  }
})
// App
import App from './App'

// Vue Router
import router from './router'

// Vuex: for services, shared components, etc
import store from './store/index'

import 'vuetify/src/stylus/app.styl'
import VuetifyConfirm from 'vuetify-confirm'
Vue.use(VuetifyConfirm)


//validators
import VeeValidate from 'vee-validate'
const VueValidationEs = require('vee-validate/dist/locale/es')
import _ from 'lodash'

const config = {
  locale: 'es',
  validity: true,
  dictionary: {
    es: VueValidationEs
  },
  fieldsBagName: 'campos',
  errorBagName: 'errors', // change if property conflicts
};

Vue.use(VeeValidate, config);

import es from 'vuetify/src/locale/es.ts'

import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

// Element Ui
import Element from 'element-ui'
import locale from 'element-ui/lib/locale/lang/es'

//import toastr
import VueToastr2 from 'vue-toastr-2'
import 'vue-toastr-2/dist/vue-toastr-2.min.css'

// Our Style
import '../static/style.css'
window.toastr = require('toastr')
 
Vue.use(VueToastr2)
store.dispatch('autoLogin')


// MomentJs for Vue
const moment = require('moment')
require('moment/locale/es')
Vue.use(require('vue-moment'), {
    moment
})



Vue.use(Element, {
  locale
})

// set default config
VueCookies.config('7d')

Vue.config.productionTip = false
window.events = new Vue();

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: {
    App
  }
})