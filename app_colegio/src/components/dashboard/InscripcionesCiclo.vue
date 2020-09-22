<script>
import { Bar } from "vue-chartjs";

export default {
  extends: Bar,
  data() {
    return {
      data: null,
      dataset: {
        label: "Inscripciones",
        backgroundColor: "#f39c12",
        borderWidth: 1,
        data: [],
        backgroundColor: []
      },

      chartData: {
        labels: [],
        datasets: []
      },
      options: {}
    };
  },

  created() {
    let self = this;
    self.options = {
      responsive: true,
      maintainAspectRatio: false,
      title: {
        display: true,
        text: "Inscripciones por grado"
      },
      legend: {
        display: false
      },

      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
              // Include a dollar sign in the ticks
              callback: function(value, index, values) {
                return value
              }
            }
          }
        ]
      }
    };

    events.$on("dashboard_inscripciones", self.onEventCiclo);
  },

  beforeDestroy() {
    let self = this;
    events.$off("dashboard_inscripciones", self.onEventCiclo);
  },

  methods: {
    onEventCiclo(data) {
      let self = this;
      self.data = data;
      self.get(data.id);
    },

    get(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.cicloService
        .getInscripciones(id)
        .then(r => {
          self.loading = false
          self.loadData(r.data)
        })
        .catch(r => {});
    },

    loadData(data) {
      let self = this;
      var items = self.orderData(data)
      items.forEach(item => {
        self.chartData.labels.push(item.nombre)
        self.dataset.data.push(item.inscripciones)
        self.dataset.backgroundColor.push(self.getRandomColor())
      });

      self.chartData.datasets.push(self.dataset);
      self.renderChart(self.chartData, self.options);
    },

    orderData(data) {
      let self = this;
      var items = _(data)
        .groupBy("grado_nivel_educativo_id")
        .map(function(items, grado_nivel_educativo_id) {
          var grado_nivel = items.find(
            x =>
              x.grado_nivel_educativo_id === parseInt(grado_nivel_educativo_id)
          );
          return {
            grado_nivel_educativo_id: parseInt(grado_nivel_educativo_id),
            nombre: grado_nivel.grado_nivel_educativo.grado.nombre + " " + grado_nivel.grado_nivel_educativo.nivel_educativo.nombre,
            inscripciones: items.filter( x => grado_nivel_educativo_id === grado_nivel_educativo_id).length
          };
        })
        .value();

      return items;
    },

    totalAmount(data) {
      data = data.filter(x => !x.anulado);
      var pagos = [];
      data.forEach(d => {
        pagos = [...pagos, ...d.pagos];
      });

      var total = pagos.reduce(function(a, b) {
        return a + parseFloat(b.total);
      }, 0);

      return total;
    },
    getRandomColor() {
      var letters = "0123456789ABCDEF".split("");
      var color = "#";
      for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    }
  },

  mounted() {
    let self = this;
    //this.renderChart(self.chartData, self.options)
  }
};
</script>
