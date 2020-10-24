import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard'

import Default from '@/components/Default'
import ExampleIndex from '@/components/example/Index'
import Login from '@/components/login/Index'
import Curso from '@/components/administracion/Curso'
import Grado from '@/components/administracion/Grado'
import Usuario from '@/components/accesos/Usuario'
import NivelEducativo from '@/components/administracion/NivelEducativo'
import Seccion from '@/components/administracion/Seccion'
import ConfigurarNivel from '@/components/administracion/ConfigurarNivelEducativo.vue'
import ConceptoPago from '@/components/administracion/ConceptoPago.vue'
import Ciclo from '@/components/administracion/Ciclo.vue'
import ConfigurarCuota from '@/components/administracion/ConfigurarCuota.vue'
import AlumnoIndex from '@/components/inscripciones/Alumno/Index'
import AlumnoCreate from '@/components/inscripciones/Alumno/Create'
import AlumnoEdit from '@/components/inscripciones/Alumno/Edit'
import Inscripcion from '@/components/inscripciones/Inscripcion/Inscripcion'
import AsignacionIndex from '@/components/inscripciones/asignacion_seccion/Index'
import SeleccionarAlumno from '@/components/Pagos/SeleccionarAlumno'
import PagoAlumno from '@/components/Pagos/PagoAlumno'
import SerieFactura from '@/components/Pagos/SerieFactura'
import AsignarCuota from '@/components/administracion/AsignarCuotaGrado'
import ConsultaInscripcion from '@/components/consultas/ConsultaInscripcion'
import ConsultaPago from '@/components/consultas/ConsultaPago'
import ConsultaCiclo from '@/components/consultas/ConsultaCiclo'
import AlumnoMoroso from '@/components/consultas/AlumnoMoroso'
import CambiarContrasenia from '@/components/accesos/CambiarContrasenia'
import HistorialAcademico from '@/components/inscripciones/Alumno/HistorialAcademico'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
    return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
    return store.state.is_login ? next('/') : next()
}

//proteger rutas de los sistema, verificar si tiene acceso
const permissionValidations = (to, from, next) => {
    var permisos = store.state.permisos //obtener permisos del usuario
    name = to.name
    var permiso = _.includes(permisos, name) //verificar si permiso existe
    return permiso ? next() : next('/')
}

const routes = [
    { path: '*', redirect: '/' },
    { path: '/', name: 'Default', component: Default, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/login', name: 'Login', component: Login, beforeEnter: multiguard([isLoggedOut]) },
    { path: '/usuario', name: 'Usuario', component: Usuario, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/curso', name: 'Curso', component: Curso, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/grado', name: 'Grado', component: Grado, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/nivel_educativo', name: 'NivelEducativo', component: NivelEducativo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/seccion', name: 'Seccion', component: Seccion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/configurar_nivel/:id', name: 'ConfigurarNivel', component: ConfigurarNivel, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/ciclo', name: 'Ciclo', component: Ciclo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/concepto_pago', name: 'ConceptoPago', component: ConceptoPago, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/configurar_cuota/:id', name: 'ConfigurarCuota', component: ConfigurarCuota, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_index', name: 'AlumnoIndex', component: AlumnoIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_create', name: 'AlumnoCreate', component: AlumnoCreate, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_edit/:id', name: 'AlumnoEdit', component: AlumnoEdit, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/inscripcion/:id', name: 'Inscripcion', component: Inscripcion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignacion_index', name: 'AsignacionIndex', component: AsignacionIndex, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/seleccionar_alumno', name: 'SeleccionarAlumno', component: SeleccionarAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/pago_alumno/:id', name: 'PagoAlumno', component: PagoAlumno, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/serie_factura', name: 'SerieFactura', component: SerieFactura, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/asignar_cuota/:id-:grado_id', name: 'AsignarCuota', component: AsignarCuota, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_inscripcion', name: 'ConsultaInscripcion', component: ConsultaInscripcion, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_pago', name: 'ConsultaPago', component: ConsultaPago, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/consulta_ciclo', name: 'ConsultaCiclo', component: ConsultaCiclo, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/alumno_moroso', name: 'AlumnoMoroso', component: AlumnoMoroso, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
    { path: '/change_password', name: 'CambiarContrasenia', component: CambiarContrasenia, beforeEnter: multiguard([isLoggedIn]) },
    { path: '/historial_academico/:id', name: 'HistorialAcademico', component: HistorialAcademico, beforeEnter: multiguard([isLoggedIn, permissionValidations]) },
]


const router = new Router({ routes })

export default router