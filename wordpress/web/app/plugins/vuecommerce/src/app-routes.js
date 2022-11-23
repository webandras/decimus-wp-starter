import Vue from "vue";
import Router from "vue-router";
import SearchPostsPage from "./pages/SearchPostsPage";
import PostCategoriesPage from "./pages/PostCategoriesPage";
import SearchProductsPage from "./pages/SearchProductsPage";
import ProductCategoriesPage from "./pages/ProductCategoriesPage";

/*global wpData:true*/
/*eslint no-undef: "error"*/
const appPath = `/${wpData.app_path}`;

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
            path: "/search-posts",
            name: "SearchPostsPage",
            component: SearchPostsPage,
        },
        {
            path: "/post-categories",
            name: "PostCategoriesPage",
            component: PostCategoriesPage,
        },
        {
            path: "/search-products",
            name: "SearchProductsPage",
            component: SearchProductsPage,
        },
        {
            path: "/product-categories",
            name: "ProductCategoriesPage",
            component: ProductCategoriesPage,
        },
        {
            path: "*",
            redirect: {name: "SearchPostsPage"},
        },
    ],
});
