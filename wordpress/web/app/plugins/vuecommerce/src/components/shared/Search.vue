<template>
    <div class="search-field-container">
        <div class="input-group mb-1">
            <input
                v-model.lazy="searchTerm"
                :aria-label="$t('shared.placeholder')"
                :placeholder="$t('shared.placeholder')"
                aria-describedby="button-search"
                class="form-control"
                type="text"
            />
            <button id="button-search" class="btn btn-secondary" type="button">
                <font-awesome-icon :icon="['fas', 'search']" :size="'md'">
                </font-awesome-icon>
                {{ $t("shared.search") }}
            </button>
        </div>

        <div class="clear-icon-container">
            <font-awesome-icon
                v-if="searchTerm"
                :icon="['fas', 'times']"
                :size="'lg'"
                class="text-danger"
                v-on:click="clearSearchTerm"
            >
            </font-awesome-icon>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            searchTerm: "",
        };
    },

    watch: {
        // notify the parent component when an input switch is toggled
        searchTerm(term) {
            this.$emit("onChangeSearchTerm", term);
        },
    },

    methods: {
        clearSearchTerm() {
            this.searchTerm = "";
        },
    },
};
</script>

<style scoped>
.search-field-container .clear-icon-container {
    position: absolute;
    right: 105px;
    top: 0;
    z-index: 1000;
    padding: 8px;
    cursor: pointer;
}

.search-field-container {
    position: relative;
}
</style>
