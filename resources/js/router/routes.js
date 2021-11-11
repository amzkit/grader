const routes = [
    {
        path: '/',
        name: 'app',
        component: () => {
            let user_role = document.head.querySelector('meta[name="user-role"]').content
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
        path: "/manageclass",
        name: 'manageclass',
        component: () => import('../pages/ManageClassRoomPage.vue'),

    },
    {
        path: "/newclassroom",
        name: 'newclassroom',
        component: () => import('../components/Manage/FormManageClassroom'),

    },
    {
        path: "/manageexample",
        name: 'manageexample',
        component: () => import('../components/Manage/ManageExample'),

    },
    {
        path: "/myscore",
        name: 'myscore',
        component: () => import('../pages/MyScorePage.vue'),

    },
    {
        path: "/scoreboard",
        name: 'scoreboard',
        component: () => import('../pages/ScoreBoardPage.vue'),

    },
]

export default routes;
