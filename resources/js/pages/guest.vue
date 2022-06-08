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
              :src="`${'https://programming.csmju.com'}/storage${item.picture.replace(
                'public',
                ''
              )}`"
              reverse-transition="fade-transition"
              transition="fade-transition"
            >
            </v-carousel-item>
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
      image: [],
      loading: false,
    };
  },
  components: { GuestTopBar },
  created() {
    this.fetchAnnounce();
  },
  methods: {
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
  },
};
</script>
