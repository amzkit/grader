<template>
  <div>
    <input type="file" class="form-control" v-on:change="onFileChange" />
    <v-btn @click="formSubmit"> Save </v-btn>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      file: "",
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
      formData.append("import_file", this.file);
      // formData.append("course_name", "CS112");
      formData.append("start_date", "01/01/2020");
      formData.append("start_time", "01:00");
      formData.append("end_date", "02/01/2020");
      formData.append("end_time", "02:00");
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

