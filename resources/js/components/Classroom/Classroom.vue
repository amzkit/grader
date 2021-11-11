<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-card>
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
        }
      ],
    };
  },
  mounted() {},
  created() {},
  methods: {},
};
</script>

<style>
</style>
