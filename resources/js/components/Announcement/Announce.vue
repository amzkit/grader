<template>
  <v-row justify="center">
    <Loading :loading="loading" />
    <Snackbar />
    <v-col cols="10">
      <v-data-table
        :headers="headers"
        :items="desserts"
        sort-by="calories"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Announcement</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="800px">
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  dark
                  class="mb-2"
                  v-bind="attrs"
                  v-on="on"
                >
                  New Announcement
                </v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row no-gutters justify="center">
                      <v-col cols="6">
                        <template v-if="editedIndex > -1 && !url">
                          <v-img
                            style="
                              height: 250px;
                              border: 1px solid #dcdcdc;
                              width: 100%;
                              object-fit: cover;
                            "
                            :src="`${'https://programming.csmju.com'}/storage/${editedItem.picture.replace(
                              'public/',
                              ''
                            )}`"
                          ></v-img>
                        </template>
                        <template v-else>
                          <v-img
                            :src="url"
                            style="
                              height: 250px;
                              border: 1px solid #dcdcdc;
                              width: 100%;
                              object-fit: cover;
                            "
                          />
                        </template>
                      </v-col>
                      <v-col cols="5" class="mt-12 ml-5">
                        <input
                          type="file"
                          accept="image/*"
                          class="form-control"
                          ref="file"
                          v-on:change="onFileChange"
                        />
                        <v-checkbox
                          class="mt-4 ml-12"
                          v-model="editedItem.IsActive"
                          :label="`Is Active`"
                        ></v-checkbox>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>

                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">
                    Cancel
                  </v-btn>
                  <v-btn color="blue darken-1" text @click="save"> Save </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialogDelete" max-width="500px">
              <v-card>
                <v-card-title class="text-h5"
                  >Are you sure you want to delete this item?</v-card-title
                >
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="closeDelete"
                    >Cancel</v-btn
                  >
                  <v-btn color="blue darken-1" text @click="deleteItemConfirm"
                    >OK</v-btn
                  >
                  <v-spacer></v-spacer>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:[`item.index`]="{ index }">
          {{ index + 1 }}
        </template>
        <template v-slot:[`item.picture`]="{ item }">
          <v-img
            max-height="190"
            max-width="290"
            :src="`${'https://programming.csmju.com'}/storage/${item.picture.replace(
              'public/',
              ''
            )}`"
          ></v-img>
        </template>
        <template v-slot:[`item.IsActive`]="{ item }">
          <div v-if="item.IsActive === 1">
            <v-icon class="mr-2"> mdi-check </v-icon>
          </div>
          <div v-else>
            <v-icon class="mr-2"> mdi-close </v-icon>
          </div>
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)">
            mdi-pencil
          </v-icon>
          <v-icon small @click="deleteItem(item)"> mdi-delete </v-icon>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
import Loading from "../Loading/Loading.vue";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  name: "Announce",
  components: { Loading, Snackbar },
  data: () => ({
    desserts: [],
    loading: false,
    file: "",
    url: null,
    headers: [
      {
        text: "#",
        align: "center",
        sortable: false,
        value: "index",
      },
      { text: "Picture", value: "picture", width: "350pt" },
      { text: "IsActive", value: "IsActive" },
      { text: "Action", value: "actions" },
    ],
    dialog: false,
    dialogDelete: false,
    editedIndex: -1,
    editedItem: {
      id: 0,
      IsActive: 1,
      picture: "",
    },
    defaultItem: {
      id: 0,
      IsActive: 0,
      picture: "",
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Announcement" : "Edit Announcement";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  async created() {
    await this.getAnnouncement();
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

    reset() {
      this.$refs.file.value = "";
      this.url = null;
    },
    onFileChange(e) {
      this.file = e.target.files[0];
      this.url = URL.createObjectURL(this.file);
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.deleteAnnouncement(item.id);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
      this.closeDelete();
    },

    close() {
      this.reset();
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      if (this.editedIndex > -1) {
        this.putAnnouncement();
      } else {
        this.postAnnouncement();
      }
      this.close();
    },

    async getAnnouncement() {
      this.loading = true;
      await axios
        .get("/api/announce")
        .then((response) => {
          if (response.data.success == true) {
            this.desserts = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async postAnnouncement() {
      this.loading = true;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      if (this.file != null) {
        formData.append("file", this.file);
        formData.append("IsActive", this.editedItem.IsActive);
      }
      await axios
        .post("/api/announce", formData, config)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async putAnnouncement() {
      this.loading = true;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("id", this.editedItem.id);
      formData.append("file", this.file);
      formData.append("IsActive", this.editedItem.IsActive == true ? 1 : 0);
      await axios
        .post("/api/announce/update", formData, config)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async deleteAnnouncement(id) {
      this.loading = true;
      await axios.delete(`/api/announce/${id}`).catch((error) => {
        this.snackBar(3500, error, "error");
      });
      this.loading = false;
    },
  },
};
</script>



