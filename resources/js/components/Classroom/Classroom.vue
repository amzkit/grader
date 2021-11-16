<template>
  <v-row>
    <Loading :loading="this.$store.state.data.loading" />
    <v-col cols="2">
      <Navigation />
    </v-col>
    <v-col cols="10">
      <v-row justify="center">
        <h1>
          {{
            this.$store.state.data.loading
              ? "Loading..."
              : this.$store.state.data.classroom.className
          }}
        </h1>
        <v-col>
          <v-data-table
            :headers="headers"
            :items="this.$store.state.data.manageClassroomWork"
            class="elevation-1"
            @click:row="rowClick"
          >
            <template slot="items" slot-scope="props">
              <tr @click="rowClicked(props.item)"></tr>
            </template>
            <template v-slot:[`item.subject_file_path`]="{ item }">
              <v-icon small class="mr-2" @click="download(item)">
                mdi-file-download
              </v-icon>
            </template>
            <template v-slot:[`item.send_start_work`]="{ item }">
              {{ dayjs(item.send_start_work).format("MMMM D, YYYY") }}
            </template>
            <template v-slot:[`item.send_end_work`]="{ item }">
              {{ dayjs(item.send_end_work).format("MMMM D, YYYY") }}
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>


<script>
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
import Code from "../Classroom/Code.vue";
export default {
  components: {
    Navigation,
    Loading,
    Code,
  },
  data: function () {
    return {
      item: null,
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
        {
          text: "วันที่ส่ง",
          value: "send_start_work",
        },
        {
          text: "ครบกำหนดส่ง",
          value: "send_end_work",
        },
      ],
    };
  },
  mounted() {},
  created() {},
  methods: {
    dayjs,
    download(item) {
      window.location.href = `api/quiz/${item.subject_file_path.replace(
        "public",
        ""
      )}`;
    },
    rowClick(item) {
      this.$store.commit("data/SET_CLASSROOM_EXAM", item);
      this.$router.replace({
        name: "classroom-code",
        params: { id: item.id },
      });
    },
  },
};
</script>

<style>
</style>
