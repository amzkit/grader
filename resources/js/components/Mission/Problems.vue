<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation :onClick="fatchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-col>
            <v-data-table
              :headers="headers"
              :items="this.$store.state.data.schedule_all"
              class="elevation-1"
            >
              <template v-slot:[`item.index`]="{ index }">
                {{ index + 1 }}
              </template>
              <template v-slot:[`item.question`]="{ item }">
                {{ convertToPlain(item.question) }}
              </template>
              <template v-slot:[`item.file`]="{ item }">
                <div v-if="item.file">
                  <v-icon small class="mr-2" @click="download(item.file)">
                    mdi-file-download
                  </v-icon>
                </div>
                <div v-else>{{ "-" }}</div>
              </template>
              <template v-slot:[`item.start_date`]="{ item }">
                {{ invalidDate(item.start_date) }}
              </template>
              <template v-slot:[`item.end_date`]="{ item }">
                {{ invalidDate(item.end_date) }}
              </template>
            </v-data-table>
          </v-col>
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
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        {
          text: "Title",
          align: "start",
          sortable: true,
          value: "title",
        },
        { text: "Question", value: "question" },
        { text: "Score", value: "score" },
        { text: "File", value: "file" },
        {
          text: "Start Date",
          value: "start_date",
        },
        {
          text: "End Date",
          value: "end_date",
        },
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

    async download(item) {
      this.loading = true;
      await axios
        .get(`/api/schedule/download${item.replace("problem_file", "")}`)
        .then((response) => {
          window.location.href = `api/schedule/download${item.replace(
            "problem_file",
            ""
          )}`;
        })
        .catch((error) => {
          if (error.response.status === 404) {
            this.snackBar(3500, error.response.data.message, "error");
          } else {
            this.snackBar(3500, error, "error");
          }
        });
      this.loading = false;
    },

    invalidDate(item) {
      return item ? dayjs(item).format("MMMM D, YYYY") : "-";
    },
    convertToPlain(html) {
      var tempDivElement = document.createElement("div");
      tempDivElement.innerHTML = html;
      return tempDivElement.textContent || tempDivElement.innerText || "";
    },
    async fatchItemSchedule(item) {
      this.loading = true;
      if (item) {
        await axios
          .get("/api/schedule/" + item.courseId)
          .then((response) => {
            if (response.data.success == true) {
              this.$store.commit(
                "data/SET_SCHEDULES_ALL",
                response.data.payload
              );
            }
          })
          .catch((error) => {
            this.snackBar(3500, error, "error");
          });
      }
      this.loading = false;
    },
  },
};
</script>

