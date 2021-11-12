<template>
  <div>
    <v-navigation-drawer v-model="drawer" absolute temporary>
      <v-list nav dense>
        <v-list-item-group
          v-model="group"
          active-class="deep-purple--text text--accent-4"
        >
          <template v-for="item in items">
            <v-list-item :key="item.text" link @click="redirect(item.url)">
              <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>

          <template v-for="item in sidebar">
            <v-list-item :key="item.id" link @click="fatchItem(item.id)">
              <v-list-item-action>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.className }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </template>
        </v-list-item-group>
      </v-list>
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

      <v-btn text to="/"> Home </v-btn>
      <v-btn text to="/classroom"> Classroom </v-btn>
      <div class="text-xs-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn text dark v-bind="attrs" v-on="on">
              Manage Classroom
              <v-icon>mdi-chevron-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item v-for="(manage, index) in manages" :key="index" link>
              <v-list-item-title @click="manage.fn">{{
                manage.title
              }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
      <v-btn text to="/myscore"> My Score </v-btn>
      <v-btn text to="/scoreboard"> Scoreboards </v-btn>
      <div class="text-xs-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark v-bind="attrs" v-on="on">
              Profile
              <v-icon>mdi-chevron-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item v-for="(user, index) in users" :key="index" link>
              <v-list-item-title @click="user.fn"
                >{{ user.title }}
              </v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      sidebar: [],
      manages: [
        {
          title: "จัดการห้องเรียน",
          fn: () => {
            this.$router.push("/manageclass");
          },
        },
        {
          title: "จัดการโจทย์",
          fn: () => {
            this.$router.push("/manageexample");
          },
        },
        {
          title: "เพิ่มห้องเรียน",
          fn: () => {
            this.$router.push("/newclassroom");
          },
        },
      ],
      users: [
        {
          title: "ชื่อ", // อันนี้จะดึงจากฐานข้อมูลเหมือนที่อาจารย์ดึง
          fn: () => {
            this.$user.name;
          },
        },
        {
          title: "Change Name",
          fn: () => {
            this.$router.push("");
          },
        },
        {
          title: "Change Password",
          fn: () => {
            this.$router.push("");
          },
        },
        {
          title: "Log Out",
          fn: () => {
            this.redirect("/logout");
          },
        },
      ],
    };
  },
  async created() {
    await this.initialize();
    await this.fatchItem(this.sidebar[0].id);
  },
  computed: {
    drawer: {
      get() {
        return this.$store.state.top_bar.drawer;
      },
      set(value) {
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

    async fatchItem(item) {
      await axios.get("api/manage-classroom/" + item).then((response) => {
        if (response.data) {
          this.$store.commit("data/SET_CLASSROOM", response.data);
        }
      });
      await axios.get("api/manage-std-classroom/" + item).then((response) => {
        if (response.data) {
          this.$store.commit("data/SET_STD_CLASSROOM", response.data);
        }
      });

      await axios.get("api/classroom/" + item).then((response) => {
        if (response.data) {
          this.$store.commit("data/SET_CLASSROOM_WORK", response.data);
        }
      });
    },

    async initialize() {
      await axios.get("api/manage-classroom").then((response) => {
        if (response.data) {
          this.sidebar = response.data;
        }
      });
    },
  },
};
</script>
