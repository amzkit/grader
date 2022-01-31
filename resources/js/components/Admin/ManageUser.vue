
<template>
  <v-row>
    <v-col cols="12">
      <v-row justify="center">
        <v-container>
          <v-data-table
            :headers="headers"
            :items="
              user.filter((e) => {
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
            :search="search"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Manage User</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-radio-group v-model="status" row class="mt-7 mr-10">
                  <v-radio label="All" value="all"></v-radio>
                  <v-radio label="TA" value="ta"></v-radio>
                  <v-radio label="Teacher" value="teacher"></v-radio>
                  <v-radio label="Student" value="student"></v-radio>
                </v-radio-group>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                >
                </v-text-field>
              </v-toolbar>
            </template>

            <template v-slot:[`item.action`]="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
          </v-data-table>
        </v-container>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>
export default {
  data: function () {
    return {
      user: [],
      status: "all",
      dialog: false,
      dialogDelete: false,
      search: "",
      headers: [
        {
          text: "Name",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "Email", value: "email" },
        { text: "Username", value: "username" },
        { text: "Role last active", value: "role" },
        { text: "Action", value: "action" },
      ],
      editedIndex: -1,
      editedItem: {
        name: "",
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
      defaultItem: {
        name: "",
        calories: 0,
        fat: 0,
        carbs: 0,
        protein: 0,
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
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
    this.getUser();
  },

  methods: {
    async getUser() {
      const getuser = [];
      await axios
        .get("/api/manage/user")
        .then(function (response) {
          if (response.data.success === true) {
            getuser.push(response.data.payload);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
      this.user = getuser[0];
    },
    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
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
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
      } else {
        this.desserts.push(this.editedItem);
      }
      this.close();
    },
  },
};
</script>

