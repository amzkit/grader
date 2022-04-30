<template>
  <!-- <v-row>
    <Loading :loading="this.loading" />
    <Snackbar />
    <div v-if="!this.$store.state.data.loading">
      <v-row justify="center">
        <v-data-table
          :headers="headers"
          :items="myScore"
          :class="[
            'elevation-1',
            $store.state.data.user.role == 'ta' ||
            $store.state.data.user.role == 'teacher'
              ? 'row-pointer'
              : null,
          ]"
          @click:row="
            $store.state.data.user.role == 'ta' ||
            $store.state.data.user.role == 'teacher'
              ? item($event)
              : null
          "
        >
          <template v-slot:[`item.index`]="{ index }">
            {{ index + 1 }}
          </template>

          <template v-slot:[`item.detail`]="{ item }">
            <v-col>
              <v-chip
                v-for="(i, index) in item.message.match(/[A-Z]/g)"
                :key="index"
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
          <template v-slot:[`item.created_at`]="{ item }">
            {{ invalidDate(item.created_at) }}
          </template>
        </v-data-table>
      </v-row>
    </div>
  </v-row> -->
  <v-row>
    <Loading :loading="this.loading" />
    <Snackbar />
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
  </v-row>
</template>


<script>
import dayjs from "dayjs";
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
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
  async created() {
    this.fetchItemSchedule();
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
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm") : "-";
    },
    handleClick(val) {
      this.$router.push({
        path: "/analysis-detail",
        query: {
          user_id: val.user_id,
          problem_id: val.problem_id,
          schedule_id: val.schedule_id,
          submission_id: val.id,
          course_id: this.$route.query.course_id,
        },
      });
    },
    async fetchItemSchedule() {
      await axios
        .get("/api/score", {
          params: {
            course_id: this.$route.query.course_id,
            user_id: this.$route.query.user_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.myScore = response.data.payload;
            this.comment = response.data.comment;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
    },
  },
};
</script>

<style lang="css" scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>


