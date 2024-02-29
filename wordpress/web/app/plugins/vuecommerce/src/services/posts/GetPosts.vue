<template>
    <div v-if="isDataAvailable">
        <!-- Show post / result count -->
        <Counter
            :filteredResultsLength="filteredResults.length"
            :wpPostsLength="wpPosts.length"
        />
        <!-- End Show post / result count -->

        <!-- Display post items in grid -->
        <div
            class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-3 g-4 mb-4"
        >
            <DisplayPost
                v-for="post in filteredResults"
                :key="post.id"
                :post="post"
                role="article"
            />
        </div>
        <!-- End Display post items in grid -->
    </div>

    <!-- Display message and spinner when requesting data -->
    <LoadIndicator v-else :apiResponse="apiResponse"/>
    <!-- End Display message and spinner when requesting data -->
</template>

<script>

import PostsServiceMixin from "../../mixins/PostsServiceMixin";

import DisplayPost from "../../components/posts/DisplayPost.vue";
import Counter from "../../components/shared/Counter.vue";
import LoadIndicator from "../../components/shared/LoadIndicator.vue";

export default {
    mixins: [PostsServiceMixin],

    components: {
        DisplayPost,
        Counter,
        LoadIndicator,
    },
};
</script>

<style scoped>
</style>
