import Vue from "vue";
import Router from "vue-router";
import AboutPage from "./pages/AboutPage.vue";
import GlobalSettingsPage from "./pages/GlobalSettingsPage.vue";
import ContactSettingsPage from "./pages/ContactSettingsPage.vue";
import HeaderSettingsPage from "./pages/HeaderSettingsPage.vue";
import AdminSettingsPage from "./pages/AdminSettingsPage.vue";
import WooCommerceSettingsPage from "./pages/WooCommerceSettingsPage.vue";


/*global decimusAdminData:true*/
/*eslint no-undef: "error"*/
const appPath = `/${decimusAdminData.app_path}`;

Vue.use(Router);

/**
 * Each route should map to a component.
 * The "component" can either be an actual component or just a component options object.
 */
export default new Router({
    base: appPath, // path of the SPA relative to the domain.
    mode: "hash", // or "history"
    routes: [
        {
            path: "/global",
            name: "GlobalSettingsPage",
            component: GlobalSettingsPage,
        },
        {
            path: "/contact",
            name: "ContactSettingsPage",
            component: ContactSettingsPage,
        },
        {
            path: "/header",
            name: "HeaderSettingsPage",
            component: HeaderSettingsPage,
        },
        {
            path: "/admin",
            name: "AdminSettingsPage",
            component: AdminSettingsPage,
        },
        {
            path: "/woocommerce",
            name: "WooCommerceSettingsPage",
            component: WooCommerceSettingsPage,
        },
        {
            path: "/about",
            name: "AboutPage",
            component: AboutPage,
        },
        {
            path: "*",
            redirect: {name: "GlobalSettingsPage"},
        },
    ],
});
