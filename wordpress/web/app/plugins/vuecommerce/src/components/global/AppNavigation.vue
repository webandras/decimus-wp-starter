<template>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <router-link
                active-class="active"
                class="nav-link"
                role="link"
                to="/search-posts"
            >{{ $t("navigation.searchPosts") }}
            </router-link>
            <!-- .nav-item -->
        </li>
        <li class="nav-item">
            <router-link
                active-class="active"
                class="nav-link"
                role="link"
                to="/post-categories"
            >{{ $t("navigation.postCategories") }}
            </router-link>
            <!-- .nav-item -->
        </li>
        <li class="nav-item">
            <router-link
                active-class="active"
                class="nav-link"
                role="link"
                to="/search-products"
            >{{ $t("navigation.searchProducts") }}
            </router-link>
            <!-- .nav-item -->
        </li>
        <li class="nav-item">
            <router-link
                active-class="active"
                class="nav-link"
                role="link"
                to="/product-categories"
            >{{ $t("navigation.productCategories") }}
            </router-link>
            <!-- .nav-item -->
        </li>
    </ul>
</template>

<script>
export default {};
</script>

<style scoped>
</style>
