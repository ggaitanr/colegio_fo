class PagoService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = `${baseUrl}pagos`
    }

    getAll() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    get(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}`);
    }

    getPagosParciales(id) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${id}/pagos_parciales`);
    }

    getByFechas(inicio, fin) {
        let self = this
        return self.axios.get(`${self.baseUrl}_reporte/${inicio}/${fin}`)
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

    //peticion a funcion create
    comprobante(id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_comprobante/${id}`, { responseType: 'blob' });
    }

    //comprobante abono
    comprobanteAbono(id) {
        let self = this
        return self.axios.get(`${self.baseUrl}_parciales_comprobante/${id}`, { responseType: 'blob' });
    }
}

export default PagoService