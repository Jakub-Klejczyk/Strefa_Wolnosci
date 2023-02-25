import { createRouter, createWebHistory } from "vue-router";
import LandingView from "../views/LandingView.vue";
import AdminPanelView from "../views/AdminPanelView.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "landing",
            component: LandingView,
        },
        {
            path: "/admin",
            name: "admin",
            component: AdminPanelView,
        },
    ],
});

export default router;
