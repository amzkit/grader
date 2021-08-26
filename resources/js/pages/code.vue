<template>
<v-row justify="center">
  <h1>Coding</h1>
  <v-card-text>
    
  </v-card-text>
</v-row>
</template>

<script>
export default {
  data: function () {
    return {
      loading: true,
      user: null,
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