<template>
    <v-app id="app" data-app>
        <guest-top-bar />
        <v-main>
            <v-container
                class="mx-auto" style="width:100%;max-width:784px"
                fill-height
                fluid
                >
                <v-row justify="center">
                    <v-col>
                        <v-card>
                            <v-card-title>
                            <span class="">Guest Index Page</span>
                            </v-card-title>

                            <v-card-text>
                                I'm a Guest Index component from <br>
                                /resources/js/pages/guest.vue
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>


</template>

<script>
import GuestTopBar from '../layouts/guest_top_bar.vue'
    export default {
        components: { GuestTopBar },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
