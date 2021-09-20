<template>
    <v-app id="app" data-app>
        <admin-menu></admin-menu>
        <v-main>
            <v-container
                class="mx-auto" style="width:100%;max-width:784px"
                fill-height
                fluid
                >
                <router-view></router-view>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
import AdminMenu from "./admin_menu.vue"

export default {
    name: 'app',
    components:{ AdminMenu },

}

</script>
