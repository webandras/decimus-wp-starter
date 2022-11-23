<template>
    <div class="col">
        <div :style="{ display: 'relative' }" class="card h-100 relative">
            <img
                :alt="post.vue_meta.featuredmedia_alt"
                :src="post.vue_meta.featuredmedia_url"
                class="card-img-top"
            />
            <div class="card-body">
                <h4 class="card-title">
                    <a :href="post.link">
                        {{ post.title.rendered }}
                    </a>
                </h4>

                <p class="card-text" v-html="post.excerpt.rendered"/>
                <a :href="post.link" class="btn btn-sm btn-outline-primary">{{
                        $t("postsPage.readMore")
                    }}</a>
            </div>
            <span
                :style="{ position: 'absolute', top: '10px', right: '10px' }"
                class="badge bg-secondary"
            >{{ postDate }}</span
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        post: {
            type: Object,
            required: true,
            default: null,
        },
    },
    computed: {
        postDate() {
            return this.post.date_gmt.split("T")[0];
        },
    },
};
</script>

<style scoped>
</style>
