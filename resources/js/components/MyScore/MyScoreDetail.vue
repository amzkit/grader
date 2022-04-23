<template>
  <div class="text-center">
    <Snackbar />
    <v-card v-if="comment != ''">
      <v-card-title>
        Comment
        <v-spacer></v-spacer>
      </v-card-title>
      <v-data-table
        :headers="headersComment"
        :items="comment"
        class="elevation-1 mb-5"
      >
        <template v-slot:[`item.index`]="{ index }">
          {{ index + 1 }}
        </template>
      </v-data-table>
    </v-card>

    <v-card>
      <v-card-title>
        My Score
        <v-spacer></v-spacer>
      </v-card-title>
      <v-data-table :headers="headers" :items="items" class="elevation-1">
        <template v-slot:[`item.index`]="{ index }">
          {{ index + 1 }}
        </template>
        <template v-slot:[`item.message`]="{ item }">
          <v-chip v-if="item.message == 'Y'" color="green" text-color="white">
            {{ item.message }}
          </v-chip>
          <v-chip v-if="item.message == 'N'" color="red" text-color="white">
            {{ item.message }}
          </v-chip>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>


<script>
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    Snackbar,
  },
  data: function () {
    return {
      loading: false,
      items: [],
      comment: [],
      headers: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        {
          text: "Input",
          align: "center",
          sortable: false,
          value: "testcase_input",
        },
        { text: "Your output", value: "output", align: "center" },
        { text: "Correct output", value: "testcase_output", align: "center" },
        { text: "Message", value: "message", align: "center" },
      ],
      headersComment: [
        {
          text: "#",
          align: "center",
          sortable: false,
          value: "index",
        },
        {
          text: "ID",
          align: "center",
          sortable: false,
          value: "username",
        },
        {
          text: "Name Comment",
          align: "center",
          sortable: false,
          value: "name",
        },
        { text: "Comment", value: "comment", align: "center" },
      ],
    };
  },

  created() {
    this.myScore();
    this.commentScore();
  },

  methods: {
    ...mapActions("snackbar", ["showSnack"]),
    snackBar(timeout = 3500, text = "Successfully", color = "success") {
      this.showSnack({
        text: text,
        color: color,
        timeout: timeout,
      });
    },
    async commentScore() {
      await axios
        .get("/api/comment", {
          params: {
            submission_id: this.$route.query.submission_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.comment = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
    },

    async myScore() {
      let items = [];
      await axios
        .get("/api/score", {
          params: {
            course_id: this.$route.query.course_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            items = response.data.payload2;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.items = items.filter(
        (e) => e.submission_id == this.$route.query.submission_id
      );
      this.loading = false;
    },
  },
};
</script>