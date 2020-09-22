<template>
  <v-layout align-start>
    <v-flex>
      <v-toolbar flat color="white">
        <v-toolbar-title>Ejemplo </v-toolbar-title>
        <v-divider
          class="mx-2"
          inset
          vertical
        ></v-divider><v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="800px">
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on"> Nuevo</v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">Nuevo</span>
            </v-card-title>
  
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 sm12 md12>
                    <v-text-field v-model="form.title" label="Title"></v-text-field>
                  </v-flex>
                  <v-flex xs12 sm12 md12>
                    <v-textarea
                        v-model="form.body"
                        auto-grow
                        box
                        color="deep-purple"
                        label="Body"
                        rows="2"
                      ></v-textarea>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>
  
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" flat @click="close">Cancelar</v-btn>
              <v-btn color="blue darken-1" flat @click="save">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        class="elevation-1"
      >
        <template v-slot:items="props">
          <td class="text-xs-right">{{ props.item.id }}</td>
          <td class="text-xs-left">{{ props.item.title }}</td>
          <td class="justify-center layout px-0">
            <v-icon
              small
              class="mr-2"
              @click="editItem(props.item)"
            >
              edit
            </v-icon>
            <v-icon
              small
              @click="deleteItem(props.item)"
            >
              delete
            </v-icon>
          </td>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
      </v-data-table>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "ExampleIndex",
  props: {
      source: String
    },
  data() {
    return {
      dialog: false,
      search: '',
      
      items: [],
      headers: [
        { text: 'Identificador', value: 'id' },
        { text: 'Title', value: 'title' },
        { text: 'Actions', value: 'name', sortable: false }
      ],

      form: {
        id: '',
        title: '',
        body: '',
      },
    };
  },

  created() {
    let self = this
    self.getAll()
  },

  methods: {
    getAll() {
      let self = this

      self.$store.state.services.exampleService
        .getAll()
        .then(r => {
          self.items = r.data
        })
        .catch(r => {});
    },

    editItem (item) {
      console.log(item)
      this.dialog = true
    },

    deleteItem (item) {
      console.log(item)
    },

    close () {
      this.dialog = false
    },

    save () {
      
    }
  },

  computed: {
    },
};
</script>