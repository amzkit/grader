const state = {
    courses: [],
    user: [],
    schedule_all: [],
}

const getters = {}
const actions = {}
const mutations = {
    SET_COURSES: (state, newValue) => {
        state.courses = newValue
    },
    SET_USER: (state, newValue) => {
        state.user = newValue
    },
    SET_SCHEDULES_ALL: (state, newValue) => {
        state.schedule_all = newValue
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
