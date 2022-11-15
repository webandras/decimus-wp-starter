<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">Admin Settings</h2>
        <AdminSettingsService/>
    </div>
</template>

<script>
import AdminSettingsService from "../services/AdminSettingsService.vue";

export default {
    name: "AdminSettingsPage",
    components: {
        AdminSettingsService,
    },
    data() {
        return {};
    },
};
</script>

<style scoped>
</style>
