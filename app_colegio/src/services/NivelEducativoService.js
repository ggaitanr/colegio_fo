class NivelEducativoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}niveles_educativos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getGrados(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/grados`);
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

export default NivelEducativoService