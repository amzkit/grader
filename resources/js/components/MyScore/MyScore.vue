<template>
  <v-row>
    <Loading :loading="this.loading" />
    <Snackbar />
    <v-col cols="2">
      <Navigation :onClick="fetchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-data-table
            :headers="headers"
            :items="mapDataMyScore"
            class="elevation-1 row-pointer"
            @click:row="handleClick"
          >
            <template v-slot:[`item.index`]="{ index }">
              {{ index + 1 }}
            </template>

            <template v-slot:[`item.detail`]="{ item }">
              <v-col>
                <v-chip
                  v-for="(i, n) in item.message.match(/[A-Z]/g)"
                  :key="n"
                  :color="i == 'Y' ? 'green' : 'red'"
                  text-color="white"
                >
                  {{ i }}
                </v-chip>
              </v-col>
            </template>

            <template v-slot:[`item.score`]="{ item }">
              <v-chip color="primary" dark>
                {{ item.score }}
              </v-chip>
            </template>
            <template v-slot:[`item.comment_count`]="{ item }">
              {{
                `${comment.reduce((n, e) => {
                  return item.id == e.submission_id ? n + 1 : n;
                }, 0)} Comment`
              }}
            </template>
            <template v-slot:[`item.created_at`]="{ item }">
              {{ invalidDate(item.created_at) }}
            </template>
          </v-data-table>
        </v-row>
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
      loading: false,
      myScore: [],
      comment: [],
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        {
          text: "Title",
          align: "center",
          sortable: false,
          value: "title",
        },
        { text: "Detail", value: "detail", align: "center", width: 350 },
        { text: "Count Submit", value: "count", align: "center" },
        { text: "Score", value: "score", align: "center" },
        { text: "Comment Code", value: "comment_count", align: "center" },
        { text: "Date Submission", value: "created_at", align: "center" },
      ],
    };
  },
  computed: {
    mapDataMyScore() {
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
      return convert(this.myScore);
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
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm:ss") : "-";
    },

    handleClick(row) {
      if (row.IsAnalysis === 1) {
        this.$router.push({
          path: "my-score-mission",
          query: { submission_id: row.id, course_id: this.course_id },
        });
      } else
        this.snackBar(
          3500,
          "You cannot view the results of the analysis for this problem.",
          "warning"
        );
    },

    async fetchItemSchedule(item) {
      this.loading = true;
      this.course_id = item.courseId;
      if (item) {
        let items = [];
        let items2 = [];

        await axios
          .get("/api/score", {
            params: {
              course_id: item.courseId,
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              items = response.data.payload;
              items2 = response.data.comment;
            }
          })
          .catch((error) => {
            this.snackBar(3500, error);
          });
        this.myScore = items;
        this.comment = items2;
      }
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

