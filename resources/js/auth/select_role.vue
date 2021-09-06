<template>
    <v-app id="">
        <v-main>
        <v-container fluid fill-height>
            <v-layout align-center justify-center>

            <v-flex xs12 sm10 md6>
                <v-card class="elevation-12">
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Select Role</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <div class="text-center mb-2" v-for="role in all_roles">
                        <v-btn @click="set_role(role)">{{ role }}</v-btn>
                    </div>
                </v-card-text>
                </v-card>
            </v-flex>
            </v-layout>
        </v-container>
        </v-main>
    </v-app>
</template>

<script>

export default {
    data: () => ({
        csrf: document.head.querySelector('meta[name="csrf-token"]').content,
        all_roles: [],
        current_user: [],

    }),
    props: {
        user: String
    },
    created(){
        this.current_user = JSON.parse(this.user)
        this.all_roles = this.current_user.roles
    },
    methods:{
        redirect(url){
            window.location.href=url
        },
        set_role(role){
            console.log(role)
            axios.post('/api/set_role',{
                role: role,
            })
            .then(response => {
                    if(response.data.success == true){
                        console.log(response.data)
                        this.redirect('/')
                    }
                })
        }

    }

}
</script>

<style>

</style>
