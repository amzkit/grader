<template>
  <v-row>
    <v-col cols="12">
      <v-row justify="center">
        <h1>Add Test Case</h1>
        <v-card>
          <v-container fluid>
            <v-row align="center">
              <v-col cols="4">
                <v-subheader> Problem Name </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-autocomplete
                  label="Problem Name"
                  v-model="missionId"
                  :items="problems"
                  item-text="title"
                  item-value="id"
                  hide-no-data
                  hide-selected
                >
                </v-autocomplete>
              </v-col>
            </v-row>

            <v-row align="center">
              <v-col cols="4">
                <v-subheader> Input </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-textarea counter label="Input" v-model="input"></v-textarea>
              </v-col>
            </v-row>

            <v-row align="center">
              <v-col cols="4">
                <v-subheader> Output </v-subheader>
              </v-col>
              <v-col cols="6">
                <v-textarea
                  counter
                  label="Output"
                  v-model="output"
                ></v-textarea>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
        <v-btn @click="addTestCase" color="primary"> Save </v-btn>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      problems: [],
      output: "",
      input: "",
      missionId: 0,
    };
  },
  mounted() {
    this.fetchItemProblem();
  },
  created() {},

  methods: {
    async fetchItemProblem() {
      this.loading = true;
      await axios.get("/api/problem").then((response) => {
        if (response.data.success == true) {
          this.problems = response.data.payload;
        }
      });
      this.loading = false;
    },
    async addTestCase() {
      this.loading = true;
      await axios
        .post("/api/test-case", {
          missionId: this.missionId,
          input: this.input,
          output: this.output,
        })
        .then((response) => {
          this.resetFrom();
          console.log(response.data.payload);
        });

      this.loading = false;
    },

    resetFrom() {
      this.output = "";
      this.input = "";
    },
  },
};
</script>