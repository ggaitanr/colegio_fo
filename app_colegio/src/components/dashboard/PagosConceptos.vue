<script>
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,
    data(){
      return {
        data: null,
        dataset: {
          label: 'Concepto pago',
          backgroundColor: '#f39c12',
          borderWidth: 1,
          data: [],
          backgroundColor: []
        },

        chartData: {
          labels: [], 
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
          text: 'Resumen de pagos por concepto de pago'
        },
        legend: {
            display: false
         },

         tooltips: {
          callbacks: {
            label: function(tooltipItem, data) {
              return 'Total: ' + self.$store.state.global.formatPrice(tooltipItem.value);
            }
          }
        },
        scales: {
          yAxes: [{
              ticks: {
                  beginAtZero: true,
                  // Include a dollar sign in the ticks
                  callback: function(value, index, values) {
                      return 'Q./ ' + value;
                  }
              }
          }]
       }
     }

     events.$on('dashboard_pagos_conceptos',self.onEventPagos)
    },

    beforeDestroy(){
        let self = this
        events.$off('dashboard_pagos_conceptos',self.onEventPagos)
    },

    methods: {
      onEventPagos(data){
          let self = this
          self.data = data
          self.loadData(data)
      },

      loadData(data) {
          let self = this
          var items = self.orderData(data)
          items.forEach(item => {
              self.chartData.labels.push(item.nombre)
              self.dataset.data.push(item.total)
              self.dataset.backgroundColor.push(self.getRandomColor())
          })

          self.chartData.datasets.push(self.dataset)
          self.renderChart(self.chartData, self.options)
      },

      orderData(data){
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
        },
        getRandomColor() {
            var letters = '0123456789ABCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++ ) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
    }
    },

    mounted () {
      let self = this
      //this.renderChart(self.chartData, self.options)
  
    }
  }
</script>