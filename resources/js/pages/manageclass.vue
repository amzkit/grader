<template>
  <v-row justify="center">
    <h1>จัดการห้องเรียน</h1>
    <v-col>
      <v-card>
        <!-- ตั้งชื่อห้องเรียน -->
        <v-card-text>
          <v-text-field
            v-model="nameClassroom"
            color="primary"
            label="ตั้งชื่อห้องเรียน"
          ></v-text-field>

          <!-- ตารางรายชื่อผู้ใช้ห้องเรียน -->
          <v-data-table
            :headers="headers"
            :items="desserts"
            :search="search"
            sort-by="calories"
            class="elevation-1"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>รายชื่อผู้ใช้ห้องเรียน</v-toolbar-title>
                <v-spacer></v-spacer>

                <!-- ช่องค้นหา -->
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>

                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="primary"
                      dark
                      class="mb-2"
                      v-bind="attrs"
                      v-on="on"
                    >
                      เพิ่มผู้ใช้ห้องเรียน
                    </v-btn>
                  </template>
                  <v-card>
                    <v-card-title>
                      <span class="text-h5">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field
                              v-model="editedItem.stdid"
                              label="รหัสนักศึกษา"
                            ></v-text-field>
                          </v-col>
                          <v-col
                            cols="12"
                            sm="6"
                            md="6
                          "
                          >
                            <v-text-field
                              v-model="editedItem.firstName"
                              label="ชื่อ"
                            ></v-text-field>
                            <v-text-field
                              v-model="editedItem.lastName"
                              label="นามสกุล"
                            ></v-text-field>
                          </v-col>
                          <v-container class="light--text" fluid>
                            <v-checkbox
                              v-model="checkbox"
                              :label="`TA`"
                            ></v-checkbox>
                          </v-container>
                        </v-row>
                      </v-container>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="close">
                        Cancel
                      </v-btn>
                      <v-btn color="blue darken-1" text @click="saveItem">
                        Save
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                  <v-card>
                    <v-card-title class="text-h5"
                      >Are you sure you want to delete this item?</v-card-title
                    >
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="closeDelete"
                        >Cancel</v-btn
                      >
                      <v-btn
                        color="blue darken-1"
                        text
                        @click="deleteItemConfirm"
                        >OK</v-btn
                      >
                      <v-spacer></v-spacer>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-toolbar>
            </template>

            <!-- ไอคอน actions -->
            <template v-slot:[`item.actions`]="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
            <template v-slot:no-data>
              <v-btn color="primary" @click="initialize"> Reset </v-btn>
            </template>
          </v-data-table>
        </v-card-text>

        <!-- ปุ่ม save ทั้งหมด -->
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="saveAll"> Save </v-btn>
        </v-card-actions>
        <v-snackbar v-model="hasSaved" :timeout="2000" absolute bottom left>
          Successfully saved
        </v-snackbar>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      search: "",
      loading: false,
      user: null,
      hasSaved: false,
      model: null,
      dialog: false,
      dialogDelete: false,
      checkbox: false,
      headers: [
        {
          text: "รหัสนักศึกษา",
          align: "start",
          sortable: false,
          value: "stdid",
        },
        {
          text: "ชื่อ - นามสกุล",
          value: "firstName",
        },
        { text: "สถานะ", value: "status" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      desserts: [],
      nameClassroom: "",
      editedIndex: -1,
      editedItem: {
        stdid: "",
        className: "",
        firstName: "",
        lastName: "",
        status: "",
      },
      defaultItem: {
        stdid: "",
        className: "",
        firstName: "",
        lastName: "",
        status: "",
      },
    };
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "เพิ่มผู้ใช้ห้องเรียน" : "แก้ไข";
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

  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.initialize();
  },
  methods: {
    async manageClass() {
      this.loading = true;
      console.log("asd");

      this.loading = false;
    },

    // ตาราง
    async initialize() {
      this.desserts = [];
      await axios.get("api/classroom").then((response) => {
        if (response.data.success == true) {
          console.log(response.data);
        }
      });
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

    saveItem() {
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
      } else {
        this.desserts.push(this.editedItem);
      }
      this.close();
    },

    saveAll() {
      this.hasSaved = true;
      this.item(this.desserts);
      console.log(this.desserts);
    },

    async item(item) {
      let id = 0;
      console.log(item);
      await axios
        .post("api/classroom", {
          className: this.nameClassroom,
        })
        .then((response) => {
          id = response.data.last_insert_id;
        })
        .catch((err) => console.log(err));
      item.map(async (e) => {
        if (e.stdid) {
          await axios
            .post("api/stdclassroom", {
              std_id: e.stdid,
              firstName: e.firstName,
              lastName: e.lastName,
              classroom_id: id,
              statusID: 1,
            })
            .then((response) => {
              console.log(response.data);
            })
            .catch((err) => console.log(err));
        }
      });
    },
  },
};
</script>
<style>
</style>