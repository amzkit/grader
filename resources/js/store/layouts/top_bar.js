const state = {
    title: "Grader",
    drawer: false,
    dialog: false,
}

const getters = {
    drawer:(state) => {
        return state.drawer
    },
}
const actions = {
    setDrawer: ({commit, state}, newValue) => {
        commit('SET_DRAWER', newValue)
        return state.drawer
    },
}
const mutations = {
    SET_DRAWER: (state, newValue) => {
        state.drawer = newValue
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
