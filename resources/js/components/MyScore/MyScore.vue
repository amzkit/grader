
<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-card>
        <v-data-table
          :headers="headers"
          :items="myscore"
          :items-per-page="5"
          class="elevation-1"
        ></v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      headers: [
        {
          text: "วิชา",
          align: "start",
          sortable: false,
          value: "subject",
        },
        { text: "รายชื่องาน", value: "work" },
        { text: "คะแนน", value: "score" },
        { text: "วันที่ส่ง", value: "datesent" },
      ],
      myscore: [
        {
          subject: "CS000",
          work: 1,
          score: 6.0,
          datesent: 24,
        },
        {
          subject: "CS001",
          work: 1,
          score: 6.0,
          datesent: 24,
        },
      ],
    };
  },
};
</script>

<style>
</style>