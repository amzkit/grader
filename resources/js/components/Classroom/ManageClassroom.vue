<template>
  <v-row>
    <Loading :loading="loading" />
    <Snackbar />
    <v-col cols="2">
      <Navigation :onClick="fetchItemClassroom" />
    </v-col>
    <v-col cols="10">
      <v-row justify="center">
        <v-col>
          <v-data-table
            :headers="headers"
            :items="desserts.filter((e) => e.role != 'teacher')"
            sort-by="calories"
            class="elevation-1"
            :search="search"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Manage Classroom</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  hide-details
                  class="mr-5"
                ></v-text-field>
                <v-dialog v-model="dialog" max-width="650px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="primary"
                      dark
                      class="mb-2"
                      v-bind="attrs"
                      v-on="on"
                    >
                      New Users
                    </v-btn>
                  </template>
                  <v-card>
                    <v-toolbar flat color="primary" dark>
                      <v-toolbar-title>{{ formTitle }}</v-toolbar-title>
                    </v-toolbar>
                    <div v-if="editedIndex === -1">
                      <v-tabs vertical>
                        <v-tab>
                          <v-icon left> mdi-upload </v-icon>
                          Import File
                        </v-tab>
                        <v-tab>
                          <v-icon left> mdi-account-plus </v-icon>
                          Add
                        </v-tab>
                        <v-tab-item>
                          <v-card flat>
                            <v-card-text>
                              <template>
                                <input
                                  type="file"
                                  class="form-control"
                                  v-on:change="onFileChange"
                                />
                              </template>

                              <v-menu
                                v-model="menu1"
                                :close-on-content-click="false"
                                max-width="290"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    :value="computedDateFormattedStartDate"
                                    clearable
                                    label="Start Date"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    @click:clear="start_date = null"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="start_date"
                                  @change="menu1 = false"
                                ></v-date-picker>
                              </v-menu>
                              <v-menu
                                v-model="menu2"
                                :close-on-content-click="false"
                                max-width="290"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    :value="computedDateFormattedEndDate"
                                    clearable
                                    label="End Date"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                    @click:clear="end_date = null"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="end_date"
                                  @change="menu2 = false"
                                ></v-date-picker>
                              </v-menu>
                            </v-card-text>
                          </v-card>
                        </v-tab-item>
                        <v-tab-item>
                          <v-card flat>
                            <v-card-text>
                              <v-col cols="12">
                                <v-autocomplete
                                  v-model="selectUser"
                                  :items="userItems"
                                  label="Name"
                                  item-text="name"
                                  item-value="id"
                                ></v-autocomplete>
                                <v-text-field
                                  v-model="editedItem.year"
                                  label="Year"
                                  type="number"
                                ></v-text-field>

                                <v-text-field
                                  v-model="editedItem.section"
                                  label="Section"
                                ></v-text-field>
                                <v-text-field
                                  v-model="editedItem.semester"
                                  label="Semester"
                                ></v-text-field>
                                <v-row>
                                  <v-col cols="4">
                                    <v-checkbox
                                      v-model="editedItem.teacher"
                                      label="Teacher"
                                    ></v-checkbox>
                                  </v-col>
                                  <v-col cols="4">
                                    <v-checkbox
                                      v-model="editedItem.ta"
                                      label="TA"
                                    ></v-checkbox>
                                  </v-col>
                                  <v-col cols="4">
                                    <v-checkbox
                                      v-model="editedItem.student"
                                      label="Student"
                                    ></v-checkbox>
                                  </v-col>
                                </v-row>
                              </v-col>
                            </v-card-text>
                          </v-card>
                        </v-tab-item>
                      </v-tabs>
                    </div>
                    <div v-else>
                      <v-card-text>
                        <v-container>
                          <v-row>
                            <v-card-text>
                              <v-col cols="12">
                                <v-text-field
                                  v-model="editedItem.name"
                                  label="Name"
                                  disabled
                                ></v-text-field>
                                <v-text-field
                                  v-model="editedItem.section"
                                  label="Section"
                                ></v-text-field>
                                <v-text-field
                                  v-model="editedItem.semester"
                                  label="Semester"
                                ></v-text-field>
                                <div v-if="!editedItem.teacher">
                                  <v-row>
                                    <!-- <v-col cols="4">
                                    <v-checkbox
                                      v-model="editedItem.teacher"
                                      label="Teacher"
                                    ></v-checkbox>
                                  </v-col> -->
                                    <v-col cols="6">
                                      <v-checkbox
                                        v-model="editedItem.ta"
                                        label="TA"
                                      ></v-checkbox>
                                    </v-col>
                                    <v-col cols="6">
                                      <v-checkbox
                                        v-model="editedItem.student"
                                        label="Student"
                                      ></v-checkbox>
                                    </v-col>
                                  </v-row>
                                </div>
                              </v-col>
                            </v-card-text>
                          </v-row>
                        </v-container>
                      </v-card-text>
                    </div>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="close">
                        Cancel
                      </v-btn>
                      <v-btn color="blue darken-1" text @click="save">
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
            <template v-slot:[`item.section`]="{ item }">
              {{ item.section ? item.section : "-" }}
            </template>
            <template v-slot:[`item.semester`]="{ item }">
              {{ item.semester ? item.semester : "-" }}
            </template>
            <template v-slot:[`item.year`]="{ item }">
              {{ item.year ? item.year : "-" }}
            </template>
            <template v-slot:[`item.role`]="{ item }">
              {{ item.teacher == 1 ? "Teacher," : "" }}
              {{ item.ta == 1 ? "TA," : "" }}
              {{ item.student == 1 ? "Student," : "" }}
            </template>
            <template v-slot:[`item.start_datetime`]="{ item }">
              {{ invalidDate(item.start_datetime) }}
            </template>
            <template v-slot:[`item.end_datetime`]="{ item }">
              {{ invalidDate(item.end_datetime) }}
            </template>
            <template v-slot:[`item.action`]="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import dayjs from "dayjs";
import Loading from "../Loading/Loading.vue";
import Navigation from "../Navigation/Navigation.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  name: "ManageClassroom",
  components: { Loading, Navigation, Snackbar },
  data: () => ({
    selectUser: "",
    addItem: {
      student_id: "",
    },
    userItems: [],
    loading: false,
    search: "",
    file: null,
    dialog: false,
    course_id: 0,
    headers: [
      {
        text: "Name",
        align: "start",
        value: "name",
      },
      { text: "Section", value: "section" },
      { text: "Semester", value: "semester" },
      { text: "Year", value: "year" },
      { text: "Start Datetime", value: "start_datetime" },
      { text: "End Datetime", value: "end_datetime" },
      { text: "Role", value: "role" },
      { text: "Action", value: "action" },
    ],
    desserts: [],
    dialogDelete: false,
    editedIndex: -1,
    editedItem: {
      name: "",
      section: "",
      semester: "",
      year: "",
      start_datetime: "",
      end_datetime: "",
      role: "",
      ta: false,
      teacher: false,
      student: false,
    },
    defaultItem: {
      name: "",
      section: "",
      semester: "",
      year: "",
      start_datetime: "",
      end_datetime: "",
      role: "",
      ta: false,
      teacher: false,
      student: false,
    },
    start_date: "",
    end_date: "",
    menu1: false,
    menu2: false,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Users" : "Edit User";
    },
    computedDateFormattedStartDate() {
      return this.start_date
        ? dayjs(this.start_date).format("dddd, MMMM D, YYYY")
        : "";
    },
    computedDateFormattedEndDate() {
      return this.end_date
        ? dayjs(this.end_date).format("dddd, MMMM D, YYYY")
        : "";
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
    dayjs,
    ...mapActions("snackbar", ["showSnack"]),
    snackBar(timeout = 3500, text = "Successfully", color = "success") {
      this.showSnack({
        text: text,
        color: color,
        timeout: timeout,
      });
    },
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY") : "-";
    },
    async getUser() {
      this.loading = true;
      await axios
        .get("/api/manage/user")
        .then((response) => {
          if (response.data.success === true) {
            this.userItems = response.data.payload;
          }
        })
        .catch(() => {
          this.snackBar(3500, response, "error");
        });
      this.loading = false;
    },
    onFileChange(e) {
      this.file = e.target.files[0];
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

    async deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
      this.deleteManageClassroom();
      this.closeDelete();
      this.snackbar = false;
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

    async deleteManageClassroom() {
      this.loading = true;
      await axios
        .delete(`/api/manage/classroom/${this.editedItem.id}`)
        .then(() => {
          this.snackBar();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async putManageClassroom() {
      this.loading = true;
      await axios
        .put(`/api/manage/classroom`, this.editedItem)
        .then(() => {
          this.snackBar();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async postManageClassroom() {
      this.loading = true;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      if (this.file != null) {
        formData.append("check_file", true);
        formData.append("import_file", this.file);
        formData.append("course_id", this.course_id);
        formData.append("start_date", this.start_date);
        formData.append("end_date", this.end_date);
      } else {
        formData.append("addItem", true);
        formData.append("user_id", this.selectUser);
        formData.append("year", this.editedItem.year);
        formData.append("section", this.editedItem.section);
        formData.append("semester", this.editedItem.semester);
        formData.append("course_id", this.course_id);
        formData.append("student", this.editedItem.student == true ? 1 : 0);
        formData.append("ta", this.editedItem.ta == true ? 1 : 0);
        formData.append("teacher", this.editedItem.teacher == true ? 1 : 0);
      }
      await axios
        .post("/api/user/file/upload", formData, config)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async save() {
      this.close();
      if (this.editedIndex > -1) {
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
        this.putManageClassroom();
      } else {
        this.postManageClassroom();
      }
    },

    async fetchItemClassroom(item) {
      this.loading = true;
      this.course_id = item.courseId;
      if (item) {
        await axios
          .get("/api/manage/classroom", {
            params: {
              course_id: item.courseId,
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              this.desserts = response.data.payload;
            }
          })
          .catch((error) => {
            this.snackBar(3500, error, "error");
          });
      }
      this.loading = false;
    },
  },
};
</script>


