const state = {
    items: [
        // { icon: 'mdi-home-outline', text: 'Home', url: '/' },
        { icon: 'mdi-logout', text: 'Log out', url: '/logout' },
    ],
    classroom: 0
}

const getters = {}
const actions = {}
const mutations = {
    SET_CLASSROOM: (state, newValue) => {
        state.classroom = newValue
    },
}




export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
}
