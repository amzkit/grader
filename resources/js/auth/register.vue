<template>
  <v-app>
    <GuestTopBar></GuestTopBar>
    <v-main>
      <v-container class="pa-md-8 mx-auto" style="width: 100%" fluid>
        <v-layout align-center justify-center>
          <v-flex xs12 sm10 md6>
            <v-card class="elevation-12">
              <v-toolbar dark color="primary">
                <v-toolbar-title>Register</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text>
                <v-form ref="form" id="form" method="POST" action="/register">
                  <v-text-field
                    prepend-icon="person"
                    name="name"
                    label="Name"
                    type="text"
                    required
                  ></v-text-field>
                  <v-text-field
                    prepend-icon="person"
                    name="email"
                    label="Email"
                    type="email"
                    required
                  ></v-text-field>
                  <v-text-field
                    prepend-icon="person"
                    name="password"
                    label="Password"
                    type="password"
                    required
                  ></v-text-field>
                  <v-text-field
                    prepend-icon="person"
                    name="password_confirmation"
                    label="password"
                    type="password"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-show="false"
                    name="_token"
                    :value="csrf"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <div class="text-center">
                  <v-btn color="primary" type="submit" form="form"
                    >Register</v-btn
                  >
                </div>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>


<script>
import GuestTopBar from "../layouts/guest_top_bar.vue";

export default {
  components: { GuestTopBar },
  data: () => ({
    csrf: document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute("content"),
  }),
  props: {},
  methods: {},
};
</script>

<style>
</style>
