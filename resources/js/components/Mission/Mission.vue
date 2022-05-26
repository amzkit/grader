<template>
  <v-row>
    <Snackbar />
    <Loading :loading="loading" />
    <v-col cols="3">
      <v-card class="mx-auto" max-width="300">
        <v-list dense>
          <v-subheader>Course</v-subheader>
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

      <v-expansion-panels :readonly="readonly" multiple focusable>
        <v-expansion-panel v-for="(item, i) in filterCourseRoom" :key="i">
          <v-expansion-panel-header v-slot="{ open }">
            <v-row no-gutters>
              <v-col cols="12">
                {{ item.title }}
                {{
                  item.late && getDateTime >= item.end_date ? "(Late)" : ""
                }}&nbsp;
                <v-rating
                  :value="item.level"
                  dense
                  half-increments
                  readonly
                  size="14"
                ></v-rating>
              </v-col>
              <v-col cols="12" class="text--secondary">
                <v-fade-transition leave-absolute>
                  <span v-if="open">
                    (Submit
                    {{
                      filterSubmissionRoom.find((e) => e.id == item.id).count
                    }})</span
                  >
                  <v-row v-else no-gutters style="width: 100%">
                    <v-col cols="6">
                      Start date:
                      {{ invalidDate(item.start_date) || "Not set" }}
                    </v-col>
                    <v-col cols="6">
                      End date: {{ invalidDate(item.end_date) || "Not set" }}
                    </v-col>
                  </v-row>
                </v-fade-transition>
              </v-col>
            </v-row>
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-card-title class="text-h5"> {{ item.title }} </v-card-title>
            <v-card-subtitle>
              {{ invalidDate(item.start_date) || "NOT SET" }}
              -
              {{ invalidDate(item.end_date) || "NOT SET" }}
            </v-card-subtitle>
            <v-card-text>
              <v-row>
                <v-col cols="4"> Question </v-col>
                <v-col cols="8">
                  <div v-html="item.question"></div>
                </v-col>
              </v-row>
              <v-row class="mb-3">
                <v-col cols="4"> Score </v-col>
                <v-col cols="8">
                  {{ item.score }}
                </v-col>
              </v-row>
              <v-row v-if="item.file" class="mb-3">
                <v-col cols="4"> Download File Question </v-col>
                <v-col cols="8">
                  <v-btn
                    small
                    color="primary"
                    dark
                    @click="download(item.file)"
                  >
                    Download File
                  </v-btn>
                </v-col>
              </v-row>

              <v-row class="mb-3">
                <v-col cols="4"> Language </v-col>
                <v-col cols="8">
                  <template v-if="item.lang">
                    {{ item.lang }}
                  </template>
                  <template v-else>
                    <v-autocomplete
                      v-model="selectLang"
                      :items="languages"
                      item-text="lang"
                      item-value="lang"
                    >
                    </v-autocomplete>
                  </template>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4"> File Import </v-col>
                <v-col cols="8">
                  <input
                    type="file"
                    class="form-control"
                    ref="file_upload"
                    v-on:change="onFileChange"
                    :accept="item.type"
                  />
                </v-col>
              </v-row>
            </v-card-text>
            <template>
              <v-btn color="green darken-1" text @click="submit(item)">
                Submit
              </v-btn>
            </template>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>

      <div v-if="viewAll">
        <v-expansion-panels focusable>
          <v-expansion-panel
            class="mt-3"
            style="background: #ffe57f"
            v-for="(item, i) in this.schedule_room.filter(
              (e) => !e.late && e.end_date <= this.getDateTime
            )"
            :key="i"
          >
            <v-expansion-panel-header v-slot="{ open }">
              <v-row no-gutters>
                <v-col cols="12">
                  {{ item.title }} (Out of time)
                  {{
                    item.late && getDateTime >= item.end_date ? "(Late)" : ""
                  }}&nbsp;
                  <v-rating
                    :value="item.level"
                    dense
                    half-increments
                    readonly
                    size="14"
                  ></v-rating>
                </v-col>
                <v-col cols="12" class="text--secondary">
                  <v-fade-transition leave-absolute>
                    <span v-if="open">
                      (Submit
                      {{
                        filterSubmissionRoom.find((e) => e.id == item.id).count
                      }})</span
                    >
                    <v-row v-else no-gutters style="width: 100%">
                      <v-col cols="6">
                        Start date:
                        {{ invalidDate(item.start_date) || "Not set" }}
                      </v-col>
                      <v-col cols="6">
                        End date: {{ invalidDate(item.end_date) || "Not set" }}
                      </v-col>
                    </v-row>
                  </v-fade-transition>
                </v-col>
              </v-row>
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-card-title class="text-h5"> {{ item.title }} </v-card-title>
              <v-card-subtitle>
                {{ invalidDate(item.start_date) || "NOT SET" }}
                -
                {{ invalidDate(item.end_date) || "NOT SET" }}
              </v-card-subtitle>
              <v-card-text>
                <v-row>
                  <v-col cols="4"> Question </v-col>
                  <v-col cols="8">
                    <div v-html="item.question"></div>
                  </v-col>
                </v-row>
                <v-row class="mb-3">
                  <v-col cols="4"> Score </v-col>
                  <v-col cols="8">
                    {{ item.score }}
                  </v-col>
                </v-row>
                <v-row v-if="item.file" class="mb-3">
                  <v-col cols="4"> Download File Question </v-col>
                  <v-col cols="8">
                    <v-btn
                      small
                      color="primary"
                      dark
                      @click="download(item.file)"
                    >
                      Download File
                    </v-btn>
                  </v-col>
                </v-row>

                <v-row class="mb-3">
                  <v-col cols="4"> Language </v-col>
                  <v-col cols="8">
                    <template v-if="item.lang">
                      {{ item.lang }}
                    </template>
                    <template v-else> { All} </template>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </div>
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
      selectLang: "",
      sendFile: null,
      sortby: "Sort",
    };
  },

  created() {
    this.getLanguage();
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
    async submit(item) {
      if (!this.sendFile) {
        this.snackBar(3500, "Please your input file.", "warning");
      }
      const lang = (this.loading = true);
      this.dialog = false;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("sourcefile", this.sendFile);
      formData.append("Lang", item.lang == null ? this.selectLang : item.lang);
      formData.append("problem_id", item.problemsId);
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
      this.sendFile = e.target.files[0];
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

