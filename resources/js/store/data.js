const state = {
    manageClassroom: {},
    manageStdClassroom: [],
}

const getters = {}
const actions = {}
const mutations = {
    SET_CLASSROOM: (state, newValue) => {
        state.manageClassroom = newValue
    },
    SET_STD_CLASSROOM: (state, newValue) => {
        state.manageStdClassroom = newValue
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
