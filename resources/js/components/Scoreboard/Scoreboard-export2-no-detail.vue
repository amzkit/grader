
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
        <v-card>
          <v-btn depressed color="primary" @click="onExport"> Primary </v-btn>
          <v-data-table
            :headers="headers"
            :items="data.classroom"
            :single-expand="singleExpand"
            :expanded.sync="expanded"
            item-key="name"
            show-expand
            class="elevation-1"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>ScoreBoard</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-switch
                  v-model="singleExpand"
                  label="Single expand"
                  class="mt-2"
                ></v-switch>
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
                          <!-- @click="handleClick(sc)" -->
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
        </v-card>
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
export default {
  components: {
    Navigation,
    Loading,
    Snackbar,
  },
  data: function () {
    return {
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

      const columns = [
        { header: "No", key: "No" },
        { header: "Name", key: "Name" },
        { header: "Username", key: "Username" },
      ];

      schedules.forEach((e) => {
        columns.push({
          header: `${e.title} (${e.score})`,
          key: e.title.replace(" ", ""),
        });
      });

      const datas = this.data.classroom;

      const workSheetName = `TEST`;

      const workBookName = "Time Report " + workSheetName;

      const workbook = new Excel.Workbook();
      const workSheet = workbook.addWorksheet(workSheetName);

      workSheet.columns = columns;
      workSheet.getRow(1).font = { bold: true };
      for (var c = 1; c <= columns.length; c++) {
        workSheet.getCell(1, c).fill = {
          type: "pattern",
          pattern: "solid",
          fgColor: { argb: "70AD47" },
        };
      }

      workSheet.columns.forEach((col, i) => {
        col.alignment = { horizontal: "center", vertical: "middle" };
        col.width = col.header !== undefined ? col.header.length + 10 : 0;
      });

      let row = 0;

      datas.forEach((data, index) => {
        const schedule = this.data.schedule;
        const scoreBoard = this.mapDataMyScore(data.user_id);

        let rowObj = {
          No: String(index + 1),
          Name: data.name,
          Username: data.username,
        };

        schedule.forEach((sch, index) => {
          const scoreBoard = this.mapDataMyScore(data.user_id);

          const res = scoreBoard.find((e) => e.schedule_id === sch.id);
          rowObj[sch.title.replace(" ", "")] = res ? res.score : "-";
        });
        workSheet.addRow(rowObj);
        row += 1;

        workSheet.eachRow({ includeEmpty: true }, function (row) {
          row.eachCell({ includeEmpty: true }, function (cell) {
            cell.border = {
              top: { style: "thin" },
              left: { style: "thin" },
              bottom: { style: "thin" },
              right: { style: "thin" },
            };
          });
        });
      });

      const buf = await workbook.xlsx.writeBuffer();
      saveAs(new Blob([buf]), `${workBookName}.xlsx`);
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
        .catch((error) => {});
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



