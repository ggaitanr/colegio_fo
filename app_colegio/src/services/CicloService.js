class CicloService {
    axios
    baseUrl
    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}ciclos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getInscripciones(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/inscripciones`);
    }

    getPagos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/pagos`);
    }

    getData(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}_get_data/${id}`);
    }

    actual() {
        let self = this;
        return self.axios.get(`${self.baseUrl}_actual`);
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
export default CicloService