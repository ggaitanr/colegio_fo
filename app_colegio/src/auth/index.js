import Axios from 'axios'
import router from '../router'
import store from '../store'
import toastr from 'toastr'

export default {

    data_refresh_token: {
        grant_type: 'refresh_token',
        refresh_token: '',
        client_id: '',
        cliente_secret: ''
    },

    permisos: [],
    permisos_operario: [
        'AlumoInscripciones', 'AlumnoIndex', 'Pagos', 'SerieFactura', 'SeleccionarAlumno', 'Consultas', 'ConsultaInscripcion', 'ConsultaPago', 'ConsultaCiclo',
        'AlumnoMoroso', 'AlumnoCreate', 'AlumnoEdit', 'Inscripcion', 'PagoAlumno', 'CambiarContrasenia'
    ],

    items_admin: [{
            icon: 'settings',
            text: 'Administracion',
            name: 'Administracion',
            model: true,
            path: 'example',
            children: [
                { name: 'Curso', icon: 'add', text: 'Cursos', path: '/curso' },
                { name: 'Grado', icon: 'add', text: 'Grados', path: '/grado' },
                { name: 'Seccion', icon: 'add', text: 'Secciones', path: '/seccion' },
                { name: 'NivelEducativo', icon: 'add', text: 'Niveles Educativos', path: '/nivel_educativo' },
                { name: 'ConceptoPago', icon: 'add', text: 'Conceptos de pago', path: '/concepto_pago' },
                { name: 'Ciclo', icon: 'add', text: 'Ciclos escolares', path: '/ciclo' },
            ]
        },
        {
            icon: 'file_copy',
            text: 'Alumnos e Inscripciones',
            model: true,
            path: '#',
            name: 'AlumoInscripciones',
            children: [
                { name: 'AlumnoIndex', icon: 'add', text: 'Alumnos', path: '/alumno_index' },
            ]
        },
        {
            icon: 'money',
            text: 'Pagos',
            model: true,
            path: '',
            name: 'Pagos',
            children: [
                { name: 'SerieFactura', icon: 'add', text: 'Serie facturas', path: '/serie_factura' },
                { name: 'SeleccionarAlumno', icon: 'add', text: 'Alumno pagos', path: '/seleccionar_alumno' },
            ]
        },
        {
            icon: 'bar_chart',
            text: 'Consultas',
            model: true,
            path: '',
            name: 'Consultas',
            children: [
                { name: 'ConsultaInscripcion', icon: 'add', text: 'Inscripciones', path: '/consulta_inscripcion' },
                { name: 'ConsultaPago', icon: 'add', text: 'Pagos', path: '/consulta_pago' },
                { name: 'ConsultaCiclo', icon: 'add', text: 'Información ciclos', path: '/consulta_ciclo' },
                { name: 'AlumnoMoroso', icon: 'add', text: 'Alumnos morosos', path: '/alumno_moroso' },
            ]
        },
        {
            icon: 'account_box',
            text: 'Accesos',
            model: true,
            path: '',
            name: 'Accesos',
            children: [
                { name: 'Usuario', icon: 'add', text: 'Usuarios', path: '/usuario' },
                { name: 'CambiarContrasenia', icon: 'add', text: 'Cambiar contraseña', path: '/change_password' },
            ]
        }

    ],

    items_operario: [{
            icon: 'file_copy',
            text: 'Alumnos e Inscripciones',
            model: true,
            path: '#',
            name: 'AlumoInscripciones',
            children: [
                { name: 'AlumnoIndex', icon: 'add', text: 'Alumnos', path: '/alumno_index' },
            ]
        },
        {
            icon: 'money',
            text: 'Pagos',
            model: true,
            path: '',
            name: 'Pagos',
            children: [
                { name: 'SerieFactura', icon: 'add', text: 'Serie facturas', path: '/serie_factura' },
                { name: 'SeleccionarAlumno', icon: 'add', text: 'Alumno pagos', path: '/seleccionar_alumno' },
            ]
        },
        {
            icon: 'bar_chart',
            text: 'Consultas',
            model: true,
            path: '',
            name: 'Consultas',
            children: [
                { name: 'ConsultaInscripcion', icon: 'add', text: 'Inscripciones', path: '/consulta_inscripcion' },
                { name: 'ConsultaPago', icon: 'add', text: 'Pagos', path: '/consulta_pago' },
                { name: 'ConsultaCiclo', icon: 'add', text: 'Información ciclos', path: '/consulta_ciclo' },
                { name: 'AlumnoMoroso', icon: 'add', text: 'Alumnos morosos', path: '/alumno_moroso' },
            ]
        },
    ],

    getRefreshToken() {
        var token_data = $cookies.get('token_data')
        this.data_refresh_token.refresh_token = token_data.refresh_token
        this.data_refresh_token.client_id = store.state.client_id,
            this.data_refresh_token.client_secret = store.state.client_secret

        return this.data_refresh_token
    },

    saveToken(token) {
        store.dispatch('guardarToken', token)
    },

    noAcceso() {
        store.dispatch('logout')
        router.push('/login')
    },

    getUser() {
        let self = this
        store.state.services.loginService.me()
            .then(r => {
                store.dispatch('setUser', r.data.user)
                store.dispatch('setInstitucion', r.data.institucion)
                this.getMenus(r.data.user.rol_id)
            }).catch(e => {

            })
    },

    getCicloActual() {
        store.state.services.cicloService.actual()
            .then(r => {
                if (r.response) {
                    toastr.error('no existe ciclo actual, por favor active uno', 'error')
                    router.push('/ciclo')
                    return
                }
                store.dispatch('setCiclo', r.data)
            }).catch(e => {

            })
    },

    getMenus(id) {
        let self = this
        self.loading = true
        store.state.services.rolService
            .getMenus(id)
            .then(r => {
                self.loading = false
                if (r.response !== undefined) {
                    self.$toastr.error(r.response.data.error, 'error')
                    return
                }

                this.mapMenu(r.data)

            }).catch(e => {

            })
    },

    mapMenu(items) {
        var menu = []
        var permissions = []
        items.forEach(function(item) {
            permissions.push(item.name)
            if (item.father === 0 && !item.hide) {
                var object = new Object
                object.icon = item.icon
                object.text = item.text
                object.path = item.path
                object.name = item.name
                object.model = true
                object.children = []
                items.forEach(function(child, i) {
                    if (item.id === child.father && !child.hide) {
                        var object2 = new Object()
                        object2.icon = child.icon
                        object2.text = child.text
                        object2.path = child.path

                        object.children.push(object2)
                    }
                });
                menu.push(object)
            }
        })
        store.dispatch('setMenu', menu)
        store.dispatch('setPermisos', permissions)
    }
}