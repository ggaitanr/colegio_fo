class ApoderadoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}apoderados`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(cui) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${cui}`);
    }

    getTelefonos(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/telefonos`);
    }

    create(data) {
        let self = this;
        return self.axios.post(`${self.baseUrl}`, data);
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`,data);
    }

    destroy(data){
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }
}

export default ApoderadoService