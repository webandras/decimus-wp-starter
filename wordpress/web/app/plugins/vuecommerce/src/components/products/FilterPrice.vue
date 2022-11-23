<template>
    <div>
        <div
            v-if="
        lowestPrice && highestPrice && parseInt(minPrice) >= parseInt(maxPrice)
      "
            class="alert alert-warning"
            role="alert"
        >
            {{ $t("productsPage.error") }}
        </div>
        <div>
            <label class="form-label" for="maxPrice">{{
                    $t("productsPage.maxPrice")
                }}</label>
            <div class="fw-bold">${{ maxPrice }}</div>
            <input
                id="maxPrice"
                v-model="maxPrice"
                :max="highestPrice"
                :min="lowestPrice"
                class="form-range"
                step="1"
                type="range"
            />
        </div>
        <div>
            <label class="form-label" for="minPrice">{{
                    $t("productsPage.minPrice")
                }}</label>
            <div class="fw-bold">${{ minPrice }}</div>
            <input
                id="minPrice"
                v-model="minPrice"
                :max="highestPrice"
                :min="lowestPrice"
                class="form-range"
                step="1"
                type="range"
            />
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            minPrice: null,
            maxPrice: null,
        };
    },

    props: {
        highestPrice: {
            required: true,
            validator: (p) => {
                return typeof p === "number" || p === null;
            },
        },
        lowestPrice: {
            required: true,
            validator: (p) => {
                return typeof p === "number" || p === null;
            },
        },
    },

    mounted() {
        // here the props are null (request is in progress)
        this.maxPrice = this.highestPrice;
        this.minPrice = this.lowestPrice;
    },

    updated() {
        // only update them if it haven't been set before (because of asynchronous request)
        if (this.maxPrice === null) {
            this.maxPrice = this.highestPrice;
        }
        if (this.minPrice === null) {
            this.minPrice = this.lowestPrice;
        }
    },

    watch: {
        // notify the parent component when the price change
        minPrice(p) {
            this.$emit("onMinPriceSelect", parseInt(p, 10));
        },
        maxPrice(p) {
            this.$emit("onMaxPriceSelect", parseInt(p, 10));
        },
    },
};
</script>

<style scoped>
</style>
