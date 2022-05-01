<template>
  <v-container fill-height fluid>
    <v-row justify="center">
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
            <v-carousel-item
              v-for="(item, i) in items"
              :key="i"
              :src="item.src"
              reverse-transition="fade-transition"
              transition="fade-transition"
            ></v-carousel-item>
          </v-carousel>
          <div class="mt-4">
            <h1 class="text-center font-weight-black white--text">
              SOURCE CODE GRADER
            </h1>
            <h5 class="text-center white--text">
              Computer Science Maejo University
            </h5>
          </div>
        </v-row>
      </v-main>
    </v-row>
  </v-container>
</template>

<script>
import GuestTopBar from "../layouts/guest_top_bar.vue";
export default {
  data: function () {
    return {
      loading: false,
      image: [],
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
  created() {
    this.fetchPicture();
  },
  methods: {},
};
</script>
