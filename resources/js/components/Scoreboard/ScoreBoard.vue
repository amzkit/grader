<template>
  <v-row justify="center">
    <h1>{{ this.$store.state.data.manageClassroom.className }}</h1>
    <v-col>
      <v-card>
        <v-card-title>
          Scoreboard
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="desserts"
          :search="search"
        ></v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      headers: [
        {
          text: "ชื่อ",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "งาน", value: "work" },
        { text: "คะแนนรวม", value: "score" },
      ],
      desserts: [
        {
          name: "แดง แจ๊ด",
          work: 159,
          score: 6.0,
        },
        {
          name: "เหลือง อ๋อย",
          work: 159,
          score: 6.0,
        },
      ],
    };
  },
};
</script>

<style>
</style>