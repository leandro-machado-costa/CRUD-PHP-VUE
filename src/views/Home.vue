<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="products"
      sort-by="product_name"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>CRUD PHP</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                Adicionar Produto
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.product_name"
                        label="Nome do Produto"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.cod_bar"
                        label="Código de Barras"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12" sm="6" md="4">
                      <v-text-field
                        v-model="editedItem.price_buy"
                        label="Preço de Compra"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Cancelar
                </v-btn>
                <v-btn color="blue darken-1" text @click="save"> Salvar </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="headline">
                Tem certeza que deseja deletar? </v-card-title
              ><v-card-subtitle
                >Você selecionou o produto {{ editedItem.product_name }}
              </v-card-subtitle>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="red darken-1" text @click="closeDelete"
                  >Não</v-btn
                >
                <v-btn color="green darken-1" text @click="deleteItemConfirm"
                  >Sim</v-btn
                >
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.price_buy`]="{ item }">
        {{ (item.price_buy * 1).toFixed(2) }}
      </template>
      <template v-slot:[`item.price_sales`]="{ item }">
        {{ (item.price_buy * 2).toFixed(2) }}
      </template>
      <template v-slot:[`item.actions`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
        <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  name: "Home",

  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [
      { text: "Produto", value: "product_name" },
      { text: "Código de Barras", value: "cod_bar" },
      { text: "Preço de Compra", value: "price_buy" },
      { text: "Preço de Venda", value: "price_sales" },
      { text: "Ações", value: "actions", sortable: false },
    ],
    products: [],
    editedIndex: -1,
    editedItem: {
      product_name: "",
      cod_bar: "0",
      price_buy: "0",
    },
    defaultItem: {
      product_name: "",
      cod_bar: "0",
      price_buy: "0",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "Novo Produto" : "Editar Produto";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
  },

  methods: {
    initialize() {
      this.products = [];
      this.$http.get("api.php").then((response) => {
        this.products = response.data ? response.data.result : [];
      });
    },

    editItem(item) {
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.produtos.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      var index = this.editedIndex;
      this.$http
        .delete("api.php?id=" + this.editedItem.id, {
          id: this.editedItem.id,
        })
        .then(() => {
          this.products.splice(index, 1);
        });

      this.closeDelete();
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.editedItem = Object.assign({}, this.defaultItem);
    },

    save() {
      console.log(this.editedItem);
      this.editedItem.price_buy = this.editedItem.price_buy.replace(",", ".");
      if (this.editedItem.id) {
        this.$http
          .put("api.php", {
            id: this.editedItem.id,
            product_name: this.editedItem.product_name,
            cod_bar: this.editedItem.cod_bar,
            price_buy: this.editedItem.price_buy * 1,
          })
          .then(() => {
            this.initialize();
          });
      } else {
        this.$http
          .post("api.php", {
            product_name: this.editedItem.product_name,
            cod_bar: this.editedItem.cod_bar,
            price_buy: this.editedItem.price_buy,
          })
          .then(() => {
            this.initialize();
          });
      }
      this.close();
    },
  },
};
</script>
