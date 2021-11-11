
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
          :items="this.$store.state.data.manageStdClassroom"
          :search="search"
          class="elevation-1"
        ></v-data-table>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: "ManageClassroom",
  data() {
    return {
      search: "",
      headers: [
        {
          text: "Student ID",
          align: "start",
          sortable: false,
          value: "std_id",
        },
        { text: "FirstName", value: "firstName" },
        { text: "LastName", value: "lastName" },
        { text: "Status", value: "statusName" },
        { text: "Action", value: "" },
      ],
    };
  },
};
</script>
