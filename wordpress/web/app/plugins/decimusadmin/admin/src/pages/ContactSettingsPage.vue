<template>
    <div class="decimus-wrapper">
        <h2 class="sub-title">Contact</h2>
        <ContactSettingsService/>
    </div>
</template>

<script>
import ContactSettingsService from "../services/ContactSettingsService.vue";

export default {
    name: "ContactSettingsPage",
    components: {
        ContactSettingsService
    },
    data() {
        return {};
    },
};
</script>

<style scoped>
</style>
