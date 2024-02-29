<template>
    <div id="search-page">
        <div class="row mt-4">
            <div v-if="screenWidth >= 768" class="col-3 col-md-3 col-lg-3 col-xxl-2">
                <!-- Filter Products by some Props -->
                <h3 class="h5 mb-3">{{ $t("productsPage.filterByCategory") }}</h3>
                <FilterCategorySwitches
                    :categories="productCategories"
                    @onSelectCategory="categoryIdsFilter = $event"
                />

                <h3 class="h5 mt-4 mb-3">{{ $t("productsPage.filterByPrice") }}</h3>
                <FilterPrice
                    :highestPrice="highestPrice"
                    :lowestPrice="lowestPrice"
                    @onMaxPriceSelect="maxPrice = parseInt($event, 10)"
                    @onMinPriceSelect="minPrice = parseInt($event, 10)"
                />

                <h3 class="h5 mt-4 mb-3">{{ $t("productsPage.filterByOnSale") }}</h3>
                <FilterOnSale @onSaleToggle="onSale = $event"/>

                <!-- End Filter Products by some Props -->

                <!-- Sort Products by Date -->
                <h3 class="h5 mt-4 mb-3">{{ $t("productsPage.sortByProps") }}</h3>
                <SortProducts @onSelectOrderBy="orderBy = $event"/>

                <!-- End Sort Products -->
            </div>
            <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xxl-10">
                <!-- Search for Products -->
                <Search @onChangeSearchTerm="searchTerm = $event"/>

                <!-- End Search for Products -->
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
                            <font-awesome-icon :icon="['fas', dropdown === 'closed' ? 'chevron-down' : 'chevron-up']"
                                               :size="'1x'">
                            </font-awesome-icon>
                        </button>
                    </p>

                    <div id="collapseDetailedSearch" class="collapse">
                        <!-- Filter Products by some Props -->
                        <h3 class="h5">{{ $t("productsPage.filterByCategory") }}</h3>
                        <FilterCategorySwitches
                            :categories="productCategories"
                            @onSelectCategory="categoryIdsFilter = $event"
                        />

                        <h3 class="h5 mt-4">{{ $t("productsPage.filterByPrice") }}</h3>
                        <FilterPrice
                            :highestPrice="highestPrice"
                            :lowestPrice="lowestPrice"
                            @onMaxPriceSelect="maxPrice = parseInt($event, 10)"
                            @onMinPriceSelect="minPrice = parseInt($event, 10)"
                        />

                        <h3 class="h5 mt-4">{{ $t("productsPage.filterByOnSale") }}</h3>
                        <FilterOnSale @onSaleToggle="onSale = $event"/>

                        <!-- End Filter Products by some Props -->

                        <!-- Sort Products by Date -->
                        <h3 class="h5 mt-4">{{ $t("productsPage.sortByProps") }}</h3>
                        <SortProducts @onSelectOrderBy="orderBy = $event"/>

                        <!-- End Sort Products -->
                    </div>
                </div>

                <!-- Get Product List -->
                <GetProducts
                    :categoryFilters="categoryIdsFilter"
                    :maxPrice="maxPrice"
                    :minPrice="minPrice"
                    :onSale="onSale"
                    :orderBy="orderBy"
                    :searchTerm="searchTerm"
                    @onGettingHighestPrice="highestPrice = parseInt($event)"
                    @onGettingLowestPrice="lowestPrice = parseInt($event)"
                />
                <!-- End Get Product List -->
            </div>
        </div>
    </div>
</template>

<script>
import FilterCategorySwitches from "../components/shared/FilterCategorySwitches.vue";
import FilterOnSale from "../components/products/FilterOnSale.vue";
import FilterPrice from "../components/products/FilterPrice.vue";
import SortProducts from "../components/products/SortProducts.vue";
import Search from "../components/shared/Search.vue";
import GetProducts from "../services/products/GetProducts.vue";

export default {
    name: "SearchProductsPage",
    components: {
        FilterCategorySwitches,
        FilterOnSale,
        FilterPrice,
        SortProducts,
        Search,
        GetProducts,
    },

    mounted() {
        this.screenWidth = window.innerWidth;
        window.addEventListener("resize", this.getScreenWidth);
    },
    deactivated() {
        this.dropdown = this.dropdown === 'closed' ? 'closed' : 'opened';
    },
    beforeDestroy() {
        window.removeEventListener("resize", this.getScreenWidth);
    },

    data() {
        return {
            searchTerm: "",
            categoryIdsFilter: [],
            onSale: false,
            maxPrice: null,
            minPrice: null,
            highestPrice: null,
            lowestPrice: null,
            order: "desc",
            orderBy: "date",
            /* eslint-disable no-undef */
            productCategories: wpData.product_categories,
            /* for responsive layout */
            screenWidth: null,
            // dropdown switch on/off
            dropdown: 'closed'
        };
    },
    methods: {
        clearSearchTerm() {
            this.searchTerm = "";
        },
        getScreenWidth() {
            this.screenWidth = window.innerWidth;
        },
        dropdownState() {
            this.dropdown = this.dropdown === 'closed' ? 'opened' : 'closed';
        }
    },
};
</script>

<style scoped>
</style>
