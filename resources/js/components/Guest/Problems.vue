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
              @click="fetchItemProblemsById(item.id)"
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

    <v-col>
      <v-container text-xs-center fluid>
        <v-layout row wrap>
          <v-flex xs12>
            <div v-if="problems">
              <v-card class="mx-auto" max-width="700">
                <v-card-title class="text-h5">
                  {{ problems.title }}
                </v-card-title>
                <v-card-text>
                  <v-row>
                    <v-col cols="4"> Question </v-col>
                    <v-col cols="8">
                      <div v-html="problems.question"></div>
                    </v-col>
                  </v-row>
                  <v-row class="mb-3">
                    <v-col cols="4"> Score </v-col>
                    <v-col cols="8">
                      {{ problems.score }}
                    </v-col>
                  </v-row>
                  <v-row v-if="problems.file" class="mb-3">
                    <v-col cols="4"> Download File Question </v-col>
                    <v-col cols="8">
                      <v-btn
                        small
                        color="primary"
                        dark
                        @click="download(problems.file)"
                      >
                        Download File
                      </v-btn>
                    </v-col>
                  </v-row>
                  <v-row class="mb-3">
                    <v-col cols="4" class="mt-1"> Level </v-col>
                    <v-col cols="8">
                      <v-rating
                        :value="problems.level"
                        dense
                        half-increments
                        color="yellow darken-3"
                        background-color="grey darken-1"
                        hover
                        readonly
                      ></v-rating>
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </div>
          </v-flex>
        </v-layout>
      </v-container>
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
      search: "",
      loading: false,
      schedules: [],
      problems: null,
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
        { text: "Score", value: "score" },
      ],
    };
  },

  created() {
    this.fetchItemSchedule();
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

    async fetchItemProblemsById(item) {
      this.loading = true;
      await axios
        .get("/api/user-problems/" + item)
        .then((response) => {
          if (response.data.success == true) {
            this.problems = response.data.payload;
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

