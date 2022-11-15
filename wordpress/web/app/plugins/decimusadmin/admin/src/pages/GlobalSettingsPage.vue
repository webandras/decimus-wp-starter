<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">Global</h2>
        <GlobalSettingsService/>
    </div>
</template>

<script>
import GlobalSettingsService from "../services/GlobalSettingsService.vue";

export default {
    name: "GlobalSettingsPage",
    components: {
        GlobalSettingsService,
    },
    data() {
        return {};
    },
};
</script>

<style scoped>
</style>
