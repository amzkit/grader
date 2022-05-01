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
        :search="search"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Manage Problem</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              label="Search"
              hide-details
              class="mr-5"
            ></v-text-field>
            <v-btn
              color="primary"
              dark
              class="mb-2"
              @click="$router.push('/new-problem')"
            >
              New Problem
            </v-btn>
          </v-toolbar>
          <v-dialog v-model="dialog" max-width="800px">
            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="8">
                      <v-text-field v-model="editedItem.title" label="Title">
                      </v-text-field>
                    </v-col>

                    <v-col cols="4">
                      <div class="text-center">
                        <v-rating
                          v-model="editedItem.level"
                          color="yellow darken-3"
                          background-color="grey darken-1"
                          empty-icon="$ratingFull"
                          half-increments
                          hover
                        ></v-rating>
                        <div>
                          <span class="text-caption text-uppercase"
                            >level:</span
                          >
                          <span class="font-weight-bold">
                            {{ editedItem.level }}
                          </span>
                        </div>
                      </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="3">
                      <v-text-field
                        prefix="$"
                        v-model="editedItem.tolerant"
                        onfocus="this.select()"
                        label="Tolerant"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="9">
                      <input
                        type="file"
                        class="form-control mt-3"
                        v-on:change="onFileChange"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12">
                      <VueEditor
                        v-model="editedItem.question"
                        :editorToolbar="customToolbar"
                      />
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
        </template>
        <template v-slot:[`item.question`]="{ item }">
          {{ convertToPlain(item.question) }}
        </template>
        <template v-slot:[`item.file`]="{ item }">
          <div v-if="item.file">
            <v-icon small class="mr-2" @click="download(item.file)">
              mdi-file-download
            </v-icon>
          </div>
          <div v-else>-</div>
        </template>
        <template v-slot:[`item.tolerant`]="{ item }">
          {{
            item.tolerant.replace("$", "") != ""
              ? item.tolerant.replace("$", "")
              : "-"
          }}
        </template>
        <template v-slot:[`item.action`]="{ item }">
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
import dayjs from "dayjs";
import Loading from "../Loading/Loading.vue";
import { VueEditor } from "vue2-editor";
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    dayjs,
    Loading,
    VueEditor,
    Snackbar,
  },
  data: function () {
    return {
      loading: false,
      search: "",
      dialog: false,
      dialogDelete: false,
      headers: [
        {
          text: "Title",
          align: "start",
          sortable: true,
          value: "title",
        },
        { text: "Question", value: "question" },
        { text: "File", value: "file" },
        {
          text: "Level",
          value: "level",
        },
        {
          text: "Tolerant",
          value: "tolerant",
        },
        {
          text: "Action",
          value: "action",
        },
      ],
      customToolbar: [
        [{ header: [false, 1, 2, 3, 4, 5, 6] }],
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        [
          { align: "" },
          { align: "center" },
          { align: "right" },
          { align: "justify" },
        ],
        ["blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }, { list: "check" }],
        [{ indent: "-1" }, { indent: "+1" }],
        [{ color: [] }, { background: [] }],
      ],
      desserts: [],
      editedIndex: -1,
      editedItem: {
        title: "",
        question: "",
        file: "",
        level: "",
        tolerant: "",
      },
      defaultItem: {
        title: "",
        question: "",
        file: "",
        level: "",
        tolerant: "",
      },
    };
  },
  async created() {
    this.getProblems();
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? "New Problem" : "Edit Problem";
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

  methods: {
    dayjs,
    ...mapActions("snackbar", ["showSnack"]),
    snackBar(timeout = 3500, text = "Successfully", color = "success") {
      this.showSnack({
        text: text,
        color: color,
        timeout: timeout,
      });
    },
    onFileChange(e) {
      this.editedItem.file = e.target.files[0];
    },

    async download(item) {
      this.loading = true;
      await axios
        .get(`/api/schedule/download${item.replace("problem_file", "")}`)
        .then((response) => {
          window.location.href = `api/schedule/download${item.replace(
            "problem_file",
            ""
          )}`;
        })
        .catch((error) => {
          if (error.response.status === 404) {
            this.snackBar(3500, error.response.data.message, "error");
          } else {
            this.snackBar(3500, error, "error");
          }
        });
      this.loading = false;
    },

    convertToPlain(html) {
      var tempDivElement = document.createElement("div");
      tempDivElement.innerHTML = html;
      return tempDivElement.textContent || tempDivElement.innerText || "";
    },

    async getProblems() {
      this.loading = true;
      await axios
        .get("/api/problem")
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

    async delateProblems() {
      this.loading = true;
      await axios
        .delete("api/problem/" + this.editedItem.id)
        .then(() => {
          this.snackBar();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    async putProblem() {
      this.loading = true;
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      let formData = new FormData();
      formData.append("id", this.editedItem.id);
      formData.append("title", this.editedItem.title);
      formData.append("question", this.editedItem.question);
      formData.append("file", this.editedItem.file ? this.editedItem.file : "");
      formData.append("level", this.editedItem.level);
      formData.append(
        "tolerant",
        this.editedItem.tolerant != "" ? this.editedItem.tolerant : "$"
      );
      await axios
        .post("/api/problem/update", formData, config)
        .then(() => {
          this.snackBar();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.loading = false;
    },

    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      this.desserts.splice(this.editedIndex, 1);
      this.delateProblems();
      this.closeDelete();
    },

    close() {
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
      this.snackbar = false;
    },

    save() {
      if (this.editedIndex > -1) {
        this.putProblem();
        Object.assign(this.desserts[this.editedIndex], this.editedItem);
        this.snackbar = false;
      }
      this.close();
    },
  },
};
</script>


