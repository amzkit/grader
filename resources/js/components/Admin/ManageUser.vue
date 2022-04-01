
<template>
  <v-row>
    <v-data-table
      :headers="headers"
      :search="search"
      :items="
        desserts.filter((e) => {
          if (this.status === 'student') {
            if (e.role_student === 1) {
              return e;
            }
          } else if (this.status === 'ta') {
            if (e.role_ta === 1) {
              return e;
            }
          } else if (this.status === 'teacher') {
            if (e.role_teacher === 1) {
              return e;
            }
          } else if (this.status === 'all') {
            return e;
          }
        })
      "
      sort-by="calories"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Manage User</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            hide-details
            class="mr-5"
          ></v-text-field>
          <v-dialog v-model="dialog" max-width="800px">
            <v-card>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        v-model="editedItem.username"
                        label="Username"
                        disabled
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-text-field
                        v-model="editedItem.name"
                        label="Name"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="9">
                      <v-text-field
                        v-model="editedItem.email"
                        label="Email"
                        disabled
                      ></v-text-field>
                    </v-col>
                    <v-col cols="3">
                      <v-btn
                        class="ma-2"
                        :loading="loading"
                        :disabled="loading"
                        @click="
                          () => {
                            loader = 'loading';
                          }
                        "
                        color="primary"
                      >
                        Reset password
                      </v-btn>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="close">
                  Cancel
                </v-btn>
                <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:[`item.action`]="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil </v-icon>
      </template>
    </v-data-table>
  </v-row>
</template>
<script>
export default {
  data: function () {
    return {
      loader: null,
      loading: false,
      status: "all",
      search: "",
      resetPassword: "",
      headers: [
        { text: "Username", value: "username" },
        {
          text: "Name",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "Email", value: "email" },
        { text: "Action", value: "action" },
      ],
      dialog: false,
      dialogDelete: false,
      desserts: [],
      editedIndex: -1,
      editedItem: {
        id: 0,
        email: "",
        name: "",
        username: "",
      },
      defaultItem: {
        id: 0,
        email: "",
        name: "",
        username: "",
      },
    };
  },

  created() {
    this.getUser();
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    loader() {
      const l = this.loader;
      this[l] = !this[l];
      setTimeout(() => {
        this[l] = false;
        this.resetPassword = this.editedItem.username;
      }, 1000);
      this.loader = null;
    },
  },

  methods: {
    async getUser() {
      this.loading = true;
      await axios.get("/api/manage/user").then((response) => {
        if (response.data.success == true) {
          this.desserts = response.data.payload;
        }
      });
      this.loading = false;
    },

    async updateUser() {
      this.loading = true;
      console.log(this.editedItem, this.resetPassword);
      await axios.put("/api/manage/user", {
        id: this.editedItem.id,
        resetPassword: this.resetPassword ? this.resetPassword : "",
        name: this.editedItem.name,
      });

      this.loading = false;
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
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
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
        this.updateUser();
        this.resetPassword = "";
      }
      this.close();
    },
  },
};
</script>

