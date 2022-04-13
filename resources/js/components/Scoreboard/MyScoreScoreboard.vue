<template>
  <v-row>
    <Loading :loading="this.loading" />
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
  </v-row>
</template>


<script>
import dayjs from "dayjs";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Loading,
  },
  data: function () {
    return {
      loading: false,
      myScore: [],
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
        { text: "Date Submission", value: "created_at", align: "center" },
      ],
    };
  },
  async created() {
    this.fatchItemSchedule();
  },
  computed: {},
  methods: {
    dayjs,
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY hh:mm A") : "-";
    },
    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY hh:mm A") : "-";
    },
    item(val) {
      this.$router.push({
        path: "/comment",
        query: {
          user_id: val.user_id,
          problem_id: val.problem_id,
          schedule_id: val.schedule_id,
        },
      });
    },
    async fatchItemSchedule() {
      let items = [];
      await axios
        .get("/api/score", {
          params: {
            course_id: this.$route.query.course_id,
            user_id: this.$route.query.user_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            items = response.data.payload;
          }
        });
      this.myScore = items;

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


