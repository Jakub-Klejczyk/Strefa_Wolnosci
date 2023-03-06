import { ComponentOptionsMixin } from "vue";

type Route = {
    path: string;
    name: string;
    component: ComponentOptionsMixin;
};

export default Route;
