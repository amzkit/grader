<template>
  <v-main>
    <v-container class="pa-md-8 mx-auto" style="width: 100%" fluid>
      <v-layout align-center justify-center>
        <v-flex xs12 sm10 md6>
          <v-card class="elevation-12">
            <v-toolbar dark color="primary">
              <v-toolbar-title>Change Password</v-toolbar-title>
              <v-spacer></v-spacer>
            </v-toolbar>
            <v-card-text>
              <v-alert
                :value="alert"
                :color="colorAlert"
                dark
                border="top"
                transition="scale-transition"
              >
                {{ textAlert }}
              </v-alert>
              <v-text-field
                prepend-icon="lock"
                name="current-password"
                label="Current Password"
                type="password"
                persistent-placeholder
                v-model="currentPassword"
                required
              ></v-text-field>
              <v-text-field
                prepend-icon="lock"
                name="new-password"
                label="New Password"
                type="password"
                persistent-placeholder
                v-model="newPassword"
                required
              ></v-text-field>
              <v-text-field
                id="password"
                prepend-icon="lock"
                name="new-password_confirmation"
                label="New Password Confirmation"
                type="password"
                persistent-placeholder
                v-model="confirmationPassword"
                required
              ></v-text-field>
            </v-card-text>
            <v-layout justify-center>
              <v-card-actions class="mb-4">
                <div class="text-center">
                  <v-btn color="primary" @click="changePassword">SUBMIT</v-btn>
                </div>
              </v-card-actions>
            </v-layout>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-main>
</template>


<script>
export default {
  data: () => ({
    currentPassword: "",
    newPassword: "",
    confirmationPassword: "",
    alert: false,
    colorAlert: "",
    textAlert: "",
  }),
  methods: {
    redirect(url) {
      window.location.href = url;
    },
    async changePassword() {
      if (this.confirmationPassword === this.newPassword) {
        await axios
          .post("api/changePassword", {
            current_password: this.currentPassword,
            new_password: this.newPassword,
            id: this.$store.state.data.user.id,
          })
          .then((res) => {
            if (res.data.success) {
              this.alert = true;
              this.colorAlert = "primary";
              this.textAlert = res.data.success;
              this.redirect("/");
            } else {
              this.alert = true;
              this.colorAlert = "error";
              this.textAlert = res.data.error;
            }
            this.currentPassword = "";
            this.newPassword = "";
            this.confirmationPassword = "";
          });
      } else {
        this.alert = true;
        this.colorAlert = "error";
        this.textAlert = "Password and confirm password does not match";
      }
    },
  },
};
</script>
