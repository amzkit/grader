<template>
  <v-container>
    <Loading :loading="this.loading" />
    <v-row>
      <v-row>
        <v-col cols="12">
          <p class="text-h5 mb-1">{{ item.title }}</p>
        </v-col>
        <v-col cols="12">
          <p class="text-h6">
            {{ convertToPlain(item.question) }}
          </p>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          <VueEditor
            v-model="item.code"
            :editorToolbar="customToolbar"
            disabled
          />
        </v-col>
        <v-col cols="6">
          <VueEditor
            v-model="item.output"
            :editorToolbar="customToolbar"
            disabled
          />
        </v-col>
      </v-row>
      <v-row align="center">
        <v-col cols="12">
          <v-textarea
            clearable
            outlined
            v-model="comment"
            clear-icon="mdi-close-circle"
            placeholder="Comment"
            rows="2"
            row-height="15"
          ></v-textarea>
          <v-btn block color="primary" @click="insertComment"> Comment </v-btn>
        </v-col>
      </v-row>
    </v-row>
  </v-container>
</template>

<script>
import Loading from "../Loading/Loading.vue";
import { VueEditor } from "vue2-editor";
export default {
  components: {
    Loading,
    VueEditor,
  },
  name: "Comment",
  data: function () {
    return {
      item: [],
      comment: "",
      loading: false,
      customToolbar: [{}],
    };
  },
  mounted() {},
  created() {
    this.getScoreboard();
  },
  methods: {
    convertToPlain(html) {
      var tempDivElement = document.createElement("div");
      tempDivElement.innerHTML = html;
      return tempDivElement.textContent || tempDivElement.innerText || "";
    },
    async getScoreboard() {
      this.loading = true;
      await axios
        .get(`/api/submission/${this.$route.query.submission_id}`)
        .then((response) => {
          if (response.data.success == true) {
            this.item = response.data.payload;
          }
        });
      this.loading = false;
    },
    async insertComment() {
      this.loading = true;
      await axios.post("/api/comment", {
        submission_id: this.item.id,
        comment: this.comment,
        user_id: this.item.user_id,
      });
      this.loading = false;
    },
  },
};
</script>