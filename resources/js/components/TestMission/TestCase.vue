<template>
  <v-row>
    <Snackbar />
    <v-col cols="3">
      <v-card class="mx-auto" max-width="300">
        <v-list dense>
          <v-row>
            <v-col>
              <v-subheader>Test Case</v-subheader>
            </v-col>
            <v-col>
              <v-btn
                class="ml-3 mb-2 mr-3"
                depressed
                color="primary"
                @click="addTestCase($route.query.test_case_id)"
              >
                Add Test Case
              </v-btn>
            </v-col>
          </v-row>
          <v-text-field
            class="mx-3"
            label="Search"
            prepend-inner-icon="search"
            v-model="search"
            clearable
            solo
            dense
          ></v-text-field>
          <v-list-item-group>
            <v-list-item
              v-for="(item, i) in filteredItems"
              :key="i"
              @click="seeTestCase(item, i + 1)"
            >
              <v-list-item-content>
                <v-list-item-title
                  v-text="`Test case (${i + 1})`"
                ></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-card>
    </v-col>
    <v-col cols="1"> <v-divider vertical></v-divider></v-col>
    <v-col cols="7">
      <template v-if="testTemplate.number != 0">
        <v-card class="mx-auto mb-4" max-width="100%">
          <v-card>
            <v-card-title class="text-h5"
              >Test Case {{ testTemplate.number }}
              <v-col>
                <v-btn
                  depressed
                  color="error"
                  @click="deleteItem(testTemplate.id)"
                >
                  Delete
                </v-btn>
                <v-btn
                  depressed
                  color="primary"
                  @click="updateItem(testTemplate)"
                >
                  Update
                </v-btn>
              </v-col>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="4"> Input</v-col>
                <v-col cols="8">
                  {{ testTemplate.input }}
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="4"> Output </v-col>
                <v-col cols="8">
                  {{ testTemplate.output }}
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-card>
      </template>
    </v-col>
    <v-dialog v-model="dialogDelete" max-width="500px">
      <v-card>
        <v-card-title class="text-h5"
          >Are you sure you want to delete this item?</v-card-title
        >
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="deleteItemConfirm"
            >OK</v-btn
          >
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialogUpdate" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="text-h5"> Edit Test Case </span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-textarea
                  label="Input"
                  v-model="testModel.input"
                ></v-textarea>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-textarea
                  v-model="testModel.output"
                  label="Output"
                ></v-textarea>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close"> Cancel </v-btn>
          <v-btn color="blue darken-1" text @click="updateTestCase">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>


<script>
import Snackbar from "../Snackbar/Snackbar.vue";
import { mapActions } from "vuex";
export default {
  components: {
    Snackbar,
  },
  data: function () {
    return {
      loading: false,
      search: "",
      testcase: [],
      testTemplate: {
        id: 0,
        number: 0,
        input: "",
        output: "",
      },
      testModel: {
        id: 0,
        input: "",
        output: "",
      },
      dialogDelete: false,
      dialogUpdate: false,
    };
  },

  created() {
    this.fetchTestCase();
  },

  computed: {
    filteredItems() {
      return _.orderBy(
        this.testcase.filter((item) => {
          return (
            item.input.toLowerCase().includes(this.search.toLowerCase()) ||
            item.output.toLowerCase().includes(this.search.toLowerCase())
          );
        }),
        "headline"
      );
    },
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

    addTestCase(id) {
      this.$router.push({
        path: "/new-test",
        query: {
          test_case_id: id,
        },
      });
    },

    seeTestCase(data, number) {
      this.testTemplate.id = data.id;
      this.testTemplate.number = number;
      this.testTemplate.input = data.input;
      this.testTemplate.output = data.output;
    },

    async fetchTestCase() {
      await axios
        .get(`api/test-case/${this.$route.query.test_case_id}`)
        .then((response) => {
          if (response.data.success == true) {
            this.testcase = response.data.payload;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
    },

    async deleteTestCase() {
      await axios
        .delete(`api/test-case/${this.testTemplate.id}`)
        .then(() => {
          location.reload();
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
    },

    async updateTestCase() {
      await axios
        .post("/api/test-case/update", {
          id: this.testModel.id,
          input: this.testModel.input,
          output: this.testModel.output,
        })
        .then((response) => {
          if (response.data.success == true) {
            this.testTemplate.input = response.data.payload.input;
            this.testTemplate.output = response.data.payload.output;
          }
        })
        .catch((error) => {
          this.snackBar(3500, error, "error");
        });
      this.dialogUpdate = false;
    },

    deleteItem() {
      this.dialogDelete = true;
    },
    close() {
      this.dialogDelete = false;
      this.dialogUpdate = false;
    },

    deleteItemConfirm() {
      this.deleteTestCase();
      this.closeDelete();
    },

    deleteItemConfirm() {
      this.deleteTestCase();
      this.closeDelete();
    },

    updateItem() {
      this.testModel.id = this.testTemplate.id;
      this.testModel.input = this.testTemplate.input;
      this.testModel.output = this.testTemplate.output;
      this.dialogUpdate = true;
    },
  },
};
</script>