(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_Admin_ManageUser_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      desserts: [],
      status: "all",
      search: "",
      headers: [{
        text: "Name",
        align: "start",
        sortable: false,
        value: "name"
      }, {
        text: "Email",
        value: "email"
      }, {
        text: "Username",
        value: "username"
      }, {
        text: "Action",
        value: "action"
      }]
    };
  },
  created: function created() {
    this.getUser();
  },
  methods: {
    getUser: function getUser() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _this.loading = true;
                _context.next = 3;
                return axios.get("/api/manage/user").then(function (response) {
                  if (response.data.success == true) {
                    _this.desserts = response.data.payload;
                  }
                });

              case 3:
                console.log(_this.desserts);
                _this.loading = false;

              case 5:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  }
});

/***/ }),

/***/ "./resources/js/components/Admin/ManageUser.vue":
/*!******************************************************!*\
  !*** ./resources/js/components/Admin/ManageUser.vue ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ManageUser.vue?vue&type=template&id=5490d01b& */ "./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b&");
/* harmony import */ var _ManageUser_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ManageUser.vue?vue&type=script&lang=js& */ "./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__.default)(
  _ManageUser_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__.default,
  _ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__.render,
  _ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Admin/ManageUser.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageUser_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ManageUser.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageUser_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__.default); 

/***/ }),

/***/ "./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b&":
/*!*************************************************************************************!*\
  !*** ./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b& ***!
  \*************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ManageUser_vue_vue_type_template_id_5490d01b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ManageUser.vue?vue&type=template&id=5490d01b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/Admin/ManageUser.vue?vue&type=template&id=5490d01b& ***!
  \****************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function() {
  var this$1 = this
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-row",
    [
      _c(
        "v-col",
        { attrs: { cols: "12" } },
        [
          _c(
            "v-row",
            { attrs: { justify: "center" } },
            [
              _c(
                "v-container",
                [
                  _c("v-data-table", {
                    staticClass: "elevation-1",
                    attrs: {
                      headers: _vm.headers,
                      items: _vm.desserts.filter(function(e) {
                        if (this$1.status === "student") {
                          if (e.role_student === 1) {
                            return e
                          }
                        } else if (this$1.status === "ta") {
                          if (e.role_ta === 1) {
                            return e
                          }
                        } else if (this$1.status === "teacher") {
                          if (e.role_teacher === 1) {
                            return e
                          }
                        } else if (this$1.status === "all") {
                          return e
                        }
                      }),
                      "sort-by": "calories",
                      search: _vm.search
                    },
                    scopedSlots: _vm._u(
                      [
                        {
                          key: "top",
                          fn: function() {
                            return [
                              _c(
                                "v-toolbar",
                                { attrs: { flat: "" } },
                                [
                                  _c("v-toolbar-title", [
                                    _vm._v("Manage User")
                                  ]),
                                  _vm._v(" "),
                                  _c("v-divider", {
                                    staticClass: "mx-4",
                                    attrs: { inset: "", vertical: "" }
                                  }),
                                  _vm._v(" "),
                                  _c("v-spacer"),
                                  _vm._v(" "),
                                  _c(
                                    "v-radio-group",
                                    {
                                      staticClass: "mt-7 mr-10",
                                      attrs: { row: "" },
                                      model: {
                                        value: _vm.status,
                                        callback: function($$v) {
                                          _vm.status = $$v
                                        },
                                        expression: "status"
                                      }
                                    },
                                    [
                                      _c("v-radio", {
                                        attrs: { label: "All", value: "all" }
                                      }),
                                      _vm._v(" "),
                                      _c("v-radio", {
                                        attrs: { label: "TA", value: "ta" }
                                      }),
                                      _vm._v(" "),
                                      _c("v-radio", {
                                        attrs: {
                                          label: "Teacher",
                                          value: "teacher"
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("v-radio", {
                                        attrs: {
                                          label: "Student",
                                          value: "student"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c("v-text-field", {
                                    attrs: {
                                      "append-icon": "mdi-magnify",
                                      label: "Search",
                                      "single-line": "",
                                      "hide-details": ""
                                    },
                                    model: {
                                      value: _vm.search,
                                      callback: function($$v) {
                                        _vm.search = $$v
                                      },
                                      expression: "search"
                                    }
                                  }),
                                  _vm._v(" "),
                                  _c(
                                    "v-btn",
                                    {
                                      staticClass: "mb-2 ml-3",
                                      attrs: { color: "primary", dark: "" },
                                      on: {
                                        click: function($event) {
                                          return _vm.$router.push("/new-user")
                                        }
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                New Teacher\n              "
                                      )
                                    ]
                                  )
                                ],
                                1
                              )
                            ]
                          },
                          proxy: true
                        },
                        {
                          key: "item.action",
                          fn: function(ref) {
                            var item = ref.item
                            return [
                              _c(
                                "v-icon",
                                {
                                  staticClass: "mr-2",
                                  attrs: { small: "" },
                                  on: {
                                    click: function($event) {
                                      return _vm.editItem(item)
                                    }
                                  }
                                },
                                [
                                  _vm._v(
                                    "\n              mdi-pencil\n            "
                                  )
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "v-icon",
                                {
                                  attrs: { small: "" },
                                  on: {
                                    click: function($event) {
                                      return _vm.deleteItem(item)
                                    }
                                  }
                                },
                                [_vm._v(" mdi-delete ")]
                              )
                            ]
                          }
                        }
                      ],
                      null,
                      true
                    )
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
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);