<template>
  <v-row>
    <Loading :loading="loading" />
    <v-col cols="2">
      <Navigation :onClick="fetchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <v-card
        class="mx-auto mb-4"
        max-width="100%"
        v-for="(item, i) in this.$store.state.data.schedule_all.filter((e) => {
          const missionSend = missionPass.find((p) => p.schedule_id == e.id);
          if (missionSend) {
            return null;
          }
          return e;
        })"
        :key="i"
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
            <v-card-title>Schedule</v-card-title>

            <v-card-text>
              <v-chip-group
                v-model="selection"
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
                problem.language.language = item.language;
                problem.language.type = item.type;
                problem.problem_id = item.problemsId;
                dialog = true;
              }
            "
          >
            SHOW MORE
          </v-btn>
        </v-card-actions>
      </v-card>
      <v-expansion-panels>
        <v-expansion-panel v-for="(item, i) in missionPass" :key="i">
          <v-expansion-panel-header disable-icon-rotate>
            {{ item.title }}
            <template v-slot:actions>
              <div v-if="item.message == 'waiting'">
                <v-icon color="primary">
                  mdi-checkbox-blank-circle-outline
                </v-icon>
              </div>
              <div v-else>
                <v-icon color="teal"> mdi-check </v-icon>
              </div>
            </template>
          </v-expansion-panel-header>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-col>

    <v-dialog v-model="dialog" persistent max-width="800">
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
              <v-btn small color="primary" dark @click="download(item.file)">
                Download File
              </v-btn>
            </v-col>
          </v-row>
          <v-row class="mb-3">
            <v-col cols="4"> Language </v-col>
            <v-col cols="8">
              {{ problem.language.language }}
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="4"> File Import </v-col>
            <v-col cols="8">
              <input
                type="file"
                class="form-control"
                ref="file_upload"
                :accept="problem.language.type"
                v-on:change="onFileChange"
              />
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="green darken-1"
            text
            @click="
              () => {
                $refs.file_upload.value = null;
                dialog = false;
              }
            "
          >
            Close
          </v-btn>
          <v-btn color="green darken-1" text @click="submit()"> Submit </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
        language: {
          language: "",
          type: "",
        },
      },
    };
  },

  computed: {
    getDateTime() {
      return dayjs(new Date()).format("MM-DD-YYYY hh:mm A");
    },
  },

  methods: {
    dayjs,

    async submit() {
      if (!this.problem.sendFile) {
        return console.log("NO");
      }
      this.loading = true;
      this.dialog = false;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("sourcefile", this.problem.sendFile);
      formData.append("Lang", this.problem.language.language);
      formData.append("problem_id", this.problem.problem_id);
      formData.append("course_id", this.course_id);
      await axios
        .post("/api/submission", formData, config)
        .then((response) => {
          if (response.data.success) {
            window.location.reload();
          }
        })
        .catch((error) => {
          console.error(error);
        });
      this.loading = false;
    },

    download(item) {
      window.location.href = `api/schedule/download${item.replace(
        "problem_file",
        ""
      )}`;
    },

    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY hh:mm A") : "-";
    },

    onFileChange(e) {
      this.problem.sendFile = e.target.files[0];
    },

    async fetchItemSchedule(item) {
      this.loading = true;
      this.course_id = item.courseId;
      this.fetchItemSubmission(item.courseId);
      if (item) {
        await axios
          .get("/api/schedule", {
            params: {
              course_id: item.courseId,
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
    },

    async fetchItemSubmission() {
      this.loading = true;
      await axios
        .get("/api/submission", {
          params: {
            course_id: this.course_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.missionPass = response.data.payload;
          }
        });
      this.loading = false;
    },
  },
};
</script>

