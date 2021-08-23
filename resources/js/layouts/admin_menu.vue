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
        <span class="hidden-sm-and-down">{{ Grader }}</span>
      </v-toolbar-title>

      <!-- menu topbars -->
      <v-btn text> Home </v-btn>
      <v-btn text> Classroom </v-btn>
      <div class="text-xs-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn text dark v-on="on"> Manage Classroom </v-btn>
          </template>
          <v-list>
            <v-list-tile v-for="(item, index) in items" :key="index" @click="y">
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile>
          </v-list>
        </v-menu>
      </div>
      <v-btn text> My Score </v-btn>
      <v-btn text> Scoreboards </v-btn>

      <!-- menu profile-->
      <div class="text-xs-center">
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn color="primary" dark v-on="on"> Username </v-btn>
          </template>
          <v-list>
            <v-list-tile v-for="(item, index) in items" :key="index" @click="y">
              <v-list-tile-title>{{ item.title }}</v-list-tile-title>
            </v-list-tile>
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
    return {};
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
