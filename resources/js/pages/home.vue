<template>
  <v-container fill-height fluid>
    <v-row justify="center">
      <Snackbar />
      <v-carousel
        cycle
        height="500"
        hide-delimiter-background
        show-arrows-on-hover
      >
        <v-carousel-item
          v-for="(item, i) in image"
          :key="i"
          :src="`${'http://127.0.0.1:8000'}/storage${item.picture.replace(
            'public',
            ''
          )}`"
          reverse-transition="fade-transition"
          transition="fade-transition"
        >
        </v-carousel-item>
      </v-carousel>
      <div class="mt-4">
        <h1 class="text-center , font-weight-black white--text">
          SOURCE CODE GRADER
        </h1>
        <h5 class="text-center white--text">
          Computer Science Maejo University
        </h5>
      </div>
      <v-row justify="center">
        <v-dialog v-model="dialog" persistent max-width="1000">
          <v-card>
            <v-stepper v-model="stepper" vertical>
              <v-stepper-step step="1" complete>
                Login Complete
              </v-stepper-step>

              <v-stepper-step step="2" :complete="stepper > 2">
                Change Password
              </v-stepper-step>

              <v-stepper-content step="2">
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
                <v-btn color="primary" @click="handleOnSave"> Save </v-btn>
                <v-btn text @click="handleOnSkip"> Skip </v-btn>
              </v-stepper-content>

              <template v-if="stepper == 3 && skip">
                <v-stepper-step :rules="[() => false]" step="3">
                  Warning
                  <small>
                    Please be careful If you don't change your password.</small
                  >
                </v-stepper-step>
              </template>

              <v-stepper-step step="3"> Finish </v-stepper-step>

              <v-stepper-content step="3">
                <v-layout justify-center>
                  <v-card-actions>
                    <div class="text-center">
                      <v-icon x-large color="green darken-2">
                        mdi-checkbox-marked-circle
                      </v-icon>
                    </div>
                  </v-card-actions>
                </v-layout>
                <v-btn color="primary" @click="handleOnFinish"> Finish</v-btn>
              </v-stepper-content>
            </v-stepper>
          </v-card>
        </v-dialog>
      </v-row>
    </v-row>
  </v-container>
</template>

<script>
import Snackbar from "../components/Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    Snackbar,
  },
  data: function () {
    return {
      image: [],
      currentPassword: "",
      newPassword: "",
      confirmationPassword: "",
      skip: false,
      stepper: 2,
      dialog: false,
      loading: true,
    };
  },
  mounted() {},
  async created() {
    await this.fetchAnnounce();
    this.firstLogin();
  },
  methods: {
    ...mapActions("snackbar", ["showSnack"]),
    snackBar(timeout = 3500, text = "Successfully", color = "success") {
      this.showSnack({
        text: text,
        color: color,
        timeout: timeout,
      });
    },
    async fetchAnnounce() {
      this.loading = true;
      await axios
        .get(`/api/picture`)
        .then((response) => {
          if (response.data.success === true) {
            this.image = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },
    redirect(url) {
      window.location.href = url;
    },
    async handleOnSave() {
      this.loading = true;
      if (
        this.confirmationPassword === this.newPassword &&
        this.newPassword != ""
      ) {
        await axios
          .post("api/changePassword", {
            current_password: this.currentPassword,
            new_password: this.newPassword,
          })
          .then(() => {
            this.stepper = 3;
          })
          .catch((error) => {
            this.snackBar(3500, error, "error");
          });
      } else {
        this.snackBar(
          3500,
          "Password and confirm password does not match",
          "error"
        );
      }
      this.loading = false;
    },
    handleOnFinish() {
      this.dialog = false;
    },
    async handleOnSkip() {
      this.skip = true;
      this.loading = true;
      await axios
        .post("api/changePassword", {
          skip: this.skip,
        })
        .then(() => {
          this.stepper = 3;
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },
    firstLogin() {
      let user_role = document.head.querySelector(
        'meta[name="user-role"]'
      ).content;
      let user_last_login = document.head.querySelector(
        'meta[name="user-last-login"]'
      ).content;
      if (user_role == "student" && user_last_login == "") {
        this.dialog = true;
      }
    },
  },
};
</script>
