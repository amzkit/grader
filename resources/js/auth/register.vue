<template>
  <v-app>
    <GuestTopBar></GuestTopBar>
    <v-main class="background">
      <v-container fill-height fluid>
        <v-row align="center" justify="center">
          <v-layout align-center justify-center>
            <v-flex xs12 sm10 md6>
              <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                  <v-toolbar-title>Register</v-toolbar-title>
                  <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                  <v-text-field
                    v-model="name"
                    name="name"
                    label="Name"
                    type="text"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-model="username"
                    label="Username"
                    type="text"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-model="email"
                    name="email"
                    label="Email"
                    type="email"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-model="password"
                    label="Password"
                    type="password"
                    required
                  ></v-text-field>
                  <v-text-field
                    v-model="password_confirmation"
                    label="Password Confirmation"
                    type="password"
                    required
                  ></v-text-field>
                </v-card-text>
                <v-layout justify-center>
                  <v-card-actions class="mb-4">
                    <div class="text-center">
                      <v-btn color="primary" @click="register">Register</v-btn>
                    </div>
                  </v-card-actions>
                </v-layout>
              </v-card>
            </v-flex>
          </v-layout>
        </v-row>
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
    name: "",
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
  }),
  methods: {
    redirect(url) {
      window.location.href = url;
    },
    async register() {
      if (this.password === this.password_confirmation) {
        await axios
          .post("/api/register", {
            name: this.name,
            username: this.username,
            email: this.email,
            password: this.password,
          })
          .then(() => {
            this.redirect("/login");
          });
      }
    },
  },
};
</script>

<style scoped>
.background {
  height: 100%;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url("../../../storage/app/picture/background.jpeg");
}
</style>
