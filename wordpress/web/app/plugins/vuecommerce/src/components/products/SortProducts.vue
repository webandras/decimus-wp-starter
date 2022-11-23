<template>
    <div>
        <select v-model="orderBy" aria-label="Default select example" class="form-select">
            <option selected>{{ $t('sortProducts.sortBy') }}</option>
            <option value="date">{{ $t('sortProducts.newest') }}</option>
            <option value="total_sales">{{ $t('sortProducts.popular') }}</option>
            <option value="price">{{ $t('sortProducts.cheap') }}</option>
            <option value="slug">{{ $t('sortProducts.abc') }}</option>
        </select>
    </div>

</template>

<script>
export default {
    data() {
        return {
            orderBy: 'date',
        };
    },

    watch: {
        // notify the parent component when an option selected
        orderBy(o) {
            this.$emit("onSelectOrderBy", o);
        },
    },
};
</script>

<style scoped>
</style>
