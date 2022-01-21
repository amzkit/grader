const routes = [
    {
        path: '/',
        name: 'app',
        component: () => {
            let user_role = document.head.querySelector('meta[name="user-role"]').content
            switch (user_role) {
                case "admin":
                    return import('../pages/home.vue')
                case "student":
                    return import('../pages/home.vue')
                case "ta":
                    return import('../pages/home.vue')
                case "teacher":
                    return import('../pages/home.vue')
                default:
                    return import('../pages/guest.vue')
            }
        },
    },
    {
        path: "/new-user",
        name: 'new-user',
        component: () => import('../components/Admin/UserImport.vue'),
    },
    {
        path: "/manage-user",
        name: 'manage-user',
        component: () => import('../components/Admin/ManageUser.vue'),
    },
    {
        path: "/manage-example",
        name: 'manage-example',
        component: () => import('../components/Manage/ManageExample.vue'),
    },
    {
        path: "/new-classroom",
        name: 'new-classroom',
        component: () => import('../components/Classroom/NewClassroom.vue'),
    },
    {
        path: "/changepassword",
        name: 'changepassword',
        component: () => import('../auth/ChangePassword.vue'),
    },
    {
        path: "/classroom",
        name: 'classroom',
        component: () => import('../pages/ClassRoomPage.vue'),
    },
    {
        path: "/manage-classroom",
        name: 'manage-classroom',
        component: () => import('../components/Manage/ManageClassroom.vue'),
    },
    {
        path: "/new-example",
        name: 'new-example',
        component: () => import('../components/Manage/NewExample.vue'),

    },
    {
        path: "/my-score",
        name: 'my-score',
        component: () => import('../pages/MyScorePage.vue'),

    },
    {
        path: "/scoreboard",
        name: 'scoreboard',
        component: () => import('../pages/ScoreBoardPage.vue'),

    },
]

export default routes;
