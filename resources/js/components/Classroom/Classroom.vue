<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-card>
      </v-card>
      <v-data-table
        :headers="headers"
        :items="this.$store.state.data.manageClassroomWork"
        class="elevation-1"
      >
        <template v-slot:[`item.subject_file_path`]="{ item }">
          <v-icon small class="mr-2" @click="download(item)">
            mdi-file-download
          </v-icon>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>


<script>
export default {
  data: function () {
    return {
      loading: true,
      user: null,
      headers: [
        {
          text: "ชื่องาน",
          align: "start",
          sortable: true,
          value: "work_name",
        },
        { text: "ชื่อเรื่อง", value: "subject_name" },
        { text: "ภาษา", value: "languagesName" },
        { text: "คะแนน", value: "score" },
        { text: "ไฟล์", value: "subject_file_path" },
        { text: "วันที่ส่ง", value: "send_start_work" },
        { text: "ครบกำหนดส่ง", value: "send_end_work" },
      ],
    };
  },
  mounted() {},
  created() {},
  methods: {
    async download(item) {
      window.location.href =
        "api/quiz" + item.subject_file_path.replace("public", "");
    },
  },
};
</script>

<style>
</style>
