<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-toolbar flat color="white">
        <v-tooltip top v-if="items.length > 0">
            <template v-slot:activator="{ on }">
              <v-btn small @click="printReporte" v-on="on"><v-icon>print</v-icon></v-btn>
            </template>
            <span>Imprimir pdf</span>
        </v-tooltip>
        <v-toolbar-title v-if="ciclo !== null">Resumen ciclo escolar {{ciclo.ciclo}} </v-toolbar-title>
          <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
      </v-toolbar>
      <v-layout row wrap>
          <v-flex md6 lg6 xs12>
              <v-card>
                <v-card-title>
                  <h4>Resumen de pagos</h4>
                </v-card-title>
                  <v-card-text>
                    <v-data-table
                        :items="items"
                        class="elevation-1"
                        hide-actions
                        hide-headers
                    >
                        <template v-slot:items="props">
                        <td>{{props.item.text }}</td>
                        <td>{{props.item.total | currency('Q ') }}</td>
                        </template>
                    </v-data-table>
                  </v-card-text>
                  <v-card-actions>
                      <v-spacer></v-spacer>
                      <h3 class="primary--text">Total: {{total(items)| currency('Q ')}}</h3> 
                </v-card-actions>
              </v-card>
          </v-flex>
          
          <v-flex md6 lg6 xs12>
              <v-card>
                <v-card-title>
                  <h4>Resumen de pagos por conceptos de pago</h4>
                </v-card-title>
                  <v-card-text>
                    <v-data-table
                        :items="items_cuotas"
                        class="elevation-1"
                        hide-actions
                        hide-headers
                    >
                        <template v-slot:items="props">
                        <td>{{props.item.nombre }}</td>
                        <td>{{props.item.total | currency('Q ') }}</td>
                        </template>
                    </v-data-table>
                  </v-card-text>
                  <v-card-actions>
                      <v-spacer></v-spacer>
                      <h3 class="primary--text">Total: {{total(items_cuotas)| currency('Q ')}}</h3> 
                </v-card-actions>
              </v-card>
          </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>

<script>
import print from '../reportes/resumen'
import ProgressBar from 'vue-simple-progress'
export default {
  name: "consulta_pago_ciclo",
  components: {
    ProgressBar
  },
  props: {
      source: String
    },
  data() {
    return {
      loading: false,
      data: null,
      items: [],
      items_cuotas: [],
      ciclo: null,
      headers: [
        { text: 'Concepto', value: 'concepto' },
        { text: 'Total', value: 'total' },
      ],

      
    };
  },

  created() {
    let self = this
    events.$on('get_ciclo_data',self.onEventCiclo)
  },

  beforeDestroy(){
      let self = this
      events.$off('get_ciclo_data',self.onEventCiclo)
  },

  methods: {
    onEventCiclo(ciclo){
        let self = this
        self.ciclo = ciclo
        self.getData(ciclo.id)
    },

    getData(id){
        let self = this
        self.loading = true
        self.$store.state.services.cicloService
        .getData(id)
        .then(r => {
            self.loading = false
            if(r.response !== undefined){
              self.$toastr.error(r.response.data.error, 'error')
              return
            }
            self.data = r.data
            self.items_cuotas = self.getItemsConcepto(r.data.pago_cuotas)
            self.items = self.getItems(self.data.resumen)

        }).catch(e => {

        })
    },

    getItems(data){
      let self = this
      var items = []
      items.push({text: 'Total al contado', total: data.total_contado},
                      {text: 'Total al credito', total: data.total_cancelado_credito},
                      {text: 'Adeudos por pagos al credito', total: data.total_credito - data.total_cancelado_credito})
      return items
    },

    getItemsConcepto(data){
        let self = this
        var items = _(data)
          .groupBy('concepto_pago_id')
          .map(function(items, concepto_pago_id) {
           var concepto = items.find(x=>x.concepto_pago_id === parseInt(concepto_pago_id))
          return {
              concepto_pago_id: parseInt(concepto_pago_id),
              nombre: concepto.concepto_pago.nombre,
              total: self.totalAmount(items.filter(x=>x.concepto_pago_id === parseInt(concepto_pago_id))),
          };
      }).value();

        return items
    },

    printReporte(){
      let self = this
      print.print(self.items_cuotas, self.items, self.ciclo, self.data.resumen.total)
    },

    total(data){
      let self = this
      var total = data.reduce(function(a, b) {
            return a + parseFloat(b.total)
        }, 0);

        return total
    },

    totalAmount(data){
        data = data.filter(x=>!x.anulado)
        var pagos = []
        data.forEach(d => {
            pagos = [...pagos, ...d.pagos]
        });

        var total = pagos.reduce(function(a, b) {
            return a + parseFloat(b.total)
        }, 0);

        return total
    }
    
  },

  computed: {
    },
};
</script>

