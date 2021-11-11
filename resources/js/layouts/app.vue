<template>
  <v-app id="app" data-app>
    <admin-menu></admin-menu>
    <v-main>
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
