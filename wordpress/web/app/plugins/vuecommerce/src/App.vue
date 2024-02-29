<template>
    <div id="vue-search-app">

        <AppNavigation>
            <!-- Load the App Navigation Component-->
        </AppNavigation>
        <keep-alive>
            <router-view>
                <!-- Load the router-view component provided by the vue-router plugin -->
            </router-view>
        </keep-alive>

    </div><!-- #vue-search-app -->
</template>

<script>
import AppNavigation from './components/global/AppNavigation';

export default {
    components: {
        AppNavigation
    }
};
</script>

<style>
#vue-search-app .input-filters .form-check.form-switch {
    padding-left: 2.5em !important;
}

#vue-search-app .card img.card-img-top {
    height: 300px;
    object-fit: cover;
}

</style>
