<template>
  <v-row>
    <Loading :loading="this.loading" />
    <Snackbar />
    <v-col cols="12">
      <v-row justify="center">
        <h1>New Classroom</h1>
        <v-card>
          <v-container fluid>
            <v-row align="center">
              <v-col cols="4">
                <v-subheader> ชื่อห้องเรียน </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="clasroom"
                  label="ชื่อห้องเรียน"
                ></v-text-field>
              </v-col>
            </v-row>

            <v-row align="center">
              <v-col cols="4">
                <v-subheader> ไฟล์รายชื่อนักศึกษา </v-subheader>
              </v-col>
              <v-col cols="6">
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
                <v-subheader> เทอมเริ่มต้น </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menu1"
                  :close-on-content-click="false"
                  max-width="290"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :value="computedDateFormattedStartDate"
                      clearable
                      label="เทอมเริ่มต้น"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      @click:clear="start_date = null"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="start_date"
                    @change="menu1 = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="4">
                <v-subheader> เทอมสิ้นสุด </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-menu
                  v-model="menu2"
                  :close-on-content-click="false"
                  max-width="290"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      :value="computedDateFormattedEndDate"
                      clearable
                      label="เทอมสิ้นสุด"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      @click:clear="end_date = null"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="end_date"
                    @change="menu2 = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
        <v-btn @click="formSubmit" color="primary"> Save </v-btn>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    Navigation,
    Loading,
    Snackbar,
  },
  data: function () {
    return {
      clasroom: "",
      file: null,
      start_date: null,
      end_date: null,
      menu1: false,
      menu2: false,
      loading: false,
    };
  },
  computed: {
    computedDateFormattedStartDate() {
      return this.start_date
        ? dayjs(this.start_date).format("dddd, MMMM D, YYYY")
        : "";
    },
    computedDateFormattedEndDate() {
      return this.end_date
        ? dayjs(this.end_date).format("dddd, MMMM D, YYYY")
        : "";
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
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    async formSubmit(e) {
      this.loading = true;
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();

      formData.append("check_file", this.file != null ? true : false);
      formData.append("import_file", this.file);
      formData.append("course_name", this.clasroom);
      formData.append("start_date", this.start_date);
      formData.append("end_date", this.end_date);

      await axios
        .post("/api/user/file/upload", formData, config)
        .then(function () {
          window.location.href = "/manage-classroom";
          this.loading = false;
        })
        .catch((response) => {
          this.snackBar(3500, response, "error");
        });
      this.loading = false;
    },
  },
};
</script>

<style>
</style>