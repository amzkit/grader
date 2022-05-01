<template>
  <v-row justify="center">
    <Loading :loading="this.loading" />
    <Snackbar />
    <v-col cols="2">
      <Navigation :onClick="fetchItemSchedule" />
    </v-col>
    <v-col cols="9">
      <v-data-table
        :headers="headers"
        :items="items.classroom"
        class="elevation-1 row-pointer"
        @click="handleClick"
        @click:row="handleClick"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Analysis</v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
        </template>
        <template v-slot:[`item.index`]="{ index }">
          {{ index + 1 }}
        </template>
        <template v-slot:[`item.problems`]="{ item }">
          {{
            `${mapDataMyScore(item.user_id).length}/${items.schedule.length}`
          }}
        </template>
        <template v-slot:[`item.totalScore`]="{ item }">
          {{
            `${mapDataMyScore(item.user_id).reduce(
              (t, n) => t + n.score,
              0
            )}/${items.schedule.reduce((t, n) => t + n.score, 0)}`
          }}
        </template>
      </v-data-table>
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
      items: [],
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
    };
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
        this.items.scoreboard.filter((e) => e.user_id === user_id)
      );
    },

    handleClick(row) {
      const items = this.items.scoreboard.find(
        (e) => e.user_id === row.user_id
      );
      if (items) {
        this.$router.push({
          path: "/analysis-problems",
          query: { course_id: items.course_id, user_id: items.user_id },
        });
      } else {
      }
    },

    async fetchItemSchedule(item) {
      this.loading = true;
      if (item) {
        await axios
          .get(`/api/scoreboard/${item.courseId}`)
          .then((response) => {
            if (response.data.success == true) {
              this.items = response.data.payload;
            }
          })
          .catch((error) => {});
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

