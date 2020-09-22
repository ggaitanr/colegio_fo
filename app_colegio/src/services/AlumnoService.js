class AlumnoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}alumnos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    getHistorial() {
        let self = this;
        return self.axios.get(`${self.baseUrl}_historial`);
    }

    getHistorialAlumno(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_historial_alumno/${id}`);
    }

    searchQuery(search) {
        let self = this
        if (search == '') {
            search = "undefined"
        }
        return self.axios.get(`${self.baseUrl}_search/${search}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getApoderados(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/apoderados`);
    }

    getInscripciones(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/inscripciones`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }


    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`, data);
    }

    destroy(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default AlumnoService