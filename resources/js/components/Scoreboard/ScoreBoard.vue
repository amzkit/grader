
<template>
  <v-container>
    <Snackbar :snackbar="snackbar" :text="text" />
    <Loading :loading="loading" />
    <v-row justify="space-between">
      <v-col md="4">
        <v-autocomplete
          v-model="classroom"
          :items="items"
          label="Classroom"
          item-text="course_name"
          item-value="courseId"
        >
        </v-autocomplete>
      </v-col>
      <v-col md="4">
        <v-row justify="end" align="center">
          <v-chip class="ma-2" label>
            Number of student : {{ data.student_count }} in:
            {{ data.classroom_name }}
          </v-chip>
        </v-row>
      </v-col>
    </v-row>
    <div>
      <v-card>
        <v-card-title>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="data.classroom"
          :class="[
            'elevation-1',
            $store.state.data.user.role == 'ta' ||
            $store.state.data.user.role == 'teacher'
              ? 'row-pointer'
              : null,
          ]"
          :search="search"
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
          <template v-slot:[`item.mission`]="{ item }">
            {{
              `${data.submission.reduce(
                (n, e) => (e.user_id === item.user_id ? n + 1 : n),
                0
              )}/${data.schedules_count}`
            }}
          </template>
          <template v-slot:[`item.score`]="{ item }">
            {{
              data.submission.reduce((n, e) => {
                if (e.user_id === item.user_id) {
                  return n + e.score;
                }
              }, 0) > 0
                ? data.submission.reduce((n, e) => {
                    if (e.user_id === item.user_id) {
                      return n + e.score;
                    }
                  }, 0)
                : 0
            }}
          </template>
        </v-data-table>
      </v-card>
    </div>
  </v-container>
</template>


<script>
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
export default {
  components: {
    Navigation,
    Loading,
    Snackbar,
  },
  data: function () {
    return {
      loading: false,
      snackbar: false,
      text: "",
      items: [],
      data: [],
      search: "",
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        { text: "Student ID", value: "username" },
        { text: "Name", value: "name" },
        {
          text: "Problem",
          sortable: false,
          value: "mission",
        },
        { text: "Score", value: "score" },
      ],
    };
  },
  async created() {
    this.getClassrooms();
  },
  computed: {
    classroom: {
      get() {
        this.text = "Please select a classroom !!";
        this.snackbar = true;
      },
      async set(value) {
        this.loading = true;
        await axios
          .get(`/api/scoreboard/${value}`)
          .then((response) => {
            if (response.data.success == true) {
              this.data = response.data.payload;
            }
          })
          .catch((error) => {
            console.log(error);
          });
        this.loading = false;
      },
    },
  },
  methods: {
    dayjs,
    async getClassrooms() {
      this.loading = true;
      await axios.get("/api/classrooms").then((response) => {
        if (response.data.success == true) {
          this.items = response.data.payload;
        }
      });
      this.loading = false;
    },
    async getScoreboard(item) {
      this.loading = true;
      if (item) {
        await axios
          .get(`/api/scoreboard/${item}`)
          .then((response) => {
            if (response.data.success == true) {
              this.data = response.data.payload;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
      this.loading = false;
    },
    item(val) {
      this.$router.push({
        path: "/scoreboard-score",
        query: { course_id: val.course_id, user_id: val.user_id },
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

