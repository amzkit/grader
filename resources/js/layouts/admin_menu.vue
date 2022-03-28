<template>
  <div>
    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list-item>
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/men/78.jpg"></v-img>
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title v-if="this.$store.state.data.user !== null">{{
            this.$store.state.data.user.name
          }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list dense>
        <div
          v-for="(link, i) in role === 'student'
            ? student
            : role === 'ta'
            ? ta
            : role === 'teacher'
            ? teacher
            : admin"
          :key="i"
        >
          <v-list-item
            v-if="!link.subLinks"
            :key="i"
            :to="link.to"
            avatar
            link
            class="v-list-item"
          >
            <v-list-item-icon>
              <v-icon>{{ link.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
              <v-list-item-title>{{ link.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-group v-else :key="link.text" no-action>
            <template v-slot:activator>
              <v-list-item-icon>
                <v-icon>{{ link.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-title>
                <v-list-item-content>
                  <v-list-item-title>{{ link.text }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item-title>
            </template>

            <v-list-item
              v-for="sublink in link.subLinks"
              :to="sublink.to"
              :key="sublink.text"
              link
            >
              <v-list dense>
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title v-text="sublink.text" />
                  </v-list-item-content>
                </v-list-item-title>
              </v-list>
            </v-list-item>
          </v-list-group>
        </div>
      </v-list>
      <template v-slot:append>
        <div class="pa-2">
          <v-btn color="error" block @click="redirect('/logout')">
            Logout
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="blue darken-3"
      dark
    >
      <v-app-bar-nav-icon
        @click.stop="$store.commit('top_bar/SET_DRAWER', !drawer)"
      ></v-app-bar-nav-icon>
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
        <button @click="redirect('/')" class="-hidden-sm-and-down">
          {{ title }}
        </button>
      </v-toolbar-title>
      <v-spacer></v-spacer>

      <v-menu
        v-model="menu"
        :close-on-content-click="false"
        :nudge-width="200"
        offset-x
      >
        <template v-slot:activator="{ on, attrs }">
          <v-btn color="indigo" dark v-bind="attrs" v-on="on"> Menu </v-btn>
        </template>

        <v-card>
          <v-list>
            <v-list-item>
              <v-list-item-avatar>
                <v-img
                  src="https://randomuser.me/api/portraits/men/78.jpg"
                ></v-img>
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title>{{
                  this.$store.state.data.user.name
                }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
          <v-divider></v-divider>
          <v-list>
            <v-list-group no-action>
              <template v-slot:activator>
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title>
                      {{
                        `ROLE (${$store.state.data.user.role.toUpperCase()})`
                      }}</v-list-item-title
                    >
                  </v-list-item-content>
                </v-list-item-title>
              </template>
              <v-list-item
                link
                v-if="this.$store.state.data.user.role_admin === 1"
                @click="changeRoleUser('admin')"
              >
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title>Admin </v-list-item-title>
                  </v-list-item-content>
                </v-list-item-title>
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
              <v-list-item
                link
                v-if="this.$store.state.data.user.role_ta === 1"
                @click="changeRoleUser('ta')"
              >
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title>Teacher Assistant </v-list-item-title>
                  </v-list-item-content>
                </v-list-item-title>
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
              <v-list-item
                link
                v-if="this.$store.state.data.user.role_student === 1"
                @click="changeRoleUser('student')"
              >
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title>Student </v-list-item-title>
                  </v-list-item-content>
                </v-list-item-title>
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
              <v-list-item
                link
                v-if="this.$store.state.data.user.role_teacher === 1"
                @click="changeRoleUser('teacher')"
              >
                <v-list-item-title>
                  <v-list-item-content>
                    <v-list-item-title>Teacher </v-list-item-title>
                  </v-list-item-content>
                </v-list-item-title>
                <v-list-item-icon>
                  <v-icon>mdi-view-dashboard</v-icon>
                </v-list-item-icon>
              </v-list-item>
            </v-list-group>
            <div class="pa-1">
              <v-btn color="primary" block @click="redirect('/changepassword')">
                Change Password
              </v-btn>
            </div>
            <div class="pa-1">
              <v-btn color="error" block @click="redirect('/logout')">
                Logout
              </v-btn>
            </div>
          </v-list>

          <v-card-actions>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      fav: true,
      menu: false,
      message: false,
      hints: true,
      admin: [
        {
          to: "/",
          icon: "mdi-home",
          text: "Home",
        },
        {
          icon: "mdi-pencil",
          text: "Mission",
          subLinks: [
            {
              to: "/mission",
              text: "Mission",
            },
            {
              text: "New Mission",
              to: "/new-mission",
            },
            {
              text: "Manage Mission",
              to: "/manage-mission",
            },
            {
              text: "Manage Mission Classroom",
              to: "/manage-mission-classroom",
            },
          ],
        },
        {
          icon: "mdi-pencil",
          text: "Classroom",
          subLinks: [
            {
              text: "Manage Classroom",
              to: "/manage-classroom",
            },
          ],
        },
        {
          to: "/my-score",
          icon: "mdi-view-dashboard",
          text: "My Score",
        },
        {
          to: "/scoreboard",
          icon: "mdi-view-dashboard",
          text: "Scoreboard",
        },
        {
          to: "/new-test",
          icon: "mdi-view-dashboard",
          text: "New Test",
        },
        {
          icon: "mdi-pencil",
          text: "User",
          subLinks: [
            {
              text: "New User",
              to: "/new-user",
            },
            {
              text: "Manage User",
              to: "/manage-user",
            },
          ],
        },
      ],
      student: [
        {
          to: "/",
          icon: "mdi-home",
          text: "Home",
        },
        {
          to: "/mission",
          icon: "mdi-view-dashboard",
          text: "Mission",
        },
        {
          icon: "mdi-pencil",
          text: "Manage",
          subLinks: [
            {
              text: "New Classroom",
              to: "/new-classroom",
            },
          ],
        },
      ],
      ta: [
        {
          to: "/",
          icon: "mdi-home",
          text: "Home",
        },
        {
          to: "/classroom",
          icon: "mdi-view-dashboard",
          text: "Classroom",
        },
        {
          icon: "mdi-pencil",
          text: "Manage",
          subLinks: [
            {
              text: "Manage Classroom",
              to: "/manage-classroom",
            },
            {
              text: "Manage Example",
              to: "/manage-example",
            },
          ],
        },
      ],
      teacher: [
        {
          to: "/",
          icon: "mdi-home",
          text: "Home",
        },
        {
          to: "/classroom",
          icon: "mdi-view-dashboard",
          text: "Classroom",
        },
        {
          icon: "mdi-pencil",
          text: "Manage",
          subLinks: [
            {
              text: "Manage Classroom",
              to: "/manage-classroom",
            },
            {
              text: "Manage Example",
              to: "/manage-example",
            },
          ],
        },
      ],
    };
  },
  async created() {
    await this.initialize();
  },
  computed: {
    role: {
      get() {
        return document.head.querySelector('meta[name="user-role"]').content;
      },
    },
    drawer: {
      get() {
        return this.$store.state.top_bar.drawer;
      },
      set(value) {
        this.loading = false;
        this.$store.commit("top_bar/SET_DRAWER", value);
      },
    },
    ...mapState({
      title: (state) => state.top_bar.title,
      items: (state) => state.navigation_drawer.items,
    }),
  },
  methods: {
    redirect(url) {
      window.location.href = url;
    },
    router(url) {
      this.$router.push(url);
    },
    async changeRoleUser(role) {
      console.log(role);
      await axios.put("api/user/" + this.$store.state.data.user.id, {
        role: role,
      });
      this.redirect("/");
    },
    async initialize() {
      await axios.get("/api/user").then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_USER", response.data.user);
        }
      });
    },
  },
};
</script>
