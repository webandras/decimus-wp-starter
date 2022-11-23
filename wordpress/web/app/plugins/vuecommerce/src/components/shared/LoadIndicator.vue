<template>
    <div class="mt-3 d-flex justify-content-center">
        <p class="text-center" v-html="apiResponse"/>
        <div class="spinner-border text-primary text-center" role="status">
            <span class="visually-hidden">{{ $t('shared.loading') }}</span>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        apiResponse: {
            type: String,
            required: true,
        },
    },
};
</script>

<style scoped>
</style>
