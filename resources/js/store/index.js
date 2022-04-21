import Vue from "vue";
import Vuex from "vuex";

import top_bar from "./layouts/top_bar";
import navigation_drawer from "./layouts/navigation_drawer";
import data from "./data";
import snackbar from "./snackbar";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        top_bar,
        navigation_drawer,
        data,
        snackbar
    }
});
