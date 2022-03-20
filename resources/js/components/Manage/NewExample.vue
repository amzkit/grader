<template>
  <v-row>
    <v-col cols="12">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <h1>จัดการโจทย์</h1>
          <v-card>
            <v-container fluid>
              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> ชื่องาน </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="title"
                    label="ใส่ชื่องาน"
                  ></v-text-field>
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> โจทย์ </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-textarea
                    counter
                    v-model="question"
                    label="พิมพ์โจทย์"
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
                  <v-subheader> คะแนน </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    v-model="score"
                    onfocus="this.select()"
                    label="กำหนดคะแนน"
                  ></v-text-field>
                </v-col>
                <v-col cols="4">
                  <v-subheader> ภาษา </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-select
                    v-model="language"
                    :items="this.$store.state.data.language"
                    item-text="languagesName"
                    item-value="id"
                    single-line
                    auto
                    label="Languages"
                  ></v-select>
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
      formData.append("language", "this.language");
      formData.append("file", this.file);
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