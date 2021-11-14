<template>
  <v-dialog v-model="this.open" max-width="500px">
    <v-card>
      <v-card-title class="text-h5">{{ this.message }}</v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="this.open = false"
          >Cancel</v-btn
        >
        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DeleteDialog",
  props: ["message", "open", "onClose"],
  data: function () {
    return {};
  },
  watch: {
    open(val) {
      val || this.open();
    },
  },
  mounted() {},
  created() {},
  methods: {
    hadleClose() {
      onClose();
    },
  },
};
</script>
