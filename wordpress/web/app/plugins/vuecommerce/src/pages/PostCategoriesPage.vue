<template>
    <div id="categories-page">
        <div class="row row-cols-1 row-cols-md-2 g-4 mt-1">
            <div v-for="category in wpCategories" :key="category.term_id" class="col">
                <div class="card h-100">
                    <img
                        v-if="wpCategoryImages[category.term_id]"
                        :src="wpCategoryImages[category.term_id]"
                        alt=""
                        class="card-img"
                    />
                    <div
                        :class="wpCategoryImages[category.term_id]
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
            ">
                        <h3
                            :class="
                wpCategoryImages[category.term_id]
                  ? 'card-title text-white'
                  : 'card-title'
              "
                        >
                            {{ category.name }}
                        </h3>
                        <p
                            v-if="category.description"
                            :class="wpCategoryImages[category.term_id] ? 'text-white' : ''"
                        >
                            {{ category.description }}
                        </p>
                        <a
                            :href="'/' + category.taxonomy + '/' + category.slug"
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
                <span class="visually-hidden">{{ $t("categoriesPage.postsInCategory") }}</span>
              </span></a
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CategoriesPage",
    data() {
        return {
            /* eslint-disable no-undef */
            wpCategories: wpData.post_categories,
            /* eslint-disable no-undef */
            wpCategoryImages: wpData.post_category_images,
        };
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
