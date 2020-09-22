class ConceptoPago {
    axios
    baseUrl
    constructor(axios, baseUrl) {
        this.axios = axios;
        this.baseUrl = `${baseUrl}concepto_pagos`
    }
    getAll(){
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id){
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`)
    }

    getCuotas(id){
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/cuotas`);
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
export default ConceptoPago