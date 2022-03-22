<template>
  <v-row>
    <v-col cols="12">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <h1>New Mission</h1>
          <v-card>
            <v-container fluid>
              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> Title </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field v-model="title" label="Title"></v-text-field>
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> Question </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-textarea
                    counter
                    v-model="question"
                    label="Question"
                  ></v-textarea>
                  <template>
                    <input
                      type="file"
                      class="form-control"
                      v-on:change="onFileChange"
                    />
                  </template>
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> Level </v-subheader>
                </v-col>
                <v-col cols="6">
                  <div class="text-center">
                    <v-rating
                      v-model="level"
                      color="yellow darken-3"
                      background-color="grey darken-1"
                      empty-icon="$ratingFull"
                      half-increments
                      hover
                      large
                    ></v-rating>
                    <div>
                      <span class="text-caption text-uppercase">level:</span>
                      <span class="font-weight-bold">
                        {{ level }}
                      </span>
                    </div>
                  </div>
                </v-col>
                <v-col cols="4">
                  <v-subheader> Score </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    v-model="score"
                    onfocus="this.select()"
                    label="Score"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-subheader> Language </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="language"
                    label="Language"
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
          <v-btn @click="formSubmit" color="primary"> Save </v-btn>
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import Navigation from "../Navigation/Navigation.vue";
export default {
  components: {
    Navigation,
  },
  data: function () {
    return {
      title: "",
      question: "",
      score: "",
      language: "",
      file: "",
      level: 0,
    };
  },
  created() {},
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    resetForm() {
      this.title = null;
      this.question = "";
      this.score = null;
      this.language = null;
      this.file = 0;
      this.level = 0;
    },
    async formSubmit(e) {
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("title", this.title);
      formData.append("question", this.question);
      formData.append("score", this.score);
      formData.append("language", this.language);
      formData.append("file", this.file);
      formData.append("level", this.level);
      await axios
        .post("/api/problem", formData, config)
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        });
      this.resetForm();
    },
  },
};
</script>

<style>
</style>