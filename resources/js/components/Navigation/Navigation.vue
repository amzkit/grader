<template>
  <div>
    <Loading :loading="this.loading" />
    <v-navigation-drawer absolute permanent left>
      <v-list dense nav>
        <v-list-item-group v-model="model" mandatory>
          <div
            v-if="
              (this.$store.state.data.user.role === 'admin' ||
                this.$store.state.data.user.role === 'teacher') &&
              $route.fullPath === '/manage-classroom'
            "
          >
            <v-list-item-content>
              <v-list-item-title>
                <v-dialog v-model="dialog" persistent max-width="600px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-btn color="primary" block dark v-bind="attrs" v-on="on">
                      New Classroom
                    </v-btn>
                  </template>
                  <v-card>
                    <v-card-title>
                      <span class="text-h5">New Classroom</span>
                    </v-card-title>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="12" md="12">
                            <v-text-field
                              label="Room Name"
                              required
                              v-model="course_name"
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <div
                          v-if="this.$store.state.data.user.role === 'admin'"
                        >
                          <div
                            class="text-no-wrap red lighten-1"
                            style="width: 14rem"
                          >
                            Only teachers can add classrooms.
                          </div>
                        </div>
                      </v-container>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="dialog = false">
                        Close
                      </v-btn>
                      <v-btn color="blue darken-1" text @click="new_course()">
                        Save
                      </v-btn>
                    </v-card-actions>
                  </v-card>
                </v-dialog>
              </v-list-item-title>
            </v-list-item-content>
          </div>
          <div>
            <div>
              <v-list-item
                v-for="item in this.$store.state.data.courses"
                :key="item.id"
                link
                @click="onClick(item)"
              >
                <v-list-item-content>
                  <v-list-item-title>{{ item.course_name }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </div>
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
      dialog: false,
      course_name: "",
    };
  },
  async created() {
    await this.check_user();
    await this.classroom();
    await this.onClick(
      this.$store.state.data.courses.length > 0
        ? this.$store.state.data.courses[0]
        : 0
    );
    console.log(this.$store.state.data.courses);
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
      await axios.get("api/classroom").then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_COURSES", response.data.payload);
        }
      });
      this.loading = false;
    },
    async new_course() {
      this.loading = true;
      await axios
        .post("api/course", {
          course_name: this.course_name,
        })
        .then((response) => {
          if (response.data.success == true) {
            this.$store.state.data.courses.push({
              courseId: response.data.payload.id,
              course_name: response.data.payload.course_name,
            });
          }
        });
      this.loading = false;
      this.dialog = false;
    },
  },
};
</script>