class InscripcionService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}inscripciones`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    getAllByGrado(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}?grado_nivel_educativo_id=` + id);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getByFechas(inicio, fin, grado) {
        let self = this
        return self.axios.get(`${self.baseUrl}_reporte/${inicio}/${fin}/${grado}`)
    }

    getPagos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/pagos`);
    }

    getCicloActual(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_ciclo_actual`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    //peticion a funcion create
    updateDocumento(data) {
        let self = this
        return self.axios.post(`${self.baseUrl}_documento`, data,
            { headers: 
                {'Content-Type': 'multipart/form-data' }
            }
        );
    }

    //peticion a funcion create
    getContrato(id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_contrato/${id}`, { responseType: 'blob' });
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

export default InscripcionService