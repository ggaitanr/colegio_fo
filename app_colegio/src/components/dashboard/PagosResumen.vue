<script>
import { Bar } from 'vue-chartjs'
import { Pie } from 'vue-chartjs'

export default {
    extends: Pie,
    data(){
      return {
        data: null,
        dataset: {
          label: 'Resumen de pagos',
          backgroundColor: '#f39c12',
          borderWidth: 1,
          data: []
        },

        chartData: {
          labels: ['Total al contado','Total al credito','Adeudos por pagos al credito'], 
          datasets: []
        },
        options: {}
      }
    },

    created(){
      let self = this
      self.options = {
        responsive: true,
        maintainAspectRatio: false,
        title: {
          display: true,
          text: 'Resumen de pagos'
        },
        legend: {
            position: 'bottom'
        },
        tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              var dataLabel = data.labels[tooltipItem.index];
              var value =  self.$store.state.global.formatPrice(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);

              return dataLabel+': '+value;
            }
          }
        },
     }

     events.$on('dashboard_pagos_resumen',self.onEventPagos)
    },

    beforeDestroy(){
        let self = this
        events.$off('dashboard_pagos_resumen',self.onEventPagos)
    },

    methods: {
      onEventPagos(data){
          let self = this
          self.data = data
          self.loadData(data)
      },

      loadData(data) {
          let self = this
          self.dataset.data.push(data.total_contado)
          self.dataset.data.push(data.total_cancelado_credito)
          self.dataset.data.push(data.total_credito - data.total_cancelado_credito)
          self.dataset.backgroundColor = ['green', 'yellow', 'red']

          self.chartData.datasets.push(self.dataset)
          self.renderChart(self.chartData, self.options)
      },

      totalAmout(tarjetas){
        let self = this
        var total = tarjetas.reduce(function(a, b) {
            return a + parseFloat(b.total)
        },0);

        return total.toFixed(2);
      }
    },

    mounted () {
      let self = this
      //this.renderChart(self.chartData, self.options)
  
    }
  }
</script>