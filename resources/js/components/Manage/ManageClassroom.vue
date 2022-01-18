<template>
  <v-row>
    <Loading :loading="this.loading" />
    <v-col cols="2">
      <Navigation />
    </v-col>
    <v-col cols="10">
      <div v-if="this.loading">
        <v-row justify="center">
          <v-col> </v-col>
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import DeleteDialog from "../Dialog/DeleteDialog.vue";
import Loading from "../Loading/Loading.vue";
import Navigation from "../Navigation/Navigation.vue";
export default {
  name: "ManageClassroom",
  components: { DeleteDialog, Loading, Navigation },
  data() {
    return {
      loading: false,
    };
  },
  methods: {},
};
</script>
