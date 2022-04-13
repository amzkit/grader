<template>
  <div>
    <guest-top-bar />
    <v-main>
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
          <h1 class="text-center , font-weight-black">SOURCE CODE GRADER</h1>
          <h5 class="text-center">Computer Science Maejo University</h5>
        </div>
      </v-row>
    </v-main>
  </div>
</template>

<script>
import GuestTopBar from "../layouts/guest_top_bar.vue";
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
  components: { GuestTopBar },
  mounted() {},
};
</script>
