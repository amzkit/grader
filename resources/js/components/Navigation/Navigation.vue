<template>
  <div>
    <v-navigation-drawer absolute permanent left>
      <v-list dense nav>
        <v-list-item-group active-class="deep-purple--text text--accent-4">
          <v-list-item
            v-for="item in this.$store.state.data.classrooms"
            :key="item.id"
            link
            @click="fatchItem(item.id)"
          >
            <v-list-item-content>
              <v-list-item-title>{{ item.className }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  name: "Navigation",
  data: function () {
    return {};
  },
  async created() {
    await this.initialize();
  },
  methods: {
    loading(setLoading) {
      this.$store.commit("data/SET_LOADING", setLoading);
    },
    async initialize() {
      this.loading(true);
      await axios.get("api/manage-classroom").then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_CLASSROOMS", response.data.payload);
          this.loading(false);
        }
      });
    },
    async fatchItem(item) {
      this.loading(true);
      await axios.get(`api/manage-classroom/${item}`).then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_CLASSROOM_ID", response.data.payload);
        }
      });
      await axios.get(`api/manage-std-classroom/${item}`).then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_STD_CLASSROOM", response.data.payload);
        }
      });
      await axios.get(`api/classroom/${item}`).then((response) => {
        if (response.data.success == true) {
          console.log(response.data.payload);

          this.$store.commit("data/SET_CLASSROOM_WORK", response.data.payload);
        }
      });
      this.loading(false);
    },
  },
};
</script>