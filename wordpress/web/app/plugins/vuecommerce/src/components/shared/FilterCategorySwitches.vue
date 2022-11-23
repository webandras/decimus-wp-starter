<template>
    <div class="input-filters mb-2">
        <div
            v-for="(cat, index) in categories"
            :key="`${cat.slug}_${index}`"
            class="form-check form-switch"
        >
            <input
                :id="`${cat.slug}_${index}`"
                v-model="category"
                :value="cat.term_id"
                class="form-check-input mb-2"
                type="checkbox"
            />
            <label :for="`${cat.slug}_${index}`" class="form-check-label">
                {{ cat.name }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            category: [],
        };
    },

    watch: {
        // notify the parent component when sa radio option picked
        category(id) {
            this.$emit("onSelectCategory", id);
        },
    },

    props: {
        categories: {
            type: Array,
            required: true,
            default: null,
        },
    },
};
</script>

<style scoped>
</style>
