<template>
  <v-row>
    <Snackbar />
    <v-col cols="3">
      <v-card class="mx-auto" max-width="300">
        <v-list dense>
          <v-subheader>Analysis</v-subheader>
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
              @click="fetchCase(item)"
            >
              <v-list-item-content>
                <v-list-item-title v-text="item.message"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
      <v-card class="mx-auto mt-3" max-width="300" v-if="comment.length > 0">
        <v-list dense>
          <v-subheader>Comment</v-subheader>
          <v-text-field
            class="mx-3"
            label="Search"
            prepend-inner-icon="search"
            v-model="searchComment"
            clearable
            solo
            dense
          ></v-text-field>
          <v-list-item-group>
            <v-list-item
              v-for="(item, i) in filteredItemsComment"
              :key="i"
              @click="fetchComment(item)"
            >
              <v-list-item-content>
                <v-list-item-title v-text="item.name"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
    <v-col cols="1"> <v-divider vertical></v-divider></v-col>
    <v-col cols="7">
      <v-card class="mx-auto mb-4" max-width="100%">
        <v-card>
          <v-card-title class="text-h5">Analysis </v-card-title>
          <v-card-text>
            <v-row>
              <v-col cols="4"> Input </v-col>
              <v-col cols="8">
                {{ analysisItem.testcase_input }}
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="4"> Your output </v-col>
              <v-col cols="8">
                {{ analysisItem.output }}
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="4"> Correct output </v-col>
              <v-col cols="8">
                {{ analysisItem.testcase_output }}
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-card>
      <v-card class="mx-auto mb-4" max-width="100%" v-if="comment.length > 0">
        <v-card-title class="text-h5"> Comment </v-card-title>
        <v-card>
          <v-card-text>
            <v-row>
              <v-col cols="4"> Message : </v-col>
              <v-col cols="8">
                {{ commentItem.message }}
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-card>
    </v-col>
  </v-row>
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
      search: "",
      searchComment: "",
      analysis: [],
      comment: [],
      analysisItem: {
        message: "",
        output: "",
        testcase_input: "",
        testcase_output: "",
      },
      commentItem: {
        message: "",
      },
    };
  },

  created() {
    this.myScore();
    this.commentScore();
  },

  computed: {
    filteredItems() {
      return _.orderBy(
        this.analysis.filter((item) => {
          return item.message.toLowerCase().includes(this.search.toLowerCase());
        }),
        "headline"
      );
    },
    filteredItemsComment() {
      return _.orderBy(
        this.comment.filter((item) => {
          return item.name
            .toLowerCase()
            .includes(this.searchComment.toLowerCase());
        }),
        "headline"
      );
    },
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
    fetchCase(item) {
      this.analysisItem.message = item.message;
      this.analysisItem.output = item.output;
      this.analysisItem.testcase_input = item.testcase_input;
      this.analysisItem.testcase_output = item.testcase_output;
    },
    fetchComment(item) {
      this.commentItem.message = item.comment;
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
      await axios
        .get("/api/score", {
          params: {
            course_id: this.$route.query.course_id,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.analysis = response.data.payload2;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.analysis = this.analysis.filter(
        (e) => e.submission_id == this.$route.query.submission_id
      );
      this.loading = false;
    },
  },
};
</script>