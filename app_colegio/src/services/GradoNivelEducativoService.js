class GradoNivelEducativoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}gradoNivelEducativos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getCuotas(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/cuotas`);
    }

    getSecciones(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}Secciones`);
    }

    getCuotasCiclo(id, ciclo_id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}Ciclo/${id}/${ciclo_id}`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    createCurso(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}Cursos`, data);
    }

    createSeccion(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}Secciones`, data);
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`, data);
    }

    destroy(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }

    destroySeccion(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}Secciones/${data.id}`);
    }

    destroyCurso(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}Cursos/${data.id}`);
    }
}

export default GradoNivelEducativoService