
<template>
  <div class="text-center">
    <v-snackbar v-model="open" :timeout="timeout">
      {{ this.text }}
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "Snackbar",
  props: ["snackbar", "text"],
  computed: {
    open: {
      get() {
        return this.snackbar;
      },
      set() {
        open;
      },
    },
  },
  data: () => ({
    timeout: 4000,
  }),
};
</script>