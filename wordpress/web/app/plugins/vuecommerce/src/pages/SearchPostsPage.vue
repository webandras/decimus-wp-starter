<template>
    <div id="search-page">
        <div class="row mt-4">
            <div v-if="screenWidth >= 768" class="col-3 col-md-3 col-lg-3 col-xxl-2">
                <!-- Filter Posts by Categories -->
                <h2 class="h5">{{ $t("postsPage.filterByCategories") }}</h2>

                <FilterCategorySwitches
                    :categories="wpCategories"
                    @onSelectCategory="categoryIdsFilter = $event"
                />
                <!-- End Filter Posts by Categories -->

                <!-- Sort Posts by Date -->
                <h2 class="h5 mt-4">{{ $t("postsPage.sortByDateOrder") }}</h2>

                <Order @onOrderToggle="order = $event"/>
                <!-- End Sort Posts by Date -->
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xxl-10">
                <!-- Search for Posts -->
                <Search @onChangeSearchTerm="searchTerm = $event"/>
                <!-- End Search for Posts -->

                <div v-if="screenWidth <= 767" class="mt-3 mb-3">
                    <p>
                        <button
                            aria-controls="collapseDetailedSearch"
                            aria-expanded="false"
                            class="btn btn-sm btn-outline-secondary"
                            data-bs-target="#collapseDetailedSearch"
                            data-bs-toggle="collapse"
                            type="button"
                            v-on:click="dropdownState"
                        >
                            {{ $t("searchPosts.detailedSearch") }}
                            <font-awesome-icon
                                :icon="[
                  'fas',
                  dropdown === 'closed' ? 'chevron-down' : 'chevron-up',
                ]"
                                :size="'1x'"
                            >
                            </font-awesome-icon>
                        </button>
                    </p>

                    <div id="collapseDetailedSearch" class="collapse">
                        <!-- Filter Posts by Categories -->
                        <h2 class="h5">{{ $t("postsPage.filterByCategories") }}</h2>

                        <FilterCategorySwitches
                            :categories="wpCategories"
                            @onSelectCategory="categoryIdsFilter = $event"
                        />
                        <!-- End Filter Posts by Categories -->

                        <!-- Sort Posts by Date -->
                        <h2 class="h5 mt-4">{{ $t("postsPage.sortByDateOrder") }}</h2>

                        <Order @onOrderToggle="order = $event"/>
                        <!-- End Sort Posts by Date -->
                    </div>
                </div>

                <!-- Get Post List -->
                <GetPosts
                    :appFilters="categoryIdsFilter"
                    :order="order"
                    :searchTerm="searchTerm"
                />
                <!-- End Get Post List -->
            </div>
        </div>
    </div>
</template>

<script>
import GetPosts from "../services/posts/GetPosts.vue";
import FilterCategorySwitches from "../components/shared/FilterCategorySwitches.vue";
import Order from "../components/posts/Order.vue";
import Search from "../components/shared/Search.vue";

export default {
    name: "SearchPostsPage",
    components: {
        GetPosts,
        FilterCategorySwitches,
        Order,
        Search,
    },
    data() {
        return {
            searchTerm: "",
            categoryIdsFilter: [],
            order: "desc",
            /* eslint-disable no-undef */
            wpCategories: wpData.post_categories,
            /* for responsive layout */
            screenWidth: null,
            // dropdown switch on/off
            dropdown: "closed",
        };
    },

    mounted() {
        this.screenWidth = window.innerWidth;
        window.addEventListener("resize", this.getScreenWidth);
    },
    deactivated() {
        this.dropdown = this.dropdown === "closed" ? "closed" : "opened";
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.getScreenWidth);
    },

    methods: {
        clearSearchTerm() {
            this.searchTerm = "";
        },
        getScreenWidth() {
            this.screenWidth = window.innerWidth;
        },
        dropdownState() {
            this.dropdown = this.dropdown === "closed" ? "opened" : "closed";
        },
    },
};
</script>

<style scoped>
</style>
