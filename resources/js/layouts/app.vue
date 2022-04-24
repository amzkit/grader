<template>
  <v-app id="app" data-app>
    <admin-menu></admin-menu>
    <v-main class="background">
      <v-container class="pa-md-8 mx-auto" style="width: 100%" fluid>
        <router-view></router-view>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import AdminMenu from "./admin_menu.vue";

export default {
  name: "app",
  components: { AdminMenu },
};
</script>

<style scoped>
.background {
  height: 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url("../../../storage/app/picture/background.jpeg");
}
</style>
