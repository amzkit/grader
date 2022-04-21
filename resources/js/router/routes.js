const routes = [
    {
        path: "/",
        name: "app",
        component: () => {
            let user_role = document.head.querySelector(
                'meta[name="user-role"]'
            ).content;
            console.log(user_role);
            switch (user_role) {
                case "admin":
                    return import("../pages/home.vue");
                case "student":
                    return import("../pages/home.vue");
                case "ta":
                    return import("../pages/home.vue");
                case "teacher":
                    return import("../pages/home.vue");
                // case "guest":
                //     return import("../pages/home.vue");
                default:
                    return import("../pages/guest.vue");
            }
        }
    },
    // User
    {
        path: "/new-user",
        name: "new-user",
        component: () => import("../components/Admin/NewUser.vue")
    },
    {
        path: "/manage-user",
        name: "manage-user",
        component: () => import("../components/Admin/ManageUser.vue")
    },
    {
        path: "/change-password",
        name: "change-password",
        component: () => import("../auth/ChangePassword.vue")
    },
    // Mission
    {
        path: "/manage-problem-classroom",
        name: "manage-problem-classroom",
        component: () =>
            import("../components/Mission/ManageMissionClassroom.vue")
    },
    {
        path: "/manage-problem",
        name: "manage-problem",
        component: () => import("../components/Mission/ManageMission.vue")
    },
    {
        path: "/new-problem",
        name: "new-problem",
        component: () => import("../components/Mission/NewMission.vue")
    },
    {
        path: "/problem",
        name: "problem",
        component: () => import("../components/Mission/Mission.vue")
    },
    // Test Mission
    {
        path: "/new-test",
        name: "new-test",
        component: () => import("../components/TestMission/NewTest.vue")
    },
    // Classroom
    {
        path: "/manage-classroom",
        name: "manage-classroom",
        component: () => import("../components/Classroom/ManageClassroom.vue")
    },
    {
        path: "/problems-view",
        name: "problems-view",
        component: () => import("../components/Mission/Problems.vue")
    },
    {
        path: "/new-classroom",
        name: "new-classroom",
        component: () => import("../components/Classroom/NewClassroom.vue")
    },
    // Score
    {
        path: "/my-score",
        name: "my-score",
        component: () => import("../pages/MyScorePage.vue")
    },
    {
        path: "/my-score-mission",
        name: "my-score-mission",
        component: () => import("../components/MyScore/MyScoreDetail.vue")
    },
    // Scoreboard
    {
        path: "/scoreboard",
        name: "scoreboard",
        component: () => import("../pages/ScoreBoardPage.vue")
    },
    {
        path: "/comment",
        name: "comment",
        component: () => import("../components/Scoreboard/Comment.vue")
    },
    {
        path: "/scoreboard-score",
        name: "scoreboard-score",
        component: () =>
            import("../components/Scoreboard/MyScoreScoreboard.vue")
    }
];

export default routes;
