<template>
    <div>
        <small v-if="filteredResultsLength === wpPostsLength" class="text-muted"
        >{{ wpPostsLength }} {{ $t("shared.posts") }}</small
        >
        <small v-else class="text-muted">{{
                $t("shared.foundOf", {
                    numberOfResults: filteredResultsLength,
                    numberOfAllPosts: wpPostsLength,
                })
            }}</small>
    </div>
</template>

<script>
export default {
    props: {
        filteredResultsLength: {
            type: Number,
            required: true,
        },
        wpPostsLength: {
            type: Number,
            required: true,
        },
    },
};
</script>

<style scoped>
</style>
