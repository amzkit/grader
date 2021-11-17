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
        <div v-for="(link, i) in links" :key="i">
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
        <span class="hidden-sm-and-down">{{ title }}</span>
      </v-toolbar-title>
      <v-spacer></v-spacer>

      <v-menu left bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-btn icon v-bind="attrs" v-on="on">
            <v-icon>mdi-dots-vertical</v-icon>
          </v-btn>
        </template>

        <v-list>
          <v-list-item link>
            <v-list-item-title @click="redirect('/logout')"
              >Logout
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      links: [
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
              text: "New Classroom",
              to: "/new-classroom",
            },
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
        {
          to: "/my-score",
          icon: "mdi-view-dashboard",
          text: "Myscore",
        },
        {
          to: "/scoreboard",
          icon: "mdi-view-dashboard",
          text: "Scoreboard",
        },
      ],
    };
  },
  async created() {
    await this.initialize();
  },
  computed: {
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
