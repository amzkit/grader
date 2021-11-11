<template>
  <v-row justify="center">
    <h1>จัดการโจทย์</h1>
    <v-card>
      <v-container fluid>
        <v-row align="center">
          <v-col cols="4">
            <v-subheader> ห้องเรียน </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="selectedClassroom"
              :items="this.$store.state.data.manageClassroom"
              item-text="className"
              item-value="id"
              single-line
              auto
              label="Classroom"
            ></v-select>
          </v-col>
        </v-row>

        <v-row align="center">
          <v-col cols="4">
            <v-subheader> ชื่องาน </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-text-field v-model="workName" label="ใส่ชื่องาน"></v-text-field>
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
              <v-file-input
                show-size
                counter
                chips
                multiple
                label="Arquivo Geral"
                ref="myfile"
                @change="submitFiles"
                v-model="subjectFile"
              ></v-file-input>
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
          <v-col cols="5">
            <v-subheader> กำหนดส่ง </v-subheader>
            <v-date-picker v-model="stateDate"></v-date-picker>
          </v-col>
          <v-col cols="1">
            <v-text>ถึง</v-text>
          </v-col>
          <v-col cols="6">
            <v-date-picker v-model="endDate"></v-date-picker>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
    <v-btn @click="save"> Save </v-btn>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      selectedClassroom: 0,
      workName: "",
      subjectName: "",
      subjectFile: null,
      score: 0,
      selectedLanguages: 0,
      stateDate: null,
      endDate: null,
    };
  },

  mounted() {},
  created() {
    this.initialize();
  },
  methods: {
    async submitFiles() {
      let formData = new FormData();
      if (this.subjectFile) {
        for (let file in this.subjectFile) {
          formData.append("cave", file);
        }
        console.log(this.subjectFile);
      } else {
        console.log("there are no files.");
      }
    },
    async initialize() {
      await axios.get("api/languages").then((response) => {
        this.$store.commit("data/SET_LANGUAGE", response.data);
      });
    },

    async save() {
      console.log(this.subjectFile[0]);
      await axios.post("api/quiz", {
        classroom_id: this.selectedClassroom,
        work_name: this.workName,
        subject_name: this.subjectName,
        subject_file_path: this.subjectFile[0],
        score: this.score,
        language_id: this.selectedClassroom,
        send_start_work: this.stateDate,
        send_end_work: this.endDate,
      });
    },
  },
};
</script>

<style>
</style>