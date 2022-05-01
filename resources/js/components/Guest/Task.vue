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
              @click="fetchItemScheduleById(item.id)"
            >
              <v-list-item-content>
                <v-list-item-title v-text="item.title"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
    <v-col cols="1"> <v-divider vertical></v-divider></v-col>
    <v-col cols="7">
      <div v-if="schedule_room">
        <v-card class="mx-auto" max-width="1200">
          <v-card-title class="text-h5">
            {{ schedule_room.title }}
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="4"> Question </v-col>
              <v-col cols="8">
                <div v-html="schedule_room.question"></div>
              </v-col>
            </v-row>
            <v-row class="mb-3">
              <v-col cols="4"> Score </v-col>
              <v-col cols="8">
                {{ schedule_room.score }}
              </v-col>
            </v-row>
            <v-row v-if="schedule_room.file" class="mb-3">
              <v-col cols="4"> Download File Question </v-col>
              <v-col cols="8">
                <v-btn
                  small
                  color="primary"
                  dark
                  @click="download(schedule_room.file)"
                >
                  Download File
                </v-btn>
              </v-col>
            </v-row>
            <v-row class="mb-3">
              <v-col cols="4"> Language </v-col>
              <v-col cols="8">
                {{ schedule_room.language }}
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
                  :accept="schedule_room.language_accept"
                />
              </v-col>
            </v-row>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" text @click="submit()">
              Submit
            </v-btn>
          </v-card-actions>
        </v-card>
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
      schedule_room: null,
      search: "",
      loading: false,
      course_id: 0,
      missionPass: [],
      file: null,
      schedules: [],
      submissions: [],
    };
  },

  created() {
    this.fetchItemSchedule();
    this.getLanguage();
  },

  computed: {
    filteredItems() {
      return _.orderBy(
        this.schedules.filter((item) => {
          return item.title.toLowerCase().includes(this.search.toLowerCase());
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
      if (!this.file) {
        this.snackBar(3500, "Please your input file.", "warning");
      }
      this.loading = true;
      this.dialog = false;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("sourcefile", this.file);
      formData.append("Lang", this.schedule_room.language);
      formData.append("problem_id", this.schedule_room.problemsId);
      formData.append("course_id", this.schedule_room.course_id);
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

    onFileChange(e) {
      this.file = e.target.files[0];
    },

    async fetchItemSchedule() {
      this.loading = true;
      await axios
        .get("/api/user-schedule")
        .then((response) => {
          if (response.data.success == true) {
            this.schedules = response.data.schedules;
            this.submissions = response.data.submissions;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async fetchItemScheduleById(item) {
      this.loading = true;
      await axios
        .get("/api/user-schedule/" + item)
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
  },
};
</script>

