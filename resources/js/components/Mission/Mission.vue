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
                  v-for="(item, i) in this.$store.state.data.schedule_all"
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
                                background-color="white"
                                color="yellow accent-4"
                                dense
                                half-increments
                                hover
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
                          <v-expansion-panel-content>
                            {{ item.question }}
                          </v-expansion-panel-content>
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
                          <v-file-input
                            label="File input"
                            v-model="files"
                          ></v-file-input>
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

              <!-- <v-expansion-panels>
                <v-expansion-panel>
                  <v-expansion-panel-header disable-icon-rotate>
                    Item
                    <template v-slot:actions>
                      <v-icon color="teal"> mdi-check </v-icon>
                    </template>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-expansion-panels> -->
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
      files: null,
    };
  },
  async created() {
    // await this.check_user();
  },
  methods: {
    dayjs,
    download(item) {
      window.location.href = `api/schedule/download${item.replace(
        "problem_file",
        ""
      )}`;
    },
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY") : "-";
    },
    async sendMission(item) {
      let formData = new FormData();
      if (this.files) {
        for (let file in this.files) {
          formData.append("file", file);
          formData.append("studentId", 1);
          formData.append("problemsId", item.problemsId);
        }
        // axios
        //   .post(
        //     "/////////////////////////",
        //     {
        //       files: formData,
        //       test: "test",
        //     },
        //     {
        //       headers: {
        //         "Content-Type": "multipart/form-data",
        //       },
        //     }
        //   )
        //   .then((response) => {
        //     console.log("Success!");
        //     console.log({ response });
        //   })
        //   .catch((error) => {
        //     console.log({ error });
        //   });
      } else {
        console.log("there are no files.");
      }
    },
    async fatchItemSchedule(item) {
      this.loading = true;
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
  },
};
</script>

