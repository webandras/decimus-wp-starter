<template>
    <div id="categories-page">
        <div v-if="isDataAvailable" class="row row-cols-1 row-cols-md-2 g-4 mt-1">
            <div
                v-for="category in wpProductCategories"
                :key="category.slug + '-' + category.id"
                class="col"
            >
                <div class="card h-100">
                    <img
                        v-if="getSafe(() => category.image.src)"
                        :src="category.image.src"
                        alt=""
                        class="card-img"
                    />
                    <div
                        :class="
              getSafe(() => category.image.src)
                ? `card-img-overlay
              d-flex
              flex-column
              align-items-center
              justify-content-center
              p-3
              text-center`
                : `d-flex
              flex-column
              align-items-center
              justify-content-center
              p-3
              text-center`
            "
                    >
                        <h3
                            :class="
                getSafe(() => category.image.src) ? 'card-title text-white' : 'card-title'
              "
                        >
                            {{ category.name }}
                        </h3>
                        <p
                            v-if="category.description"
                            :class="category.term_id ? 'text-white' : ''"
                        >
                            {{ category.description }}
                        </p>
                        <a
                            :href="'/product-category/' + category.slug"
                            class="btn btn-sm btn-primary position-relative"
                        >{{ $t("categoriesPage.allPosts") }}
                            <span
                                class="
                  position-absolute
                  top-0
                  start-100
                  translate-middle
                  badge
                  rounded-pill
                  bg-danger
                "
                            >
                {{ category.count }}
                <span class="visually-hidden">{{
                        $t("categoriesPage.postsInCategory")
                    }}</span>
              </span></a
                        >
                    </div>
                </div>
            </div>
        </div>
        <LoadIndicator v-else :apiResponse="apiResponse"/>
    </div>
</template>

<script>
import axios from "axios";
import LoadIndicator from "../components/shared/LoadIndicator.vue";

export default {
    name: "ProductCategoriesPage",
    data() {
        return {
            apiResponse: "", // initial loading or error messages.
            /* eslint-disable no-undef */
            wpProductCategories: [],
            isDataAvailable: false,
            /* eslint-disable no-undef */
            wpData,
        };
    },

    mounted() {
        this.getProductCategories();
    },

    components: {
        LoadIndicator,
    },

    methods: {
        getSafe(fn) {
            try {
                return fn();
                /* eslint-disable no-empty */
            } catch (e) {
            }
        },

        async getProductCategories(
            route = "products/categories",
            namespace = "wc/v3"
        ) {
            try {
                const restURL = process.env.NODE_ENV === 'development' ? process.env.VUE_APP_REST_API_PATH : this.wpData.rest_url;
                const productsPerPage = 100;

                const ck = process.env.VUE_APP_CK;
                const cs = process.env.VUE_APP_CS;

                // send an initial request and await the response to get the total number of posts.
                const response = await axios.get(
                    `${restURL}/${namespace}/${route}?per_page=${productsPerPage}&page=1`, {
                        auth: {
                            username: ck,
                            password: cs,
                        },
                    }
                );

                this.wpProductCategories = response.data;
                this.isDataAvailable = true;
            } catch (error) {
                this.apiResponse = `$t(The request could not be processed! <br> <strong>${error}</strong> `;
            }
        },
    },
};
</script>

<style scoped>
#categories-page .card img {
    height: 280px;
    object-fit: cover;
    filter: brightness(0.4);
}

#categories-page .card-img-overlay {
    transition: 0.3s all;
}

#categories-page .card-img-overlay:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>
