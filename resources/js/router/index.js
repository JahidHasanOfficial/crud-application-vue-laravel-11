import { createRouter, createWebHistory } from "vue-router";

const routes = {};
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/home",
            name: "home",
            component: () => import("./../crud/index.vue"),
        },
        {
            path: "/create",
            name: "create",
            component: () => import("./../crud/create.vue"),
        },
        {
            path: "/edit/:id",
            name: "edit",
            component: () => import("./../crud/edit.vue"),
        },
    ],
});

export default router;