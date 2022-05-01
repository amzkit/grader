<template>
  <v-row>
    <Snackbar />
    <Loading :loading="loading" />
    <v-col cols="3">
      <v-card class="mx-auto" max-width="300">
        <v-list dense>
          <v-subheader>Problems</v-subheader>
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
              @click="fetchItemScoreBoardById(item.id)"
            >
              <v-list-item-content>
                <v-list-item-title v-text="item.title"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
    <v-col cols="1"> <v-divider vertical></v-divider></v-col>

    <v-col cols="7">
      <v-row justify="center">
        <v-data-table
          :headers="headers"
          :items="items"
          class="elevation-1 row-pointer"
        >
          <template v-slot:[`item.index`]="{ index }">
            {{ index + 1 }}
          </template>
          <template v-slot:[`item.score`]="{ item }">
            {{ `${item.submission_score}/${item.problem_score}` }}
          </template>
        </v-data-table>
      </v-row>
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
      items: [],
      search: "",
      loading: false,
      schedules: [],
      scoreboard: [],
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        {
          text: "Date Send",
          sortable: false,
          value: "date_send",
        },
        { text: "Name", value: "name" },
        { text: "Language", value: "Lang" },
        { text: "Count Submit", value: "count", align: "center" },
        { text: "Score", value: "score" },
      ],
    };
  },

  async created() {
    await this.fetchItemSchedule();
  },

  computed: {
    getDateTime() {
      return dayjs(new Date()).format("MM-DD-YYYY HH:mm");
    },

    filteredItems() {
      return _.orderBy(
        this.schedules.filter((item) => {
          return item.title.toLowerCase().includes(this.search.toLowerCase());
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

    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY HH:mm") : "-";
    },

    async fetchItemSchedule() {
      this.loading = true;
      await axios
        .get("/api/user-schedule")
        .then((response) => {
          if (response.data.success == true) {
            this.schedules = response.data.schedules;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async fetchItemScoreBoardById(item) {
      this.loading = true;
      await axios
        .get("/api/user-scoreboard/" + item)
        .then((response) => {
          if (response.data.success == true) {
            this.scoreboard = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });

      if (this.scoreboard.scoreboard) {
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
        this.items = convert(this.scoreboard.scoreboard);
      }
      this.loading = false;
    },
  },
};
</script>

