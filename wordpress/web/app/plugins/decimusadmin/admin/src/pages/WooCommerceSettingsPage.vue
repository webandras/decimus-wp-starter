<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">WooCommerce Settings</h2>
        <WooCommerceSettingsService/>
    </div>
</template>

<script>
import WooCommerceSettingsService from "../services/WooCommerceSettingsService.vue";

export default {
    name: "WooCommerceSettingsPage",
    components: {
        WooCommerceSettingsService,
    },
    data() {
        return {};
    },
};
</script>

<style scoped>
</style>
