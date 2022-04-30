<template>
  <v-container>
    <v-container fluid fill-height>
      <v-layout justify-center align-center>
        <v-flex text-xs-center>
          <v-card class="elevation-20">
            <v-card-title>
              <v-icon large left> mdi-help-circle </v-icon>
              <span class="text-h6 font-weight-light">
                {{ items.problems && items.problems.title }}
              </span>
            </v-card-title>
            <v-card-text class="text-h5 font-weight-bold">
              {{ convertToPlain(items.problems && items.problems.question) }}
            </v-card-text>
            <v-container class="grey lighten-5">
              <v-row justify="center">
                <v-col md="12">
                  <v-card class="pa-2" outlined tile>
                    <v-card class="pa-2" tile outlined> Code </v-card>
                    <textarea id="code"></textarea>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
            <v-col cols="12" class="mt-2">
              <v-textarea
                clearable
                outlined
                v-model="comment"
                clear-icon="mdi-close-circle"
                placeholder="Comment"
                rows="2"
                row-height="15"
              ></v-textarea>
              <v-btn block color="primary" @click="insertComment">
                Comment
              </v-btn>
            </v-col>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
    <Snackbar />
    <Loading :loading="this.loading" />
  </v-container>
</template>

<script>
import Loading from "../Loading/Loading.vue";
import { VueEditor } from "vue2-editor";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
import * as CodeMirror from "codemirror";
import "codemirror/lib/codemirror.css";
import "codemirror/theme/dracula.css";

export default {
  components: {
    Loading,
    VueEditor,
    Snackbar,
  },
  name: "Comment",
  data: function () {
    return {
      items: [],
      comment: "",
      loading: false,
      customToolbar: [{}],
    };
  },
  computed: {
    content() {
      return this.item.code;
    },
  },
  created() {
    this.getScoreboard();
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

    convertToPlain(html) {
      var tempDivElement = document.createElement("div");
      tempDivElement.innerHTML = html;
      return tempDivElement.textContent || tempDivElement.innerText || "";
    },

    async getScoreboard() {
      this.loading = true;
      await axios
        .get(`/api/submission/scoreboard`, {
          params: {
            submission_id: this.$route.query.submission_id,
            problem_id: this.$route.query.problem_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.items = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        theme: "dracula",
        readOnly: "nocursor",
      }).setValue(this.items.submission.code);
      this.loading = false;
    },

    async insertComment() {
      this.loading = true;
      await axios
        .post("/api/comment", {
          submission_id: this.$route.query.submission_id,
          comment: this.comment,
        })
        .then(() => {
          window.location.href = "/analysis";
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },
  },
};
</script>