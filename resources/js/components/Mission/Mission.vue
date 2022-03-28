<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation :onClick="fatchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-col>
            <div>
              <v-expansion-panels class="mb-6">
                <v-expansion-panel
                  v-for="(
                    item, i
                  ) in this.$store.state.data.schedule_all.filter((e) => {
                    const missionSend = missionPass.find(
                      (p) => p.schedule_id == e.id
                    );
                    if (missionSend) {
                      return null;
                    }
                    return e;
                  })"
                  :key="i"
                >
                  <v-expansion-panel-header expand-icon="mdi-menu-down">
                    <v-row no-gutters>
                      <v-col cols="4">
                        <v-fade-transition leave-absolute>
                          <v-row no-gutters style="width: 100%">
                            <v-col cols="6">
                              {{ item.title }}
                            </v-col>
                            <v-col>
                              <v-rating
                                v-model="item.level"
                                color="yellow accent-4"
                                background-color="grey darken-1"
                                dense
                                size="18"
                              ></v-rating>
                            </v-col>
                          </v-row>
                        </v-fade-transition>
                      </v-col>

                      <v-col cols="8" class="text--secondary">
                        <v-fade-transition leave-absolute>
                          <v-row no-gutters style="width: 100%">
                            <v-col cols="6">
                              Start date:
                              {{ invalidDate(item.start_date) || "NOT SET" }}
                            </v-col>
                            <v-col cols="6">
                              End date:
                              {{ invalidDate(item.end_date) || "NOT SET" }}
                            </v-col>
                          </v-row>
                        </v-fade-transition>
                      </v-col>
                    </v-row>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <v-row no-gutters>
                      <v-spacer></v-spacer>

                      <v-row>
                        <v-col cols="4" class="d-flex align-center">
                          <v-expansion-panel-content>
                            Question
                          </v-expansion-panel-content>
                        </v-col>
                        <v-col cols="8" class="d-flex align-center">
                          <div v-html="item.question"></div>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="4" class="d-flex align-center">
                          <v-expansion-panel-content>
                            Language
                          </v-expansion-panel-content>
                        </v-col>
                        <v-col cols="8" class="d-flex align-center">
                          <v-expansion-panel-content>
                            {{ item.language }}
                          </v-expansion-panel-content>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="4" class="d-flex align-center">
                          <v-expansion-panel-content>
                            Score
                          </v-expansion-panel-content>
                        </v-col>
                        <v-col cols="8" class="d-flex align-center">
                          <v-expansion-panel-content>
                            {{ item.score }}
                          </v-expansion-panel-content>
                        </v-col>
                      </v-row>

                      <v-row v-if="item.file">
                        <v-col cols="4" class="d-flex align-center">
                          <v-expansion-panel-content>
                            Download File Question
                          </v-expansion-panel-content>
                        </v-col>
                        <v-col cols="8" class="d-flex align-center">
                          <v-expansion-panel-content>
                            <v-btn
                              key="0"
                              small
                              color="primary"
                              dark
                              @click="download(item.file)"
                            >
                              Download File
                            </v-btn>
                          </v-expansion-panel-content>
                        </v-col>
                      </v-row>

                      <v-row>
                        <v-col cols="4" class="d-flex align-center">
                          <v-expansion-panel-content>
                            File Import
                          </v-expansion-panel-content>
                        </v-col>
                        <v-col cols="8" class="d-flex align-center">
                          <input
                            type="file"
                            class="form-control"
                            :accept="item.type"
                            v-on:change="onFileChange"
                          />
                        </v-col>
                      </v-row>
                    </v-row>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        depressed
                        color="primary"
                        @click="sendMission(item)"
                      >
                        SEND MISSION
                      </v-btn>
                    </v-card-actions>
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels>

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
            </div>
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
      loading: false,
      loader: null,
      loadingDownload: false,
      file: null,
      course_id: 0,
      missionPass: [],
      customToolbar: [["clean"]],
    };
  },
  async created() {},

  computed: {
    getDateTime() {
      return dayjs(new Date()).format("MM-DD-YYYY hh:mm A");
    },
  },
  methods: {
    dayjs,
    err(item) {
      console.log(item);
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
      this.file = e.target.files[0];
    },
    async sendMission(item) {
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("sourcefile", this.file);
      formData.append("Lang", item.language);
      formData.append("problem_id", item.problemsId);
      formData.append("course_id", this.course_id);
      await axios
        .post("/api/submission", formData, config)
        .then(function (response) {
          location.reload();
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    async fatchItemSchedule(item) {
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

