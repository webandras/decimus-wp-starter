<template>
    <div>
        <div class="form-check">
            <input
                id="desc-order"
                v-model="order"
                checked
                class="form-check-input"
                type="radio"
                value="desc"
            />
            <label class="form-check-label" for="desc-order">
                {{ $t("postsPage.newestFirst") }}
            </label>
        </div>
        <div class="form-check">
            <input
                id="asc-order"
                v-model="order"
                class="form-check-input"
                type="radio"
                value="asc"
            />
            <label class="form-check-label" for="asc-order">
                {{ $t("postsPage.oldestFirst") }}
            </label>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            order: "desc",
        };
    },

    watch: {
        // notify the parent component when a radio option picked
        order(order) {
            this.$emit("onOrderToggle", order);
        },
    },
};
</script>

<style scoped>
</style>
