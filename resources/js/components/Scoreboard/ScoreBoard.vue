
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
              @click="fetchRoom(item.courseId)"
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
    <v-col>
      <v-row>
        <v-data-table
          :headers="headers"
          :items="data.classroom"
          :single-expand="true"
          :expanded.sync="expanded"
          item-key="name"
          show-expand
          class="elevation-1"
        >
          <template v-slot:top>
            <v-toolbar flat>
              <v-toolbar-title>ScoreBoard</v-toolbar-title>
              <v-spacer></v-spacer>
              <template
                v-if="
                  $store.state.data.user.role === 'admin' ||
                  $store.state.data.user.role === 'teacher'
                "
              >
                <v-select
                  v-model="exportData"
                  class="mr-5 mt-6"
                  :items="[
                    {
                      id: 1,
                      key: 'No Detail',
                    },
                    { id: 2, key: 'Detail' },
                  ]"
                  label="Select Template Export"
                  item-text="key"
                  item-value="id"
                  dense
                  solo
                ></v-select>
                <v-btn depressed color="primary" @click="onExport">
                  Primary
                </v-btn>
              </template>
            </v-toolbar>
          </template>
          <template v-slot:[`item.index`]="{ index }">
            {{ index + 1 }}
          </template>
          <template v-slot:[`item.problems`]="{ item }">
            {{
              `${mapDataMyScore(item.user_id).length}/${data.schedule.length}`
            }}
          </template>
          <template v-slot:[`item.totalScore`]="{ item }">
            {{
              `${mapDataMyScore(item.user_id).reduce(
                (t, n) => t + n.score,
                0
              )}/${data.schedule.reduce((t, n) => t + n.score, 0)}`
            }}
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
              <v-container fluid>
                <v-simple-table>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Title Problem</th>
                        <th class="text-left">Date Submission</th>
                        <th class="text-left">Count Submit</th>
                        <th class="text-left">Score</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(sc, index) in mapDataMyScore(item.user_id)"
                        :key="index"
                      >
                        <td>
                          {{ index + 1 }}
                        </td>
                        <td>
                          {{
                            data.schedule.find((e) => e.id == sc.schedule_id)
                              .title
                          }}
                        </td>
                        <td>
                          {{ sc.date_send }}
                        </td>
                        <td>
                          {{ sc.count }}
                        </td>
                        <td>
                          {{ sc.score }}
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-container>
            </td>
          </template>
        </v-data-table>
      </v-row>
    </v-col>
  </v-row>
</template>


<script>
import Excel from "exceljs";
import { saveAs } from "file-saver";
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
import { exportProblems, exportProblemsDetail } from "./ExportProblems";

export default {
  components: {
    Navigation,
    Loading,
    Snackbar,
  },
  data: function () {
    return {
      exportData: null,
      course_room: [],
      expanded: [],
      singleExpand: false,
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        { text: "Username", value: "username" },
        {
          text: "Name",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "Problems", value: "problems" },
        { text: "Total Score", value: "totalScore" },
      ],
      loading: false,
      items: [],
      data: [],
      search: "",
      json: [
        { name: "Dady", age: "21" },
        { name: "Jonh", age: "25" },
        { name: "James", age: "17" },
      ],
    };
  },
  async created() {
    this.getScoreboard();
    this.getClassrooms();
    this.fetchItemCourse();
  },
  computed: {
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
    async onExport() {
      const schedules = this.data.schedule;
      const classroom = this.data.classroom;
      const scoreboard = this.data.scoreboard;
      const classroom_name = this.data.classroom_name;

      try {
        if (this.exportData === 1) {
          const exportFile = await exportProblems(
            schedules,
            classroom,
            scoreboard,
            classroom_name
          );
          return saveAs(new Blob([exportFile]), `${classroom_name}.xlsx`);
        } else if (this.exportData === 2) {
          const exportFile = await exportProblemsDetail(
            schedules,
            classroom,
            scoreboard,
            classroom_name
          );
          return saveAs(new Blob([exportFile]), `${classroom_name}.xlsx`);
        } else {
          this.snackBar(3500, "Please select a format", "warning");
        }
      } catch (err) {
        this.snackBar(3500, "Please select a classroom.", "warning");
      }
    },
    fetchRoom(val) {
      this.$router
        .push({
          path: "/scoreboard",
          query: {
            course_id: val,
          },
        })
        .catch(() => {});
      this.getScoreboard();
    },
    async getScoreboard() {
      this.loading = true;
      await axios
        .get(`/api/scoreboard/${this.$route.query.course_id}`)
        .then((response) => {
          if (response.data.success == true) {
            this.data = response.data.payload;
          }
        })
        .catch(() => {
          this.snackBar(3500, "Please select a classroom.", "warning");
        });
      this.loading = false;
    },
    async fetchItemCourse() {
      this.loading = true;
      await axios
        .get("api/classroom")
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
    mapDataMyScore(user_id) {
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
      return convert(
        user_id
          ? this.data.scoreboard.filter((e) => e.user_id === user_id)
          : this.data.scoreboard
      );
    },
    async getClassrooms() {
      this.loading = true;
      await axios
        .get("/api/classrooms")
        .then((response) => {
          if (response.data.success == true) {
            this.items = response.data.payload.filter((e) => {
              if (
                this.$store.state.data.user.role !== "teacher" &&
                this.$store.state.data.user.role !== "admin"
              ) {
                return e.courseId !== 1;
              }
              return e;
            });
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

<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>



