const routes = [
    {
        path: '/',
        name: 'app',
        component: () => {
            let user_role = document.head.querySelector('meta[name="user-role"]').content
            console.log(user_role)
            switch (user_role) {
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
        component: () => import('../pages/ClassRoomPage.vue'),
    },
    {
        path: "/classroom/:id",
        name: 'classroom-code',
        component: () => import('../components/Classroom/Code.vue'),
    },
    {
        path: "/manage-classroom",
        name: 'manage-classroom',
        component: () => import('../pages/ManageClassRoomPage.vue'),

    },
    {
        path: "/new-classroom",
        name: 'new-classroom',
        component: () => import('../components/Manage/FormManageClassroom'),

    },
    {
        path: "/manage-example",
        name: 'manage-example',
        component: () => import('../components/Manage/ManageExample'),

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
