const state = {
    classroom: {},
    classrooms: [],
    manageStdClassroom: [],
    manageClassroomWork: [],
    user: [],
    language: [],
    loading: false,
    classroomExam: [],
}

const getters = {}
const actions = {}
const mutations = {
    SET_CLASSROOM_ID: (state, newValue) => {
        state.classroom = newValue
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
    SET_CLASSROOMS: (state, newValue) => {
        state.classrooms = newValue
    },
    SET_LOADING: (state, newValue) => {
        state.loading = newValue
    },
    SET_CLASSROOM_EXAM: (state, newValue) => {
        state.classroomExam = newValue
    },
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
