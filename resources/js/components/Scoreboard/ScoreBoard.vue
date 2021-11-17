
<template>
  <v-row>
    <Loading :loading="this.$store.state.data.loading" />
    <v-col cols="2">
      <Navigation />
    </v-col>
    <v-col cols="10">
      <div v-if="!this.$store.state.data.loading">
        <v-row justify="center">
          <v-card>
            <v-card-title>
              {{ $store.state.data.classroom.className }}
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
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import Navigation from "../Navigation/Navigation.vue";
import Loading from "../Loading/Loading.vue";
export default {
  components: {
    Navigation,
    Loading,
  },
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