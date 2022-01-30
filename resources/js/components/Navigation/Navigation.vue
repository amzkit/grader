<template>
  <div>
    <Loading :loading="this.loading" />
    <v-navigation-drawer absolute permanent left>
      <v-list dense nav>
        <v-list-item-group v-model="model" mandatory>
          <div
            v-if="
              this.$store.state.data.user.role === 'admin' ||
              this.$store.state.data.user.role === 'teacher'
            "
          >
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
          <div>
            <v-list-item
              v-for="item in this.$store.state.data.classrooms"
              :key="item.id"
              link
              @click="onClick(item)"
            >
              <v-list-item-content>
                <v-list-item-title>{{ item.course_name }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </div>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
  </div>
</template>

<script>
import Loading from "../Loading/Loading.vue";
export default {
  name: "Navigation",
  components: {
    Loading,
  },
  props: {
    onClick: Function,
  },
  data: function () {
    return {
      model: 0,
      loading: false,
    };
  },
  async created() {
    await this.check_user();
    await this.classroom();
    await this.onClick(
      this.$store.state.data.classrooms.length > 0
        ? this.$store.state.data.classrooms[0]
        : 0
    );
  },
  methods: {
    async check_user() {
      this.loading = true;
      await axios.get("/api/user").then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_USER", response.data.user);
        }
      });
      this.loading = false;
    },
    async classroom() {
      this.loading = true;
      await axios
        .get("api/classroom", {
          params: {
            studentid: this.$store.state.data.user.username,
          },
        })
        .then((response) => {
          if (response.data.success == true) {
            this.$store.commit("data/SET_CLASSROOMS", response.data.payload);
            console.log(response.data.payload);
          }
        });
      this.loading = false;
    },
  },
};
</script>