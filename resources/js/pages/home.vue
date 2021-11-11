<template>
  <v-row justify="center">
    <!--Slide Photo -->
    <v-carousel
      cycle
      height="400"
      hide-delimiter-background
      show-arrows-on-hover
    >
      <v-carousel-item
        v-for="(item, i) in items"
        :key="i"
        :src="item.src"
        reverse-transition="fade-transition"
        transition="fade-transition"
      ></v-carousel-item>
    </v-carousel>

    <h1 class="text-center , font-weight-black">SOURCE CODE GRADER</h1>
    <h5 class="text-center">Computer Science Maejo University</h5>

    <!-- Home ที่อาจารย์ทำไว้อย่าพึ่งลบนะ -->
    <v-col>
      <v-card :loading="loading">
        <v-card-title>
          <span class="">Home -</span>
        </v-card-title>

        <v-card-text>
          I'm a home component from <br />
          /resources/js/pages/home.vue

          <v-card v-if="this.$store.state.data.user" class="mt-3">
            <v-card-title>Current User</v-card-title>
            <v-card-subtitle
              >Data retrived from api "/api/user"</v-card-subtitle
            >
            <v-card-text>
              Name : {{ this.$store.state.data.user.name }} <br />
              Email : {{ this.$store.state.data.user.email }} <br />
            </v-card-text>
          </v-card>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      loading: true,
      //   เพิ่มรูปตรง slid
      items: [
        {
          src: "https://lh3.googleusercontent.com/proxy/Ml3Qrf_Gzvmud_7CXhsCXBRwc8S460ibO0l-k20CK22C05a_BGLowMXXSI7qfi89cMvVT4G-KvXJa7DxmSsxM0oaRIbUFI_j",
        },
        {
          src: "https://mindphp.com/images/knowledge/software-computer-code-1940x900_35196.jpg",
        },
        {
          src: "https://code.org/shared/images/social-media/codeorg2020_social.png",
        },
      ],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    this.initialize();
  },
  methods: {
    async initialize() {
      this.loading = true;
      await axios.get("/api/user").then((response) => {
        if (response.data.success == true) {
          this.$store.commit("data/SET_USER", response.data.user);

        }
      });

      this.loading = false;
    },
  },
};
</script>
