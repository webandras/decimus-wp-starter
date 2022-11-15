<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">Header settings</h2>
        <HeaderSettingsService/>
    </div>
</template>

<script>
import HeaderSettingsService from "../services/HeaderSettingsService.vue";

export default {
    name: "HeaderSettingsPage",
    data() {
        return {};
    },
    components: {
        HeaderSettingsService,
    },
};
</script>

<style scoped>
</style>
