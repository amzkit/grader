<template>
  <v-row>
    <Loading :loading="this.$store.state.data.loading" />
    <v-col cols="2">
      <Navigation />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <h1>จัดการโจทย์</h1>
          <v-card>
            <v-container fluid>
              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> ห้องเรียน </v-subheader>
                </v-col>
                <v-col cols="6">
                  {{ this.$store.state.data.classroom.className }}
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> ชื่องาน </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="workName"
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
                    v-model="subjectName"
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
                    v-model="selectedLanguages"
                    :items="this.$store.state.data.language"
                    item-text="languagesName"
                    item-value="id"
                    single-line
                    auto
                    label="Languages"
                  ></v-select>
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="12">
                  <v-subheader> กำหนดส่ง </v-subheader>
                </v-col>
              </v-row>

              <v-row align="center">
                <v-col cols="5">
                  <v-date-picker v-model="stateDate"></v-date-picker>
                </v-col>
                <v-col cols="2">
                  <v-text>ถึง</v-text>
                </v-col>
                <v-col cols="5">
                  <v-date-picker v-model="endDate"></v-date-picker>
                </v-col>
              </v-row>
            </v-container>
          </v-card>
          <v-btn @click="formSubmit"> Save </v-btn>
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Navigation,
    Loading,
  },
  data: function () {
    return {
      file: "",
      workName: "",
      subjectName: "",
      subjectFile: null,
      score: 0,
      selectedLanguages: 0,
      stateDate: null,
      endDate: null,
    };
  },
  created() {
    this.initialize();
  },
  methods: {
    resetForm() {
      this.file = null;
      this.workName = "";
      this.subjectName = null;
      this.subjectFile = null;
      this.score = 0;
      this.selectedLanguages = 0;
      this.stateDate = null;
      this.endDate = null;
    },
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    async initialize() {
      await axios.get("api/languages").then((response) => {
        this.$store.commit("data/SET_LANGUAGE", response.data.payload);
      });
    },

    async formSubmit(e) {
      e.preventDefault();
      let currentObj = this;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("classroom_id", this.$store.state.data.classroom.id);
      formData.append("work_name", this.workName);
      formData.append("subject_name", this.subjectName);
      formData.append("file", this.file);
      formData.append("score", this.score);
      formData.append("language_id", this.selectedLanguages);
      formData.append("send_start_work", this.stateDate);
      formData.append("send_end_work", this.endDate);
      await axios
        .post("api/quiz", formData, config)
        .then(function (response) {
          currentObj.success = response.data.success;
        })
        .catch(function (error) {
          currentObj.output = error;
        });
      this.resetForm();
    },
  },
};
</script>

<style>
</style>