<template>
  <v-row>
    <Snackbar />
    <Loading :loading="loading" />
    <v-col cols="3">
      <v-card class="mx-auto" max-width="300">
        <v-list dense>
          <v-subheader>Problems</v-subheader>
          <v-text-field
            class="mx-3"
            label="Search"
            prepend-inner-icon="search"
            v-model="search"
            clearable
            solo
            dense
          ></v-text-field>
          <v-list-item-group>
            <v-list-item
              v-for="(item, i) in filteredItems"
              :key="i"
              @click="fetchRoom(item.id)"
            >
              <v-list-item-content>
                <v-list-item-title
                  v-text="item.course_name"
                ></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
    <v-col cols="1"> <v-divider vertical></v-divider></v-col>
    <v-col cols="7">
      <v-row>
        <v-col cols="7">
          <v-text-field
            class="mx-3"
            label="Search Title"
            prepend-inner-icon="search"
            v-model="searchProblem"
            clearable
            solo
            dense
          ></v-text-field>
        </v-col>
        <v-col cols="3">
          <v-select
            v-model="sortby"
            :items="['Title', 'Date', 'Level', 'Late']"
            label="Sort"
            solo
            dense
          ></v-select>
        </v-col>
        <v-col cols="2">
          <v-checkbox
            v-model="viewAll"
            solo
            dense
            :label="`View All`"
          ></v-checkbox>
        </v-col>
      </v-row>

      <v-card
        class="mx-auto mb-4"
        max-width="100%"
        v-for="(item, i) in filterCourseRoom"
        :key="i"
        :color="item.late && getDateTime >= item.end_date ? '#FFE57F' : 'white'"
      >
        <v-list-item two-line>
          <v-list-item-content>
            <v-list-item-title class="text-h5">
              {{ item.title }}
            </v-list-item-title>
            <v-list-item-subtitle>
              <v-rating
                :value="item.level"
                dense
                half-increments
                readonly
                size="14"
              ></v-rating>
            </v-list-item-subtitle>
            <v-divider class="mx-4"></v-divider>
            <v-card-title
              >Schedule
              {{
                item.late && getDateTime >= item.end_date ? `(Past due)` : ""
              }}
            </v-card-title>
            <v-card-text>
              <v-chip-group
                active-class="deep-purple accent-4 white--text"
                column
              >
                <v-chip>
                  {{ invalidDate(item.start_date) || "NOT SET" }}
                  -
                  {{ invalidDate(item.end_date) || "NOT SET" }}
                </v-chip>
              </v-chip-group>
            </v-card-text>
          </v-list-item-content>
        </v-list-item>

        <v-card-actions>
          <v-btn
            color="primary"
            text
            @click="
              () => {
                problem.title = item.title;
                problem.question = item.question;
                problem.score = item.score;
                problem.file = item.file;
                problem.start_date = item.start_date;
                problem.end_date = item.end_date;
                problem.lang = item.lang;
                problem.problem_id = item.problemsId;
                problem.type = item.type;
                dialog = true;
                problem.send = true;
              }
            "
          >
            SHOW MORE
          </v-btn>
        </v-card-actions>
      </v-card>
      <div v-if="viewAll">
        <v-card
          class="mx-auto mb-4"
          max-width="100%"
          style="background: #ffe57f"
          v-for="(item, i) in this.schedule_room.filter(
            (e) => !e.late && e.end_date <= this.getDateTime
          )"
          :key="i"
        >
          <v-list-item two-line>
            <v-list-item-content>
              <v-list-item-title class="text-h5">
                {{ `${item.title} (Out of time)` }}
              </v-list-item-title>
              <v-list-item-subtitle>
                <v-rating
                  :value="item.level"
                  dense
                  half-increments
                  readonly
                  size="14"
                ></v-rating>
              </v-list-item-subtitle>
              <v-divider class="mx-4"></v-divider>
              <v-card-title>Schedule </v-card-title>
              <v-card-text>
                <v-chip-group
                  active-class="deep-purple accent-4 white--text"
                  column
                >
                  <v-chip>
                    {{ invalidDate(item.start_date) || "NOT SET" }}
                    -
                    {{ invalidDate(item.end_date) || "NOT SET" }}
                  </v-chip>
                </v-chip-group>
              </v-card-text>
            </v-list-item-content>
          </v-list-item>
          <v-card-actions>
            <v-btn
              color="primary"
              text
              @click="
                () => {
                  problem.title = item.title;
                  problem.question = item.question;
                  problem.score = item.score;
                  problem.file = item.file;
                  problem.start_date = item.start_date;
                  problem.end_date = item.end_date;
                  problem.lang = item.lang;
                  problem.problem_id = item.problemsId;
                  problem.type = item.type;
                  dialog = true;
                  problem.send = false;
                }
              "
            >
              SHOW MORE
            </v-btn>
          </v-card-actions>
        </v-card>
      </div>

      <v-expansion-panels>
        <v-expansion-panel v-for="(item, i) in filterSubmissionRoom" :key="i">
          <v-expansion-panel-header disable-icon-rotate>
            {{ `${item.title} (Submit ${item.count})` }}
            <template v-slot:actions>
              <v-icon color="teal"> mdi-check </v-icon>
            </template>
          </v-expansion-panel-header>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-col>

    <v-dialog v-model="dialog" max-width="800">
      <v-card>
        <v-card-title class="text-h5"> {{ problem.title }} </v-card-title>
        <v-card-subtitle>
          {{ invalidDate(problem.start_date) || "NOT SET" }}
          -
          {{ invalidDate(problem.end_date) || "NOT SET" }}
        </v-card-subtitle>
        <v-card-text>
          <v-row>
            <v-col cols="4"> Question </v-col>
            <v-col cols="8">
              <div v-html="problem.question"></div>
            </v-col>
          </v-row>
          <v-row class="mb-3">
            <v-col cols="4"> Score </v-col>
            <v-col cols="8">
              {{ problem.score }}
            </v-col>
          </v-row>
          <v-row v-if="problem.file" class="mb-3">
            <v-col cols="4"> Download File Question </v-col>
            <v-col cols="8">
              <v-btn small color="primary" dark @click="download(problem.file)">
                Download File
              </v-btn>
            </v-col>
          </v-row>

          <v-row class="mb-3">
            <v-col cols="4"> Language </v-col>
            <v-col cols="8">
              {{ problem.lang }}
            </v-col>
          </v-row>
          <template v-if="problem.send">
            <v-row>
              <v-col cols="4"> File Import </v-col>
              <v-col cols="8">
                <input
                  type="file"
                  class="form-control"
                  ref="file_upload"
                  v-on:change="onFileChange"
                  :accept="problem.type"
                />
              </v-col>
            </v-row>
          </template>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="
              () => {
                if ($refs.file_upload) {
                  $refs.file_upload.value = null;
                }
                dialog = false;
              }
            "
          >
            Close
          </v-btn>
          <template v-if="problem.send">
            <v-btn color="green darken-1" text @click="submit()">
              Submit
            </v-btn>
          </template>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      viewAll: false,
      languages: [],
      schedule_room: [],
      course_room: [],
      search: "",
      searchProblem: "",
      dialog: false,
      snackbar: false,
      text: "",
      loading: false,
      course_id: 0,
      missionPass: [],
      problem: {
        problem_id: 0,
        title: "",
        question: "",
        score: 0,
        file: "",
        sendFile: null,
        start_date: "",
        end_date: "",
        lang: "",
        type: "",
        send: false,
      },
      sortby: "Sort",
    };
  },

  created() {
    this.fetchItemSchedule();
    this.fetchItemScheduleById();
    this.fetchItemSubmission();
  },

  computed: {
    getDateTime() {
      return dayjs(new Date()).format("YYYY-MM-DD HH:mm");
    },
    filteredItems() {
      return _.orderBy(
        this.course_room.filter((item) => {
          return item.course_name
            .toLowerCase()
            .includes(this.search.toLowerCase());
        }),
        "headline"
      );
    },

    filterSubmissionRoom() {
      const convert = (arr) => {
        const res = {};
        arr.forEach((obj) => {
          const key = `${obj.schedule_id}`;
          if (!res[key]) {
            res[key] = { ...obj, count: 0 };
          }
          res[key].count += 1;
        });
        return Object.values(res);
      };

      return _.orderBy(
        convert(this.missionPass).filter((item) => {
          return item.title
            .toLowerCase()
            .includes(this.searchProblem.toLowerCase());
        }),
        "headline"
      );
    },
    filterCourseRoom() {
      let schedule_filter = [];
      this.schedule_room.filter((e) => {
        if (e.late) {
          if (
            (e.end_date > this.getDateTime &&
              e.start_date <= this.getDateTime) ||
            (e.end_date <= this.getDateTime && e.late)
          ) {
            schedule_filter.push(e);
          }
        } else {
          if (
            e.end_date > this.getDateTime &&
            e.start_date <= this.getDateTime
          ) {
            schedule_filter.push(e);
          }
        }
      });
      let sort = [];
      if (this.sortby == "Title") {
        sort = schedule_filter.sort((a, b) => (a.title > b.title ? 1 : -1));
      } else if (this.sortby == "Date") {
        sort = schedule_filter.sort((a, b) =>
          a.start_date > b.start_date ? 1 : -1
        );
      } else if (this.sortby == "Level") {
        sort = schedule_filter.sort((a, b) => (a.level > b.level ? 1 : -1));
      } else if (this.sortby == "Late") {
        sort = schedule_filter.sort((a, b) => (a.late < b.late ? 1 : -1));
      } else {
        sort = schedule_filter;
      }
      return _.orderBy(
        sort.filter((item) => {
          return item.title
            .toLowerCase()
            .includes(this.searchProblem.toLowerCase());
        }),
        "headline"
      );
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
    async submit() {
      if (!this.problem.sendFile) {
        this.snackBar(3500, "Please your input file.", "warning");
      }
      this.loading = true;
      this.dialog = false;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("sourcefile", this.problem.sendFile);
      formData.append("Lang", this.problem.lang);
      formData.append("problem_id", this.problem.problem_id);
      formData.append("course_id", this.$route.query.course_id);
      await axios
        .post("/api/submission", formData, config)
        .then((response) => {
          if (response.data.success) {
            window.location.reload();
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
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

    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm") : "-";
    },

    onFileChange(e) {
      this.problem.sendFile = e.target.files[0];
    },

    async fetchItemSchedule() {
      this.loading = true;
      await axios
        .get("/api/schedule")
        .then((response) => {
          if (response.data.success == true) {
            this.course_room = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    fetchRoom(val) {
      this.$router
        .push({
          path: "/problem",
          query: {
            course_id: val,
          },
        })
        .catch(() => {});
      this.fetchItemScheduleById();
      this.fetchItemSubmission();
    },

    async fetchItemScheduleById() {
      this.loading = true;
      await axios
        .get("/api/schedule/" + this.$route.query.course_id)
        .then((response) => {
          if (response.data.success == true) {
            this.schedule_room = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async fetchItemSubmission() {
      this.loading = true;
      await axios
        .get("/api/submission", {
          params: {
            course_id: this.$route.query.course_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.missionPass = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },
  },
};
</script>

