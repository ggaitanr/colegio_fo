import Vue from 'vue'
import Vuex from 'Vuex'
import services from './services'
import global from '../components/sharedjs/global'
import moment from 'moment'
import auth from '../auth'
import Axios from 'axios'

Vue.use(Vuex)

const state = {
    services,
    global,
    menu: [],
    permisos: [],
    usuario: {},
    ciclo: {},
    institucion: {},
    token: null,
    is_login: false,
    token_expired: null,
    client_id: 2,
    //base_url: 'http://www.colegio-fa.com/',
    base_url: 'http://207.154.253.69/colegio-fo/',
    client_secret: 'KYp7cNlNYM72dmwCHBPVboZTjdr1YjhPf3mGPH3W'
}

const mutations = {
    setUser(state, usuario) {
        state.usuario = usuario
    },

    setInstitucion(state, institucion) {
        state.institucion = institucion
    },

    setCiclo(state, ciclo) {
        state.ciclo = ciclo
    },

    setToken(state, token) {
        state.token = token
        state.is_login = true
    },

    logout(state) {
        state.is_login = false
        state.token = null
    },

    setState(state) {
        state.is_login = false
        state.token = null
    },

    setTokenExpired(state, time) {
        state.token_expired = time
    },

    setMenu(state, menu) {
        state.menu = menu
    },

    setPermisos(state, permisos) {
        state.permisos = permisos
    },
}

const actions = {
    guardarToken({ commit }, data_user) {
        Axios.defaults.headers.common.Authorization = `Bearer ${data_user.access_token}`
        commit("setToken", data_user.access_token)
        $cookies.set('token_data', data_user)
    },

    logout({ commit }) {
        $cookies.remove('token_data')
        commit("logout")
    },

    autoLogin({ commit }) {
        let token = $cookies.get('token_data')
        if (token) {
            commit('setToken', token)
            auth.getUser()
            auth.getCicloActual()
        } else {
            commit('setState')
        }
    },

    setUser({ commit }, user) {
        commit('setUser', user)
    },

    setInstitucion({ commit }, institucion) {
        commit('setInstitucion', institucion)
    },

    setCiclo({ commit }, ciclo) {
        commit('setCiclo', ciclo)
    },

    setMenu({ commit }, menu) {
        commit('setMenu', menu)
    },

    setPermisos({ commit }, permisos) {
        commit('setPermisos', permisos)
    },
}

export default new Vuex.Store({
    state,
    mutations,
    actions
})