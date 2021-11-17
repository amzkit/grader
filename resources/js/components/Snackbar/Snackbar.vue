
<template>
  <div class="text-center">
    <v-snackbar v-model="this.snackbar" :timeout="timeout">
      {{ this.text }}
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "Snackbar",
  props: ["snackbar", "text"],
  data: () => ({
    timeout: 2000,
  }),
};
</script>