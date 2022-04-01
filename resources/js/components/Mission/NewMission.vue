<template>
  <v-row>
    <Snackbar :snackbar="snackbar" :text="text" />
    <Loading :loading="loading" />
    <v-col cols="12">
      <v-row justify="center">
        <h1>New Mission</h1>
        <v-card>
          <v-container fluid>
            <v-form ref="form" v-model="rules.valid" lazy-validation>
              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> Title </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="title"
                    :rules="[rules.required]"
                    label="Title"
                  ></v-text-field>
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
                    :rules="[rules.required]"
                    v-model="score"
                    onfocus="this.select()"
                    label="Score"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-subheader> Language </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-autocomplete
                    v-model="lang"
                    :items="languages"
                    :rules="[rules.required]"
                    label="Language"
                    item-text="lang"
                    item-value="id"
                  >
                  </v-autocomplete>
                </v-col>
              </v-row>
            </v-form>
          </v-container>
        </v-card>
        <v-btn @click="submit" color="primary"> Save </v-btn>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import Navigation from "../Navigation/Navigation.vue";
import { VueEditor } from "vue2-editor";
import Snackbar from "../Snackbar/Snackbar.vue";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Navigation,
    VueEditor,
    Snackbar,
    Loading,
  },
  data: function () {
    return {
      rules: {
        valid: true,
        required: (value) => !!value || "Required.",
      },
      snackbar: false,
      loading: false,
      text: "",
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
        [{ indent: "-1" }, { indent: "+1" }],
        [{ color: [] }, { background: [] }],
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

    async getLanguage() {
      this.loading = true;
      await axios
        .get("/api/language")
        .then((response) => {
          this.languages = response.data.payload;
        })
        .catch((error) => {
          this.snackbar = true;
          this.text = error;
        });
      this.loading = false;
    },

    async submit() {
      if (this.$refs.form.validate() == false) {
        this.$refs.form.validate();
      }
      this.loading = true;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("title", this.title);
      formData.append("question", this.question);
      formData.append("score", this.score);
      formData.append("language_id", this.lang);
      formData.append("file", this.file);
      formData.append("level", this.level);
      formData.append("tolerant", this.tolerant != "" ? this.tolerant : "$");
      if (this.title && this.question && this.lang && this.score) {
        await axios
          .post("/api/problem", formData, config)
          .then(function () {
            window.location.href = "/manage-mission";
          })
          .catch(function (error) {
            console.log(error);
          });
      }
      this.loading = false;
    },
  },
};
</script>

<style>
</style>