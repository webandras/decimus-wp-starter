import Vue from "vue";
import router from "./app-routes";
import VueI18n from "vue-i18n";
import {library} from "@fortawesome/fontawesome-svg-core";
import {faTimes, faSearch, faChevronDown, faChevronUp} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

import App from "./App.vue";

require("animate.css");

library.add(faTimes, faSearch, faChevronDown, faChevronUp);
Vue.component("font-awesome-icon", FontAwesomeIcon);

Vue.config.productionTip = false;

Vue.use(VueI18n);

// Ready translated locale messages
const messages = {
    en: {
        navigation: {
            search: "Search",
            categories: "Categories",
        },
        shared: {
            loading: "Loading... ",
            error: "The request could not be processed!",
            search: "Search",
            placeholder: "Search in titles and excerpts for...",
            posts: "Items",
            foundOf: "Found {numberOfResults} of {numberOfAllPosts}",
        },
        postsPage: {
            filterByCategories: "Filter by Categories",
            sortByDateOrder: "Sort by Date",
            newestFirst: "Newest first",
            oldestFirst: "Oldest first",
            discounted: "On sale",
            readMore: "Read more",
        },
        categoriesPage: {
            allPosts: "All Posts",
            postsInCategory: "posts in category",
        },
        productsPage: {
            filterByCategory: "Filter by Category",
            filterByPrice: "Filter by Price",
            filterByOnSale: "Only On Sale Products",
            sortByProps: "Sort products",
            maxPrice: "Max price:",
            minPrice: "Min price:",
            error: "The minimum price should be less than the maximum price.",
            selectOptions: "Select options",
        },
        sortProducts: {
            sortBy: "Select sorting option",
            newest: "Newest First",
            popular: "Most Popular",
            cheap: "Cheapest First",
            abc: "In Alphabetical Order",
        },
        searchPosts: {
            detailedSearch: "Detailed search/filter",
        },
    },
    hu: {
        navigation: {
            searchPosts: "Bejegyzéskeresés",
            searchProducts: "Termékkeresés",
            productCategories: "Termékkategóriák",
            postCategories: "Bejegyzés-kategóriák",
        },
        shared: {
            loading: "Betöltés... ",
            error: "A kérést nem lehetett feldolgozni!",
            search: "Keresés",
            placeholder: "Keresés a címekben és a leírásban...",
            posts: "elem",
            foundOf: "{numberOfResults} találat {numberOfAllPosts} elemből",
        },
        postsPage: {
            filterByCategories: "Szűrés kategórák szerint",
            sortByDateOrder: "Rendezés dátum szerint",
            newestFirst: "Legújabb elől",
            oldestFirst: "legrégebbi elől",
            readMore: "Tovább olvasom",
        },
        categoriesPage: {
            allPosts: "Összes bejegyzés",
            postsInCategory: "bejegyzés a kategóriában",
        },
        productsPage: {
            filterByCategory: "Kategória szerint szűrés",
            filterByPrice: "Ár szerint szűrés",
            filterByOnSale: "Csak a leértékelt termékek",
            sortByProps: "Sorba rendezés",
            discounted: "Leértékeltek",
            maxPrice: "Legdrágább:",
            minPrice: "Legolcsóbb:",
            error: "A minimum árnak kisebbnek kell lennie a maximum árnál.",
            selectOptions: "Válassz opciót",
        },
        sortProducts: {
            sortBy: "Válassz sorbarendezési módot",
            newest: "A legújabb elől",
            popular: "A legnépszerűbb elől",
            cheap: "A legolcsóbb elől",
            abc: "ABC sorrendben",
        },
        searchPosts: {
            detailedSearch: "Részletes keresés/szűrés",
        },
    },
};

const i18n = new VueI18n({
    locale: "hu", // set locale
    messages: messages, // set locale ui texts
});

new Vue({
    render: (h) => h(App),
    router,
    i18n,
}).$mount("#vuecommerce-filter-products");
