<template>
    <div v-if="isDataAvailable">
        <!-- Show post / result count -->
        <Counter
            :filteredResultsLength="filteredResults.length"
            :wpPostsLength="wpProducts.length"
        />
        <!-- End Show post / result count -->

        <!-- Display post items in grid -->
        <div
            class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 g-4 my-4"
        >
            <DisplayProduct
                v-for="product in filteredResults"
                :key="product.id"
                :product="product"
                role="article"
            />
        </div>
        <!-- End Display post items in grid -->
    </div>

    <!-- Display message and spinner when requesting data -->
    <LoadingResults v-else :apiResponse="apiResponse"/>
    <!-- End Display message and spinner when requesting data -->
</template>

<script>

import ProductServiceMixin from "../../mixins/ProductServiceMixin";

import DisplayProduct from "../../components/products/DisplayProduct.vue";
import Counter from "../../components/shared/Counter.vue";
import LoadingResults from "../../components/shared/LoadIndicator.vue";

export default {
    mixins: [ProductServiceMixin],
    components: {
        DisplayProduct,
        Counter,
        LoadingResults,
    },

};
</script>

<style scoped>
</style>
