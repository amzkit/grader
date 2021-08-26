<template>
  <div>
    <v-navigation-drawer
      v-model="drawer"
      :clipped="$vuetify.breakpoint.lgAndUp"
      app
    >
      <v-list dense>
        <template v-for="item in items">
          <v-row v-if="item.heading" :key="item.heading" align="center">
            <v-col cols="6">
              <v-subheader v-if="item.heading">
                {{ item.heading }}
              </v-subheader>
            </v-col>
            <v-col cols="6" class="text-center">
              <a href="#!" class="body-2 black--text">EDIT</a>
            </v-col>
          </v-row>
          <v-list-group
            v-else-if="item.children"
            :key="item.text"
            v-model="item.model"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title>
                  {{ item.text }}
                </v-list-item-title>
              </v-list-item-content>
            </template>
            <v-list-item v-for="(child, i) in item.children" :key="i" link>
              <v-list-item-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>
                  {{ child.text }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-group>

          <v-list-item v-else :key="item.text" link @click="redirect(item.url)">
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
        <span class="hidden-sm-and-down">{{ Title }}</span>
      </v-toolbar-title>

      <!-- menu topbars -->
      <v-btn text to="/"> Home </v-btn>
      <v-btn text to="/classroom"> Classroom </v-btn>
      <div class="text-xs-center">
        <v-menu offset-y :rounded="rounded">
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

      <!-- menu profile-->
      <div class="text-xs-center">
        <v-menu offset-y :rounded="rounded">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark v-bind="attrs" v-on="on">
              Profile
              <v-icon>mdi-chevron-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item v-for="(user, index) in users" :key="index" link>
              <v-list-item-title @click="user.fn">{{
                user.title
              }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-app-bar>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  data() {
    return {
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
            this.$router.push("/managepropos");
          },
        },
      ],
      users: [
        {
          title: "ชื่อ", // อันนี้จะดึงจากฐานข้อมูลเหมือนที่อาจารย์ดึง
          fn: () => {
            this.$user.name
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
            this.$router.push("");
          },
        },
      ],
    };
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
  },
};
</script>
