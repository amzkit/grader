<template>
  <div>
    <v-navigation-drawer absolute permanent left>
      <v-list dense nav>
        <v-list-item-group v-model="model" mandatory>
          <div v-if="this.$store.state.data.classrooms[0]">
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
          </div>
          <div v-else>
            <v-list-item-content>
              <v-list-item-title>
                <v-btn
                  color="primary"
                  block
                  @click="$router.push('/new-classroom')"
                >
                  New Classroom
                </v-btn>
              </v-list-item-title>
            </v-list-item-content>
          </div>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
export default {
  name: "Navigation",
  data: function () {
    return {
      model: 0,
    };
  },
  async created() {
    await this.initialize();
    await this.fatchItem(this.$store.state.data.classrooms[0].id);
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
          this.$store.commit("data/SET_CLASSROOM_WORK", response.data.payload);
        }
      });
      this.loading(false);
    },
  },
};
</script>