
<template>
  <v-row>
    <v-col cols="12">
      <v-row justify="center">
        <v-container>
          <v-data-table
            :headers="headers"
            :items="
              desserts.filter((e) => {
                if (this.status === 'student') {
                  if (e.role_student === 1) {
                    return e;
                  }
                } else if (this.status === 'ta') {
                  if (e.role_ta === 1) {
                    return e;
                  }
                } else if (this.status === 'teacher') {
                  if (e.role_teacher === 1) {
                    return e;
                  }
                } else if (this.status === 'all') {
                  return e;
                }
              })
            "
            sort-by="calories"
            class="elevation-1"
            :search="search"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-toolbar-title>Manage User</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-radio-group v-model="status" row class="mt-7 mr-10">
                  <v-radio label="All" value="all"></v-radio>
                  <v-radio label="TA" value="ta"></v-radio>
                  <v-radio label="Teacher" value="teacher"></v-radio>
                  <v-radio label="Student" value="student"></v-radio>
                </v-radio-group>
                <v-text-field
                  v-model="search"
                  append-icon="mdi-magnify"
                  label="Search"
                  single-line
                  hide-details
                >
                </v-text-field>
                <v-btn
                  color="primary"
                  dark
                  class="mb-2 ml-3"
                  @click="$router.push('/new-user')"
                >
                  New Teacher
                </v-btn>
              </v-toolbar>
            </template>

            <template v-slot:[`item.action`]="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">
                mdi-pencil
              </v-icon>
              <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
            </template>
          </v-data-table>
        </v-container>
      </v-row>
    </v-col>
  </v-row>
</template>
<script>
export default {
  data: function () {
    return {
      desserts: [],
      status: "all",
      search: "",
      headers: [
        {
          text: "Name",
          align: "start",
          sortable: false,
          value: "name",
        },
        { text: "Email", value: "email" },
        { text: "Username", value: "username" },
        { text: "Action", value: "action" },
      ],
    };
  },

  created() {
    this.getUser();
  },

  methods: {
    async getUser() {
      this.loading = true;
      await axios.get("/api/manage/user").then((response) => {
        if (response.data.success == true) {
          this.desserts = response.data.payload;
        }
      });
      console.log(this.desserts);
      this.loading = false;
    },
  },
};
</script>

