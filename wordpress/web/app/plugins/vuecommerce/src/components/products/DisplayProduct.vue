<template>
    <div class="col">
        <div :style="{ display: 'relative' }" class="card h-100 d-flex text-center">
            <img
                :alt="product.name"
                :src="product.images[0].src"
                class="card-img-top"
            />
            <div class="card-body d-flex flex-column">
                <h4 class="card-title">
                    <a :href="product.permalink">
                        {{ product.name }}
                    </a>
                </h4>
                <span v-if="product.on_sale === true" class="badge bg-danger sale"
                >%</span
                >
                <p class="card-text" v-html="product.short_description"/>
                <p class="price mb-3" v-html="product.price_html"/>

                <div class="add-to-cart-container mt-auto">
                    <a
                        v-if="product.external_url"
                        :href="product.external_url"
                        class="btn btn-primary"
                    >{{ product.button_text }}</a
                    >
                    <a
                        v-else-if="
              product.stock_quantity > 0 ||
              ((product.stock_quantity === null ||
                product.backorders_allowed) &&
                product.purchasable &&
                product.variations.length === 0)
            "
                        :aria-label="'Add ' + product.name"
                        :data-product_id="product.id"
                        :data-product_sku="product.sku"
                        :href="'?add-to-cart=' + product.id"
                        class="
              wp-block-button__link
              add_to_cart_button
              btn btn-primary
              ajax_add_to_cart
              single_add_to_cart_button
            "
                        data-bs-target="#offcanvas-cart"
                        data-bs-toggle="offcanvas"
                        data-quantity="1"
                        rel="nofollow"
                    >{{ "Add to cart" }}</a
                    >
                    <a v-else :href="product.permalink" class="btn btn-primary">{{
                            $t("productsPage.selectOptions")
                        }}</a>
                </div>
            </div>
            <span
                :style="{ position: 'absolute', top: '10px', right: '10px' }"
                class="badge bg-secondary"
            >{{ productDate }}</span
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        product: {
            type: Object,
            required: true,
            default: null,
        },
    },
    computed: {
        productDate() {
            return this.product.date_created.split("T")[0];
        },
    },
};
</script>

<style scoped>
</style>
