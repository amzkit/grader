const routes = [
    {
        path: '/',
        name: 'app',
        component: () => {
            let user_role = document.head.querySelector('meta[name="user-role"]').content
            switch(user_role){
                case "admin":
                    return import('../pages/home.vue')
                case "student":
                    return import('../pages/home.vue')
                case "teacher":
                    return import('../pages/home.vue')
                case "ta":
                    return import('../pages/home.vue')
                default:
                    return import('../pages/guest.vue')
            }


        },
    },
    {
        path: "*",
    }
]

export default routes;
