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
    <h1 class="text-center , font-weight-black">
        SOURCE CODE GRADER 
    </h1>
    <h5 class="text-center ">
        Computer Science Maejo University 
    </h5>
       
    


    <!-- Home ที่อาจารย์ทำไว้อย่าพึ่งลบนะ -->
    <v-col>
      <v-card :loading="loading">
        <v-card-title>
          <span class="">Home -</span>
        </v-card-title>

        <v-card-text>
          I'm a home component from <br />
          /resources/js/pages/home.vue

          <v-card v-if="user" class="mt-3">
            <v-card-title>Current User</v-card-title>
            <v-card-subtitle
              >Data retrived from api "/api/user"</v-card-subtitle
            >
            <v-card-text>
              Name : {{ user.name }} <br />
              Email : {{ user.email }} <br />
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
      user: null,

    //   เพิ่มรูปตรง slid
      items: [
          {
              src: 'https://siamrath.co.th/files/styles/1140/public/img/20201229/c79b909950b13cc0e4eb35a2fd5e2f373acc4f232e77c0be506b55f7ba168848.jpg?itok=OL38f8eI',
          },
          {
              src: 'https://static.posttoday.com/media/content/2018/08/30/AC72A7A32BED489198FFB1620C6168D8.jpg',
          },
          {
              src: 'https://img.soccersuck.com/images/2021/06/04/1EED5031-AE91-4C8E-AAAF-F55D1634DA47.jpg',
          }
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
          console.log(response.data);
          this.user = response.data.user;
        }
      });
      this.loading = false;
    },
  },
};
</script>
