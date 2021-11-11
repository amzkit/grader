<template>
  <v-row justify="center">
    <h1>จัดการโจทย์</h1>
    <v-card>
      <v-container fluid>
        <v-row align="center">
          <!-- เลือก classroom -->
          <v-col cols="4">
            <v-subheader> ห้องเรียน </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="select"
              :items="items"
              :error-messages="selectErrors"
              label="เลือกห้องเรียน"
              required
              @change="$v.select.$touch()"
              @blur="$v.select.$touch()"
            ></v-select>
          </v-col>
        </v-row>

        <!-- ใส่ชื่องาน -->
        <v-row align="center">
          <v-col cols="4">
            <v-subheader> ชื่องาน </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-text-field label="ใส่ชื่องาน"></v-text-field>
          </v-col>
        </v-row>

        <!-- โจทย์ -->
        <v-row align="center">
          <v-col cols="4">
            <v-subheader> โจทย์ </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-textarea counter label="พิมพ์โจทย์"></v-textarea>
            <template>
              <v-file-input multiple label="แนบไฟล์โจทย์" dense></v-file-input>
            </template>
          </v-col>
        </v-row>

        <!-- คะแนน -->
        <v-row align="center">
          <v-col cols="4">
            <v-subheader> คะแนน </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-text-field label="กำหนดคะแนน"></v-text-field>
          </v-col>
          <!-- ภาษา -->
          <v-col cols="4">
            <v-subheader> ภาษา </v-subheader>
          </v-col>
          <v-col cols="6">
            <v-select
              v-model="selectleg"
              :items="itemsleg"
              :error-messages="selectErrors"
              label="เลือกภาษา"
              required
              @change="$v.selectLeg.$touch()"
              @blur="$v.selectleg.$touch()"
            ></v-select>
          </v-col>
        </v-row>

        <!-- กำหนดส่ง -->
        <v-row align="center">
          <v-col cols="5">
            <v-subheader> กำหนดส่ง </v-subheader>
            <v-date-picker v-model="picker"></v-date-picker>
          </v-col>
          <v-col cols="1">
            <v-text>ถึง</v-text>
          </v-col>
          <v-col cols="6">
            <v-date-picker v-model="picker"></v-date-picker>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      loading: true,
      user: null,

      // เลือก classroom
      select: null,
      items: ["cs000", "cs001", "cs002", "cs003"],

      // เลือก ภาษา
      selectLeg: null,
      itemsleg: ["C", "C++", "java", "phython"],

      //เลือกวันที่
      picker: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        .toISOString()
        .substr(0, 10),
    };
  },

  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.initialize();
  },
  methods: {
    async initialize() {
      this.loading = true;
      await axios.get("api/manage/classroom").then((response) => {
        if (response.data.success == true) {
          console.log(response.data);
          this.user = response.data.user;
          this.select = null;
        }
      });
      this.loading = false;
    },
  },
};
</script>

<style>
</style>