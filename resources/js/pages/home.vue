<template>
  <v-row justify="center">
    <v-carousel
      cycle
      height="500"
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
    <div class="mt-4">
      <h1 class="text-center , font-weight-black white--text">
        SOURCE CODE GRADER
      </h1>
      <h5 class="text-center white--text">Computer Science Maejo University</h5>
    </div>
  </v-row>
</template>

<script>
export default {
  data: function () {
    return {
      loading: true,
      items: [
        {
          src: "https://mindphp.com/images/knowledge/software-computer-code-1940x900_35196.jpg",
        },
        {
          src: "https://code.org/shared/images/social-media/codeorg2020_social.png",
        },
      ],
    };
  },
  mounted() {},
  created() {
    this.firstLogin();
  },
  methods: {
    redirect(url) {
      window.location.href = url;
    },
    firstLogin() {
      let user_role = document.head.querySelector(
        'meta[name="user-role"]'
      ).content;
      let user_last_login = document.head.querySelector(
        'meta[name="user-last-login"]'
      ).content;
      console.log(user_role, user_last_login);
      if (user_role == "student" && user_last_login == "") {
        this.redirect("/change-password");
      }
    },
  },
};
</script>
