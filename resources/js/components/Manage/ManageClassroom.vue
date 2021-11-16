
<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-dialog v-model="dialog" max-width="500px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
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
                <v-container class="light--text" fluid>
                  <v-text-field
                    v-model="editedItem.stdid"
                    label="รหัสนักศึกษา"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.firstName"
                    label="ชื่อ"
                  ></v-text-field>
                  <v-text-field
                    v-model="editedItem.lastName"
                    label="นามสกุล"
                  ></v-text-field>
                  <v-select
                    v-model="editedItem.status"
                    :items="status"
                    item-text="statusName"
                    item-value="id"
                    single-line
                    auto
                    label="Status"
                  ></v-select>
                </v-container>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="close"> Cancel </v-btn>
            <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-card>
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
            เพิ่มผู้ใช้ห้องเรียน
          </v-btn>
        </template>

        <v-data-table
          :headers="headers"
          :items="this.$store.state.data.manageStdClassroom"
          :search="search"
          class="elevation-1"
        >
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
      </v-card>
    </v-col>
    <Loading :loading="true" />
  </v-row>
</template>

<script>
import DeleteDialog from "../Dialog/DeleteDialog.vue";
import Loading from "../Loading/Loading.vue";
export default {
  name: "ManageClassroom",
  components: { DeleteDialog, Loading },
  data() {
    return {
      dialog: false,
      dialogDelete: false,
      editedItem: {
        stdid: "",
        firstName: "",
        lastName: "",
        status: "",
      },
      defaultItem: {
        stdid: "",
        firstName: "",
        lastName: "",
        status: "",
      },
      search: "",
      headers: [
        {
          text: "Student ID",
          align: "start",
          sortable: false,
          value: "std_id",
        },
        { text: "FirstName", value: "firstName" },
        { text: "LastName", value: "lastName" },
        { text: "Status", value: "statusName" },
        { text: "Action", value: "actions" },
      ],
    };
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
    close() {
      this.dialog = false;
    },
    async initialize() {
      this.desserts = [];
      await axios.get("api/status").then((response) => {
        this.status = response.data;
      });
    },
    async deleteItem(item) {
      await axios.delete("api/manage-std-classroom/" + item.id);
    },
    async save() {
      await axios
        .post("api/manage-std-classroom", {
          std_id: this.editedItem.stdid,
          firstName: this.editedItem.firstName,
          lastName: this.editedItem.lastName,
          classroom_id: this.$store.state.data.manageClassroom.id,
          status_id: this.editedItem.status,
        })
        .then((response) => {
          console.log(response.data.payload);
          this.$store.state.data.manageStdClassroom.push({
            std_id: response.data.payload.std_id,
            firstName: response.data.payload.firstName,
            lastName: response.data.payload.lastName,
            classroom_id: response.data.payload.classroom_id,
            statusName: response.data.payload.statusName,
          });
        })
        .catch((err) => console.log(err));
      this.close();
    },
  },
};
</script>
