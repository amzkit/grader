<template>
  <v-row>
    <v-col cols="2">
      <Navigation :onClick="fatchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-col>
            <v-data-table
              :headers="headers"
              :items="this.$store.state.data.schedule_all"
              sort-by="calories"
              class="elevation-1"
              :search="search"
            >
              <template v-slot:top>
                <v-toolbar flat>
                  <v-toolbar-title>Manage Example</v-toolbar-title>
                  <v-divider class="mx-4" inset vertical></v-divider>
                  <v-spacer></v-spacer>

                  <v-dialog v-model="dialog" persistent max-width="600px">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        class="mx-2"
                        fab
                        dark
                        small
                        color="primary"
                        v-bind="attrs"
                        v-on="on"
                      >
                        <v-icon dark> mdi-plus </v-icon>
                      </v-btn>
                    </template>
                    <v-card>
                      <v-card-title>
                        <span class="text-h5">New Example {{ roomName }}</span>
                      </v-card-title>
                      <v-card-text>
                        <v-container>
                          <v-row>
                            <v-col cols="12">
                              <v-autocomplete
                                label="Examples"
                                v-model="selectedExamplesId"
                                :items="problemList"
                                item-text="title"
                                item-value="id"
                                hide-no-data
                                hide-selected
                                multiple
                                chips
                                deletable-chips
                              ></v-autocomplete>
                            </v-col>
                            <v-col cols="12">
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
                            </v-col>
                            <v-col cols="12">
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
                            </v-col>
                          </v-row>
                        </v-container>
                      </v-card-text>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                          color="blue darken-1"
                          text
                          @click="dialog = false"
                        >
                          Close
                        </v-btn>
                        <v-btn color="blue darken-1" text @click="postExample">
                          Save
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-dialog>

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
              <template v-slot:[`item.file`]="{ item }">
                <div v-if="item.file">
                  <v-icon small class="mr-2" @click="download(item)">
                    mdi-file-download
                  </v-icon>
                </div>
              </template>
              <template v-slot:[`item.send_start_work`]="{ item }">
                {{ dayjs(item.start_date).format("MMMM D, YYYY") }}
              </template>
              <template v-slot:[`item.send_end_work`]="{ item }">
                {{ dayjs(item.end_date).format("MMMM D, YYYY") }}
              </template>
              <template v-slot:[`item.action`]="{ item }">
                <div v-if="item.file">
                  <v-icon small class="mr-2" @click="() => {}">
                    mdi-pencil
                  </v-icon>
                </div>
              </template>
            </v-data-table>
          </v-col>
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>


<script>
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Navigation,
    Loading,
  },
  data: function () {
    return {
      problemList: [],
      selectedExamplesId: [],
      roomId: 0,
      roomName: "",
      start_date: null,
      end_date: null,
      menu1: false,
      menu2: false,
      search: "",
      loading: false,
      dialog: false,
      headers: [
        {
          text: "ชื่องาน",
          align: "start",
          sortable: true,
          value: "title",
        },
        { text: "ชื่อเรื่อง", value: "question" },
        { text: "ภาษา", value: "language" },
        { text: "คะแนน", value: "score" },
        { text: "ไฟล์", value: "file" },
        {
          text: "วันที่ส่ง",
          value: "start_date",
        },
        {
          text: "ครบกำหนดส่ง",
          value: "end_date",
        },
        {
          text: "Action",
          value: "action",
        },
      ],
    };
  },
  async created() {
    await this.getProblems();
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
  methods: {
    dayjs,
    download(item) {
      window.location.href = `api/schedule/download${item.file.replace(
        "problem_file",
        ""
      )}`;
    },
    async postExample() {
      console.log(this.selectedExamplesId);
      console.log(this.roomId);
      console.log(this.start_date);
      console.log(this.end_date);
      this.dialog = false;
    },
    async getProblems() {
      this.loading = true;
      const problems = [];
      await axios.get("/api/problem").then((response) => {
        if (response.data.success == true) {
          problems.push(response.data.payload);
        }
      });
      this.problemList = problems[0];
      this.loading = false;
    },
    async fatchItemSchedule(id) {
      this.loading = true;
      if (id) {
        this.roomId = id;
        await axios
          .get("/api/schedule", {
            params: {
              course_id: id,
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              this.$store.commit(
                "data/SET_SCHEDULES_ALL",
                response.data.payload
              );
            }
          });
      }
      this.loading = false;
      console.log(
        this.problemList.filter((e) =>
          this.$store.state.data.schedule_all.filter(
            (a) => a.problemsId !== e.id
          )
        )
      );
    },
  },
};
</script>

