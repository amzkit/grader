<template>
    <v-row justify="center">
        <v-col>
            <v-card :loading="loading">
                <v-card-title>
                <span class="">Home Component</span>
                </v-card-title>

                <v-card-text>
                    I'm a home component from <br>
                    /resources/js/pages/home.vue

                    <v-card v-if="user" class="mt-3">
                        <v-card-title>Current User</v-card-title>
                        <v-card-subtitle>Data retrived from api "/api/user"</v-card-subtitle>
                        <v-card-text>
                            Name : {{ user.name }} <br>
                            Email : {{ user.email }} <br>
                            Role : {{ user.role }} <br>
                        </v-card-text>
                    </v-card>
                </v-card-text>
            </v-card>
        </v-col>


    </v-row>
</template>

<script>
export default {
    data: function(){
        return {
            loading : true,
            user: null,
        }
    },
    mounted() {

        console.log('Component mounted.')
    },
    created(){
        this.initialize()
    },
    methods:{
        async initialize(){
            this.loading = true
            await axios.get('/api/user')
                .then(response => {
                    if(response.data.success == true){
                        console.log(response.data)
                        this.user = response.data.user
                    }
                })
            this.loading = false
        },
    }
}
</script>
