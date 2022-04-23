<template>
  <v-row>
    <Loading :loading="this.loading" />
    <Snackbar />
    <v-col cols="12">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-data-table
            :headers="headers"
            :items="myScore"
            class="elevation-1 row-pointer"
            @click:row="Item($event)"
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
        { text: "Detail", value: "detail", align: "center", width: 450 },
        { text: "Score", value: "score", align: "center" },
        { text: "Comment Code", value: "comment_count", align: "center" },
        { text: "Date Submission", value: "created_at", align: "center" },
      ],
    };
  },
  created() {
    this.fetchItemMyScore();
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
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm") : "-";
    },
    Item(val) {
      this.$router.push({
        path: "my-score-mission",
        query: { submission_id: val.id, course_id: this.course_id },
      });
    },
    async fetchItemMyScore() {
      this.loading = true;
      await axios
        .get("/api/score-guest")
        .then((response) => {
          if (response.data.success == true) {
            this.myScore = response.data.payload;
            this.comment = response.data.comment;
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

