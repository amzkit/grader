<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation :onClick="fatchItemClassroom" />
    </v-col>
    <v-col cols="10">
      <v-row justify="center">
        <v-col>
          <v-data-table
            :headers="headers"
            :items="desserts"
            sort-by="calories"
            class="elevation-1"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Manage Classroom</v-toolbar-title>
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
                      New Item
                    </v-btn>
                  </template>
                  <v-card>
                    <v-card-title>
                      <span class="text-h5">{{ formTitle }}</span>
                    </v-card-title>

                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12">
                            <div v-if="editedIndex === -1">
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
                                    label="เทอมเริ่มต้น"
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
                                    label="เทอมสิ้นสุด"
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
                            </div>
                            <div v-else>
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
                              <v-select
                                v-model="editedItem.role"
                                :items="['student', 'ta']"
                                single-line
                                auto
                                label="Role"
                              ></v-select>
                            </div>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
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
import DeleteDialog from "../Dialog/DeleteDialog.vue";
import Loading from "../Loading/Loading.vue";
import Navigation from "../Navigation/Navigation.vue";
export default {
  name: "ManageClassroom",
  components: { DeleteDialog, Loading, Navigation },
  data: () => ({
    loading: false,
    search: "",
    file: "",
    dialog: false,
    classroomName: "",
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
    },
    defaultItem: {
      name: "",
      section: "",
      semester: "",
      year: "",
      start_datetime: "",
      end_datetime: "",
      role: "",
    },
    start_date: null,
    end_date: null,
    menu1: false,
    menu2: false,
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Item" : "Edit Item";
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

  methods: {
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
      this.closeDelete();
      this.loading = true;
      await axios.delete(
        "/api/manage/classroom/" +
          this.editedItem.user_id +
          "/" +
          this.editedItem.course_id
      );
      this.loading = false;
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

    async save() {
      this.close();
      if (this.editedIndex > -1) {
        this.loading = true;
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
        await axios.put("/api/manage/classroom/" + this.editedItem.user_id, {
          course_id: this.editedItem.course_id,
          role: this.editedItem.role,
        });
        this.loading = false;
      } else {
        this.loading = true;
        const config = {
          headers: { "content-type": "multipart/form-data" },
        };
        let formData = new FormData();

        formData.append("import_file", this.file);
        formData.append("course_name", this.classroomName);
        formData.append("start_date", this.start_date);
        formData.append("end_date", this.end_date);

        await axios
          .post("/api/user/file/upload", formData, config)
          .then(function () {
            location.reload();
          })
          .catch(function (error) {
            console.log(error);
          });
      }
      this.loading = false;
    },

    async fatchItemClassroom(item) {
      this.loading = true;
      this.classroomName = item.course_name;
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
          });
      }
      this.loading = false;
    },
  },
};
</script>

