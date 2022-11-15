<template>
    <div>
        <div
            v-if="apiResponse && apiResponse.status === 200"
            role="alert"
            class="notice notice-success inline"
        >
            <p>
                {{ apiResponse.description }}
            </p>
        </div>
        <div
            v-else-if="apiResponse && apiResponse.status !== 200"
            class="notice notice-error inline"
            role="alert"
        >
            <p>
                {{ apiResponse.error }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Alert",
    props: {
        apiResponse: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style scoped>
</style>
