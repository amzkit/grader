<template>
  <v-row>
    <Loading :loading="loading" />
    <Snackbar />
    <v-col cols="2">
      <Navigation :onClick="fetchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <v-row justify="center">
        <v-col>
          <v-data-table
            :headers="headers"
            :items="desserts"
            sort-by="calories"
            class="elevation-1"
            :search="search"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Manage Problems Classroom</v-toolbar-title>
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
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn
                      color="primary"
                      dark
                      class="mb-2"
                      v-bind="attrs"
                      v-on="on"
                    >
                      ADD PROBLEMS
                    </v-btn>
                  </template>

                  <v-card>
                    <v-card-title>
                      <span class="text-h5">
                        {{
                          editedIndex === -1 ? "Add Problems" : "Edit Problems"
                        }}
                      </span>
                    </v-card-title>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="8">
                            <div v-if="editedIndex === -1">
                              <v-autocomplete
                                label="Examples"
                                v-model="selectedExamplesId"
                                :items="
                                  problemList.filter(
                                    (e) =>
                                      !desserts
                                        .map((e) => e.problemsId)
                                        .includes(e.id)
                                  )
                                "
                                item-text="title"
                                item-value="id"
                                hide-no-data
                                hide-selected
                                multiple
                                chips
                                deletable-chips
                              >
                              </v-autocomplete>
                            </div>
                          </v-col>
                          <v-col :cols="editedIndex === -1 ? '4' : '12'">
                            <v-autocomplete
                              v-model="editedItem.language_id"
                              :items="languages"
                              label="Language"
                              item-text="lang"
                              item-value="id"
                            >
                            </v-autocomplete>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="6">
                            <v-text-field
                              type="number"
                              v-model="editedItem.score"
                              onfocus="this.select()"
                              label="Score"
                            ></v-text-field>
                            <!-- <v-text-field
                              v-model=""
                              label="Score"
                              hide-details
                              class="mr-5"
                            ></v-text-field> -->
                          </v-col>
                          <v-col cols="3">
                            <v-checkbox
                              v-model="editedItem.late"
                              :label="`Pass due`"
                            ></v-checkbox>
                          </v-col>
                          <v-col cols="3">
                            <v-checkbox
                              v-model="editedItem.IsAnalysis"
                              :label="`Is Analysis`"
                            ></v-checkbox>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="6">
                            <!-- DATE TIME 1 -->
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
                          </v-col>
                          <v-col cols="6">
                            <!-- DATE TIME 2 -->
                            <v-menu
                              v-model="menu3"
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
                                @change="menu3 = false"
                              ></v-date-picker>
                            </v-menu>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="6">
                            <v-menu
                              ref="menu"
                              v-model="menu2"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              :return-value.sync="start_time"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="start_time"
                                  label="Start Time"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                ></v-text-field>
                              </template>
                              <v-time-picker
                                v-if="menu2"
                                format="24hr"
                                v-model="start_time"
                                full-width
                                @click:minute="$refs.menu.save(start_time)"
                              ></v-time-picker>
                            </v-menu>
                          </v-col>
                          <v-col cols="6">
                            <v-menu
                              ref="menu2"
                              v-model="menu4"
                              :close-on-content-click="false"
                              :nudge-right="40"
                              :return-value.sync="end_time"
                              transition="scale-transition"
                              offset-y
                              max-width="290px"
                              min-width="290px"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                  v-model="end_time"
                                  label="End Time"
                                  readonly
                                  v-bind="attrs"
                                  v-on="on"
                                ></v-text-field>
                              </template>
                              <v-time-picker
                                v-if="menu4"
                                format="24hr"
                                v-model="end_time"
                                full-width
                                @click:minute="$refs.menu2.save(end_time)"
                              ></v-time-picker>
                            </v-menu>
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
            <template v-slot:[`item.question`]="{ item }">
              {{ convertToPlain(item.question) }}
            </template>
            <template v-slot:[`item.late`]="{ item }">
              <div v-if="item.late === 1">
                <v-icon class="mr-2"> mdi-check </v-icon>
              </div>
              <div v-else>
                <v-icon class="mr-2"> mdi-close </v-icon>
              </div>
            </template>
            <template v-slot:[`item.IsAnalysis`]="{ item }">
              <div v-if="item.IsAnalysis === 1">
                <v-icon class="mr-2"> mdi-check </v-icon>
              </div>
              <div v-else>
                <v-icon class="mr-2"> mdi-close </v-icon>
              </div>
            </template>
            <template v-slot:[`item.file`]="{ item }">
              <div v-if="item.file">
                <v-icon small class="mr-2" @click="download(item.file)">
                  mdi-file-download
                </v-icon>
              </div>
              <div v-else>
                {{ " - " }}
              </div>
            </template>
            <template v-slot:[`item.start_date`]="{ item }">
              {{ invalidDate(item.start_date) }}
            </template>
            <template v-slot:[`item.end_date`]="{ item }">
              {{ invalidDate(item.end_date) }}
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
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    Navigation,
    Loading,
    Snackbar,
  },
  data: function () {
    return {
      loading: false,
      languages: [],
      problemList: [],
      selectedExamplesId: [],
      roomId: 0,
      roomName: "",
      search: "",
      dialog: false,
      headers: [
        {
          text: "Title",
          align: "start",
          sortable: true,
          value: "title",
        },
        { text: "Score", value: "score" },
        {
          text: "Start Date",
          value: "start_date",
        },
        {
          text: "End Date",
          value: "end_date",
        },
        { text: "File", value: "file" },
        {
          text: "Language",
          value: "lang",
        },
        {
          text: "Pass due",
          value: "late",
          align: "center",
        },
        {
          text: "Is Analysis",
          value: "IsAnalysis",
          align: "center",
        },
        {
          text: "Action",
          value: "action",
        },
      ],
      desserts: [],
      dialogDelete: false,
      editedIndex: -1,
      editedItem: {
        id: 0,
        start_date: "",
        end_date: "",
        language_id: 0,
        late: false,
        IsAnalysis: false,
        score: 0,
        start_date: "",
        end_date: "",
      },
      defaultItem: {
        id: 0,
        start_date: "",
        end_date: "",
        language_id: 0,
        late: false,
        IsAnalysis: false,
        score: 0,
        start_date: "",
        end_date: "",
      },
      start_date: null,
      end_date: null,
      start_time: null,
      end_time: null,
      menu1: false,
      menu2: false,
      menu3: false,
      menu4: false,
    };
  },
  async created() {
    await this.getProblems();
    await this.getLanguage();
  },
  computed: {
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
    dayjs,
    ...mapActions("snackbar", ["showSnack"]),
    snackBar(timeout = 3500, text = "Successfully", color = "success") {
      this.showSnack({
        text: text,
        color: color,
        timeout: timeout,
      });
    },
    convertToPlain(html) {
      var tempDivElement = document.createElement("div");
      tempDivElement.innerHTML = html;
      return tempDivElement.textContent || tempDivElement.innerText || "";
    },

    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm") : "-";
    },

    async download(item) {
      this.loading = true;
      await axios
        .get(`/api/schedule/download${item.replace("problem_file", "")}`)
        .then((response) => {
          window.location.href = `api/schedule/download${item.replace(
            "problem_file",
            ""
          )}`;
        })
        .catch((error) => {
          if (error.response.status === 404) {
            this.snackBar(3500, error.response.data.message, "error");
          } else {
            this.snackBar(3500, error, "error");
          }
        });
      this.loading = false;
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
      this.deleteSchedule();
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
        this.updateExample(this.editedItem);
      } else {
        this.postExample();
      }
      this.close();
    },

    async postExample() {
      this.loading = true;
      await axios
        .post("/api/manage/example", {
          exampleId: this.selectedExamplesId,
          roomId: this.roomId,
          late: this.editedItem.late,
          IsAnalysis: this.editedItem.IsAnalysis,
          score: this.editedItem.score,
          language_id: this.editedItem.language_id,
          start_date: dayjs(`${this.start_date} ${this.start_time}`).format(
            "MM-DD-YYYY HH:mm"
          ),
          end_date: dayjs(`${this.end_date} ${this.end_time}`).format(
            "MM-DD-YYYY HH:mm"
          ),
        })
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.dialog = false;
      this.loading = false;
    },

    async updateExample(item) {
      this.loading = true;
      await axios
        .put("/api/manage/example", {
          id: item.id,
          late: this.editedItem.late,
          IsAnalysis: this.editedItem.IsAnalysis,
          score: this.editedItem.score,
          language_id: this.editedItem.language_id,
          start_date:
            this.start_date && this.start_time
              ? dayjs(`${this.start_date} ${this.start_time}`).format(
                  "MM-DD-YYYY HH:mm"
                )
              : this.editedItem.start_date,
          end_date:
            this.end_date && this.end_time
              ? dayjs(`${this.end_date} ${this.end_time}`).format(
                  "MM-DD-YYYY HH:mm"
                )
              : this.editedItem.end_date,
        })
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.dialog = false;
      this.loading = false;
    },

    async getLanguage() {
      this.loading = true;
      await axios
        .get("/api/language")
        .then((response) => {
          this.languages = response.data.payload;
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async deleteSchedule() {
      this.loading = true;
      await axios
        .delete("api/manage/example/" + this.editedItem.id)
        .then(() => {
          this.snackBar();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async getProblems() {
      this.loading = true;
      await axios
        .get("/api/problem")
        .then((response) => {
          if (response.data.success == true) {
            this.problemList = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async fetchItemSchedule(item) {
      this.loading = true;
      if (item) {
        this.roomId = item.courseId;
        await axios
          .get("/api/schedule/" + item.courseId)
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


