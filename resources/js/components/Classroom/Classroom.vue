<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation :onClick="fatchItemSchedule" />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-col>
            <v-data-table
              :headers="headers"
              :items="this.$store.state.data.schedule_all"
              class="elevation-1"
            >
              <!-- @click:row="rowClick"  -->
              <!-- <template slot="items" slot-scope="props">
                <tr @click="rowClicked(props.item)"></tr>
              </template> -->
              <template v-slot:[`item.file`]="{ item }">
                <div v-if="item.file">
                  <v-icon small class="mr-2" @click="download(item)">
                    mdi-file-download
                  </v-icon>
                </div>
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
      </div>
    </v-col>
  </v-row>
</template>


<script>
import dayjs from "dayjs";
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Navigation,
    Loading,
  },
  data: function () {
    return {
      loading: false,
      headers: [
        {
          text: "ชื่องาน",
          align: "start",
          sortable: true,
          value: "title",
        },
        { text: "ชื่อเรื่อง", value: "question" },
        { text: "ภาษา", value: "language" },
        { text: "คะแนน", value: "score" },
        { text: "ไฟล์", value: "file" },
        {
          text: "วันที่ส่ง",
          value: "start_date",
        },
        {
          text: "ครบกำหนดส่ง",
          value: "end_date",
        },
      ],
    };
  },
  async created() {
    // await this.check_user();
  },
  methods: {
    dayjs,
    download(item) {
      window.location.href = `api/schedule/download${item.file.replace(
        "problem_file",
        ""
      )}`;
    },
    async fatchItemSchedule(item) {
      this.loading = true;
      if (item) {
        await axios
          .get("/api/schedule", {
            params: {
              course_id: item.courseId,
            },
          })
          .then((response) => {
            if (response.data.success == true) {
              this.$store.commit(
                "data/SET_SCHEDULES_ALL",
                response.data.payload
              );
            }
          });
      }
      this.loading = false;
    },
  },
};
</script>

