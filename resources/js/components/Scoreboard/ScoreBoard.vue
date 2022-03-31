<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation :onClick="fatchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-expansion-panels>
            <v-expansion-panel v-for="(item, i) in schedule" :key="i">
              <v-expansion-panel-header disable-icon-rotate>
                <v-row no-gutters>
                  <v-col cols="4">
                    <v-fade-transition leave-absolute>
                      <v-row no-gutters style="width: 100%">
                        <v-col cols="6">
                          {{ item.title }}
                        </v-col>
                        <v-col cols="6"> Score : {{ item.score }} </v-col>
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
                <div v-if="$store.state.data.user.role == 'ta'">
                  <v-data-table
                    :headers="headers"
                    :items="
                      scoreboard.filter((e) => e.problem_id == item.problemsId)
                    "
                    :items-per-page="5"
                    class="elevation-1"
                    @click:row="Item($event)"
                  ></v-data-table>
                </div>
                <div v-else>
                  <v-data-table
                    :headers="headers"
                    :items="
                      scoreboard.filter((e) => e.problem_id == item.problemsId)
                    "
                    :items-per-page="5"
                    class="elevation-1"
                  ></v-data-table>
                </div>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
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
      scoreboard: [],
      schedule: [],
      analyses: [],
      headers: [
        {
          text: "Name",
          sortable: false,
          value: "name",
        },
        { text: "Student ID", value: "username" },
        { text: "Score", value: "score" },
      ],
    };
  },
  async created() {},
  computed: {},
  methods: {
    dayjs,
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY hh:mm A") : "-";
    },
    Item(val) {
      // this.$router.push({ name: "comment", params: { data: val } });
      this.$router.push({
        path: "comment",
        query: { submission_id: val.id },
      });
    },
    async fatchItemSchedule(item) {
      this.loading = true;
      this.course_id = item.courseId;
      if (item) {
        let items = [];
        let items2 = [];
        await axios
          .get("/api/scoreboard", {
            params: {
              course_id: item.courseId,
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              items = response.data.payload;
              items2 = response.data.payload2;
            }
          });
        this.scoreboard = items;
        this.schedule = items2;
      }
      console.log("analyses", this.analyses);
      this.loading = false;
    },
  },
};
</script>

