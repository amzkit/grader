import Vue from 'vue'
import Vuex from 'vuex'

import top_bar from './layouts/top_bar'
import navigation_drawer from './layouts/navigation_drawer'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        top_bar,
        navigation_drawer,
    },
})
