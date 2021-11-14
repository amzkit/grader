(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Manage_ManageExample_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      file: "",
      selectedClassroom: this.$store.state.data.manageClassroom.id,
      workName: "",
      subjectName: "",
      subjectFile: null,
      score: 0,
      selectedLanguages: 0,
      stateDate: null,
      endDate: null
    };
  },
  mounted: function mounted() {},
  created: function created() {
    this.initialize();
  },
  methods: {
    onFileChange: function onFileChange(e) {
      console.log(e.target.files[0]);
      this.file = e.target.files[0];
    },
    initialize: function initialize() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return axios.get("api/languages").then(function (response) {
                  _this.$store.commit("data/SET_LANGUAGE", response.data);
                });

              case 2:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    },
    formSubmit: function formSubmit(e) {
      e.preventDefault();
      var currentObj = this;
      var config = {
        headers: {
          "content-type": "multipart/form-data"
        }
      };
      var formData = new FormData();
      formData.append("classroom_id", this.selectedClassroom);
      formData.append("work_name", this.workName);
      formData.append("subject_name", this.subjectName);
      formData.append("file", this.file);
      formData.append("score", this.score);
      formData.append("language_id", this.selectedLanguages);
      formData.append("send_start_work", this.stateDate);
      formData.append("send_end_work", this.endDate);
      axios.post("api/quiz", formData, config).then(function (response) {
        currentObj.success = response.data.success;
      })["catch"](function (error) {
        currentObj.output = error;
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/components/Manage/ManageExample.vue":
/*!**********************************************************!*\
  !*** ./resources/js/components/Manage/ManageExample.vue ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ManageExample.vue?vue&type=template&id=827ecf34& */ "./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34&");
/* harmony import */ var _ManageExample_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ManageExample.vue?vue&type=script&lang=js& */ "./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _ManageExample_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__.render,
  _ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Manage/ManageExample.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageExample_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ManageExample.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageExample_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageExample_vue_vue_type_template_id_827ecf34___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ManageExample.vue?vue&type=template&id=827ecf34& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Manage/ManageExample.vue?vue&type=template&id=827ecf34& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-row",
    { attrs: { justify: "center" } },
    [
      _c("h1", [_vm._v("จัดการโจทย์")]),
      _vm._v(" "),
      _c(
        "v-card",
        [
          _c(
            "v-container",
            { attrs: { fluid: "" } },
            [
              _c(
                "v-row",
                { attrs: { align: "center" } },
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "4" } },
                    [_c("v-subheader", [_vm._v(" ห้องเรียน ")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-subheader", [
                        _vm._v(
                          "\n            " +
                            _vm._s(
                              this.$store.state.data.manageClassroom.className
                            ) +
                            "\n          "
                        )
                      ])
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-row",
                { attrs: { align: "center" } },
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "4" } },
                    [_c("v-subheader", [_vm._v(" ชื่องาน ")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-text-field", {
                        attrs: { label: "ใส่ชื่องาน" },
                        model: {
                          value: _vm.workName,
                          callback: function($$v) {
                            _vm.workName = $$v
                          },
                          expression: "workName"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-row",
                { attrs: { align: "center" } },
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "4" } },
                    [_c("v-subheader", [_vm._v(" โจทย์ ")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-textarea", {
                        attrs: { counter: "", label: "พิมพ์โจทย์" },
                        model: {
                          value: _vm.subjectName,
                          callback: function($$v) {
                            _vm.subjectName = $$v
                          },
                          expression: "subjectName"
                        }
                      }),
                      _vm._v(" "),
                      [
                        _c("input", {
                          staticClass: "form-control",
                          attrs: { type: "file" },
                          on: { change: _vm.onFileChange }
                        })
                      ]
                    ],
                    2
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-row",
                { attrs: { align: "center" } },
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "4" } },
                    [_c("v-subheader", [_vm._v(" คะแนน ")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-text-field", {
                        attrs: {
                          type: "number",
                          onfocus: "this.select()",
                          label: "กำหนดคะแนน"
                        },
                        model: {
                          value: _vm.score,
                          callback: function($$v) {
                            _vm.score = $$v
                          },
                          expression: "score"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "4" } },
                    [_c("v-subheader", [_vm._v(" ภาษา ")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-select", {
                        attrs: {
                          items: this.$store.state.data.language,
                          "item-text": "languagesName",
                          "item-value": "id",
                          "single-line": "",
                          auto: "",
                          label: "Languages"
                        },
                        model: {
                          value: _vm.selectedLanguages,
                          callback: function($$v) {
                            _vm.selectedLanguages = $$v
                          },
                          expression: "selectedLanguages"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-row",
                { attrs: { align: "center" } },
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "5" } },
                    [
                      _c("v-subheader", [_vm._v(" กำหนดส่ง ")]),
                      _vm._v(" "),
                      _c("v-date-picker", {
                        model: {
                          value: _vm.stateDate,
                          callback: function($$v) {
                            _vm.stateDate = $$v
                          },
                          expression: "stateDate"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "1" } },
                    [_c("v-text", [_vm._v("ถึง")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-col",
                    { attrs: { cols: "6" } },
                    [
                      _c("v-date-picker", {
                        model: {
                          value: _vm.endDate,
                          callback: function($$v) {
                            _vm.endDate = $$v
                          },
                          expression: "endDate"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("v-btn", { on: { click: _vm.formSubmit } }, [_vm._v(" Save ")])
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);