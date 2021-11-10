<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-card>
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
            เพิ่มผู้ใช้ห้องเรียน
          </v-btn>
        </template>

        <v-data-table
          :headers="headers"
          :items="desserts"
          :items-per-page="5"
          class="elevation-1"
        ></v-data-table>
      </v-card>
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
          sortable: false,
          value: "name",
        },
        { text: "ครบกำหนดส่ง", value: "dateline" },
        { text: "วันที่ส่ง", value: "finish" },
        { text: "สถานะ", value: "status" },
      ],
      desserts: [
        {
          name: "งานที่ 1",
          dateline: "1/1/2222 - 00.00 am",
          finish: "2/1/2222 - 10.21 am",
          status: "ส่งแล้ว(เลยกำหนด)",
        },
        {
          name: "งานที่ 2",
          dateline: "10/1/2222",
          finish: "",
          status: "ยังไม่ส่ง",
        },
        {
          name: "งานที่ 3",
          dateline: "262",
          finish: "",
          status: "16.0",
        },
        {
          name: "งานที่ 4",
          dateline: "305",
          finish: "",
          status: "3.7",
        },
        {
          name: "งานที่ 5",
          dateline: "356",
          finish: "",
          status: "16.0",
        },
        {
          name: "งานที่ 6",
          dateline: "375",
          finish: "",
          status: "0.0",
        },
        {
          name: "งานที่ 7",
          dateline: "392",
          finish: "",
          status: "0.2",
        },
        {
          name: "งานที่ 8",
          dateline: "408",
          finish: "",
          status: "3.2",
        },
        {
          name: "งานที่ 9",
          dateline: "452",
          finish: "",
          status: "25.0",
        },
        {
          name: "งานที่ 10",
          dateline: "518",
          finish: "",
          status: "26.0",
        },
      ],
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
      await axios.get("/api/user").then((response) => {
        if (response.data.success == true) {
          console.log(response.data);
          this.user = response.data.user;
        }
      });
      this.loading = false;
    },
  },
};
</script>

<style>
</style>