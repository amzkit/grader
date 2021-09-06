<template>
    <v-app id="">
        <v-main>
        <v-container fluid fill-height>
            <v-layout align-center justify-center>

            <v-flex xs12 sm10 md6>
                <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Log In</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <div class="text-center">
                    <v-btn color="error" @click="redirect('/login/mju/redirect')">MJU Sign-In</v-btn>
                    </div>


                    <v-divider></v-divider>

                    <v-form ref="form" id="form" method="POST" action="/login">
                    <v-text-field prepend-icon="person" v-model="email" name="email" label="Email" type="text" placeholder=" " persistent-placeholder></v-text-field>
                    <v-text-field id="password" v-model="password" prepend-icon="lock" name="password" label="Password" type="password" placeholder=" " persistent-placeholder></v-text-field>
                    <v-text-field v-show="false" name="_token" :value="csrf"></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" type="submit" form="form">Log in</v-btn>
                </v-card-actions>
                </v-card>
            </v-flex>
            </v-layout>
        </v-container>
        </v-main>
    </v-app>
</template>

<script>
import GuestTopBar from '../layouts/guest_top_bar.vue'

export default {
    components: { GuestTopBar },
    data: () => ({
            drawer: null,
            email: '',
            password: '',
            //csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            csrf: document.head.querySelector('meta[name="csrf-token"]').content,
        }),
        props: {
            source: String
        },
    methods:{
        redirect(url){
            window.location.href=url
        },

    }

}
</script>

<style>

</style>
