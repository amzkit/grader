<template>
  <v-row>
    <v-col cols="12">
      <div>
        <v-row justify="center">
          <h1>New User</h1>
          <v-card>
            <v-container fluid>
              <v-row align="center">
                <v-col cols="4">
                  <v-subheader> Status </v-subheader>
                </v-col>
                <v-col cols="6">
                  <v-radio-group v-model="status" row>
                    <v-radio label="Teacher" value="teacher"></v-radio>
                    <v-radio label="Student" value="student"></v-radio>
                  </v-radio-group>
                </v-col>
              </v-row>
              <div v-if="this.status === 'student'">
                <v-row align="center">
                  <v-col cols="4">
                    <v-subheader> ImportFile </v-subheader>
                  </v-col>
                  <v-col cols="6">
                    <div>
                      <input
                        type="file"
                        class="form-control"
                        v-on:change="onFileChange"
                      />
                    </div>
                  </v-col>
                </v-row>
              </div>
              <div v-else-if="this.status === 'teacher'">
                <v-row align="center">
                  <v-col cols="4">
                    <v-subheader> Username </v-subheader>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field
                      :rules="[rules.required]"
                      v-model="username"
                      label="Username"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row align="center">
                  <v-col cols="4">
                    <v-subheader> Name </v-subheader>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field
                      v-model="name"
                      :rules="[rules.required]"
                      label="Name"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row align="center">
                  <v-col cols="4">
                    <v-subheader> E-mail </v-subheader>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field
                      v-model="email"
                      :rules="[rules.required, rules.email]"
                      label="E-mail"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row align="center">
                  <v-col cols="4">
                    <v-subheader> Password </v-subheader>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field
                      v-model="password"
                      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                      :rules="[rules.required, rules.min]"
                      :type="showPassword ? 'text' : 'password'"
                      label="Password"
                      hint="At least 8 characters"
                      value="wqfasds"
                      class="input-group--focused"
                      @click:append="showPassword = !showPassword"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </div>
            </v-container>
          </v-card>
          <v-btn @click="formSubmit" color="primary"> Save </v-btn>
        </v-row>
      </div>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      file: "",
      status: "",
      username: "",
      name: "",
      email: "",
      password: "",
      showPassword: false,
      rules: {
        required: (value) => !!value || "Required.",
        min: (v) => v.length >= 8 || "Min 8 characters.",
        email: (v) => /.+@.+/.test(v) || "Invalid e-mail.",
      },
    };
  },
  methods: {
    onFileChange(e) {
      this.file = e.target.files[0];
    },
    async formSubmit(e) {
      e.preventDefault();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();

      if (this.status === "teacher") {
        formData.append("username", this.username);
        formData.append("status", this.status);
        formData.append("name", this.name);
        formData.append("email", this.email);
        formData.append("password", this.password);
      } else {
        formData.append("import_file", this.file);
      }
      await axios
        .post("/api/user/file/upload", formData, config)
        .then(function (response) {
          console.log("Uploaded", response);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
  },
};
</script>

