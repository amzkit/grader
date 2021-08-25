const routes = [
    {
        path: '/',
        name: 'app',
        component: () => {
            let user_role = document.head.querySelector('meta[name="user-role"]').content
            switch(user_role){
                case "admin":
                    return import('../pages/home.vue')
                default:
                    return import('../pages/guest.vue')
            }
        },
    },
    {
        path: "/classroom",
        name: 'classroom',
        component: () => import('../pages/classroom.vue'),
        
    },
    {
        path: "/manageclass",
        name: 'manageclass',
        component: () => import('../pages/manageclass.vue'),
        
    },
    {
        path: "/managepropos",
        name: 'managepropos',
        component: () => import('../pages/managepropos.vue'),
        
    },
]

export default routes;
