<template>
  <v-layout align-start v-loading="loading">
    <v-flex>
      <v-layout row wrap justify-end>
        <div>
          <v-breadcrumbs :items="itemsB">
            <template v-slot:divider>
              <v-icon>forward</v-icon>
            </template>
          </v-breadcrumbs>
        </div>
      </v-layout>
      <v-toolbar-title>Configurar cuotas para ciclo escolar {{ciclo.ciclo}}</v-toolbar-title>
      <v-data-table :headers="headers" :items="items" :search="search" class="elevation-1">
        <template v-slot:items="props">
          <td class="text-xs-left">{{ props.item.grado.nombre+' '+props.item.nivel_educativo.nombre }}</td>
          <td class="text-xs-left">
            <v-tooltip top>
              <template v-slot:activator="{ on }">
                  <v-icon v-on="on" color="success" fab dark @click="$router.push(`/asignar_cuota/`+ciclo.id+'-'+props.item.id)">money</v-icon>
              </template>
              <span>Asignar cuotas a grado</span>
            </v-tooltip>
          </td>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="getAll">Reset</v-btn>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>
<script>
export default {
  name: "ConfigurarCuota",
  props: {
    source: String
  },
  data() {
    return {
      dialog: false,
      dialogEdit: false,
      search: "",
      loading: false,
      items: [],
      ciclo: {},
      headers: [
        { text: "Grado", value: "grado" },
        { text: "Acciones", value: "", sortable: false }
      ],
      itemsB: [
        {
          text: "CICLOS ESCOLARES",
          disabled: false,
          href: "#/ciclo"
        },
        {
          text: "CUOTAS",
          disabled: true,
          href: "#"
        }
      ],
      form: 
        {
          id: null,
          concepto_pago_id: null,
          ciclo_id: null,
          cuota: null,
          grado_nivel_educativo_id: null
        }
    };
  },

  created() {
    let self = this
    self.getCiclo(self.$route.params.id)
    self.getGradoNivel()
  },

  methods: {
    getAll(id) {
      let self = this;
      self.loading = true;

      self.$store.state.services.conceptoPagoService
        .getCuotas(id)
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },

    getGradoNivel(){
      let self = this
      self.loading = true
      self.$store.state.services.gradoNivelEducativoService
        .getAll()
        .then(r => {
          self.loading = false
          self.items = r.data
        })
        .catch(r => {});
    },
    
    
    getCiclo(id) {
      let self = this;
      self.loading = true;
      self.$store.state.services.cicloService
        .get(id)
        .then(r => {
          self.loading = false
          self.ciclo = r.data
          //self.setNivelEducativosCuotas(r.data.cuotas)
        })
        .catch(r => {});
    },

    setNivelEducativosCuotas(cuotas){
      let self = this
      self.items = _(cuotas)
          .groupBy('grado_nivel_educativo_id')
          .map(function(items, grado_nivel_educativo_id) {
           var grado_nivel = items.find(x=>x.grado_nivel_educativo_id === parseInt(grado_nivel_educativo_id))
          return {
              grado_nivel_educativo_id: parseInt(grado_nivel_educativo_id),
              nombre: grado_nivel.grado_nivel_educativo.grado.nombre+' '+grado_nivel.grado_nivel_educativo.nivel_educativo.nombre,
              cuotas: items.filter(x=>x.grado_nivel_educativo_id === parseInt(grado_nivel_educativo_id)),
          };
      }).value();
    },
    
    getNivelEducativo() {
      let self = this;
      self.loading = true;
      self.$store.state.services.nivelEducativoService
        .getAll()
        .then(r => {
          self.loading = false;
          self.nivel_educativo = r.data;
        })
        .catch(r => {});
    },
    

    create() {
      let self = this;
      self.loading = true;
      let data = self.form;
      self.$store.state.services.cuotaService
        .create(data)
        .then(r => {
          self.loading = false;
          if (r.response) {
            this.$toastr.error(r.response.data.error, "error");
            return;
          }
          this.$toastr.success("registro agregado con éxito", "éxito");
          self.dialog = false;
        })
        .catch(r => {});
    }, //mapear datos a formulario

    mapData(data) {
      let self = this;
      self.form.id = data.id;
      self.form.concepto_pago_id = data.concepto_pago_id;
      self.form.ciclo_id = data.ciclo_id;
      self.form.cuota = data.form.cuota;
      self.form.grado_nivel_educativo_id = data.grado_nivel_educativo_id;
    },
    
    createOrEdit() {
      this.$validator.validateAll().then(result => {
        if (result) {
          if (this.form.id > 0 && this.form.id != null) {
            self.update();
          } else {
            self.create();
          }
        }
      });
      let self = this;
    },

    clearData() {
      let self = this;

      Object.keys(self.form).forEach(function(key, index) {
        if (typeof self.form[key] === "string") self.form[key] = "";
        else if (typeof self.form[key] === "boolean") self.form[key] = true;
        else if (typeof self.form[key] === "number") self.form[key] = null;
      });
    },
    close() {
      let self = this;
      self.dialog = false;
      self.clearData();
    }
  },
  
  computed: {
    setTitle() {
      let self = this;
      return self.form.id !== null ? "Editar Cuota" : "Agregar Cuota";
    }
  }
};
</script>
