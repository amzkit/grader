const state = {
    manageClassroom: [],
    manageStdClassroom: [],
    manageClassroomWork: [],
    user: null,
    language: [],
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
    SET_CLASSROOM_WORK: (state, newValue) => {
        state.manageClassroomWork = newValue
    },
    SET_USER: (state, newValue) => {
        state.user = newValue
    },
    SET_LANGUAGE: (state, newValue) => {
        state.language = newValue
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
