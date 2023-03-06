import Route from "../types/Route";
import LandingView from "../views/LandingView.vue";
import AdminPanelView from "../views/AdminPanelView.vue";

const routes: Route[] = [
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
];

export default routes;
