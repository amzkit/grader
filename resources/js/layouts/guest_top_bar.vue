<template>

        <v-app-bar
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            app
            color="darken-3"
            style="width:100%;"
        >
            <v-toolbar-title
                style="width: 300px"
                class="ml-0 pl-4"
            >
                <span class="-hidden-sm-and-down">Guest</span>
            </v-toolbar-title>

            <v-spacer></v-spacer>
            <v-btn class="primary pr-4" @click="redirect('/login')">
                Login
            </v-btn>
        </v-app-bar>
</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
        }
    },
    computed: {
        drawer:{
            get(){
                return this.$store.state.top_bar.drawer
            },
            set(value){
                this.$store.commit('top_bar/SET_DRAWER', value)
            }
        },
        ...mapState({
            title: state => state.top_bar.title,
            items: state => state.navigation_drawer.items
        }),
    },
    methods:{
        redirect(url){
            window.location.href = url
        },
    },
}

</script>
