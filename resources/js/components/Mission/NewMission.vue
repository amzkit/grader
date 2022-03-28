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
                  <VueEditor
                    v-model="question"
                    :editorToolbar="customToolbar"
                  />
                  <template>
                    <input
                      type="file"
                      class="form-control mt-3"
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
                  <v-subheader> Tolerant </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    prefix="$"
                    v-model="tolerant"
                    onfocus="this.select()"
                    label="Tolerant"
                  ></v-text-field>
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
                  <v-combobox
                    v-model="lang"
                    :items="languages"
                    label="Language"
                    item-text="lang"
                    item-value="id"
                  ></v-combobox>
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
import { VueEditor } from "vue2-editor";
export default {
  components: {
    Navigation,
    VueEditor,
  },
  data: function () {
    return {
      title: "",
      question: "",
      score: 0,
      lang: "",
      file: "",
      level: 0.0,
      tolerant: "",
      languages: [],
      customToolbar: [
        [{ header: [false, 1, 2, 3, 4, 5, 6] }],
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        [
          { align: "" },
          { align: "center" },
          { align: "right" },
          { align: "justify" },
        ],
        ["blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
        [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
        [{ color: [] }, { background: [] }], // dropdown with defaults from theme
      ],
    };
  },
  created() {
    this.getLanguage();
  },
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    resetForm() {
      this.title = null;
      this.question = "";
      this.score = null;
      this.lang = null;
      this.file = "";
      this.level = 0;
      this.tolerant = "";
    },
    async getLanguage() {
      let language = [];
      await axios
        .get("/api/language")
        .then(function (response) {
          language = response.data.payload;
        })
        .catch(function (error) {
          console.log(error);
        });
      this.languages = language;
    },
    async formSubmit(e) {
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      console.log(this.lang);
      formData.append("title", this.title);
      formData.append("question", this.question);
      formData.append("score", this.score);
      formData.append("language_id", this.lang.id);
      formData.append("file", this.file);
      formData.append("level", this.level);
      formData.append("tolerant", this.tolerant != "" ? this.tolerant : "$");
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