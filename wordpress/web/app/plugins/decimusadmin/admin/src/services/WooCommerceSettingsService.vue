<template>
    <div>
        <form
            v-if="showSettings"
            method="post"
            v-on:submit="updateSettings($event)"
        >
            <div class="mb-20">

                <div class="form-group w-500">

                    <p>
                        <label class="main-label">Single Product Metadata</label>

                        <label for="show_single_product_meta">
                            <input
                                name="show_single_product_meta"
                                v-model.number="settings.option_value.show_single_product_meta"
                                type="checkbox"
                                value="1"
                            />
                            <span>Show single product metadata</span>
                        </label>
                    </p>
                    <p>
                        <label class="main-label">Product Videos</label>

                        <label for="enable_product_videos">
                            <input
                                name="enable_product_videos"
                                v-model.number="settings.option_value.enable_product_videos"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable product videos</span>
                        </label>
                    </p>


                </div>
            </div>

            <Alert
                v-show="showAlertSettings && !showSettings"
                :apiResponse="apiResponse"
            />
            <Alert v-if="showAlertSettings" :apiResponse="apiResponse"/>

            <input
                v-if="!areSettingsUpdated"
                class="button-primary"
                type="submit"
                name="save-settings"
                value="SAVE SETTINGS"
            />
            <SavingBtn v-else/>
        </form>
        <Loader v-else/>
    </div>
</template>

<script>

import Loader from "../components/shared/Loader.vue";
import Alert from "../components/shared/Alert.vue";
import SavingBtn from "../components/shared/SavingBtn.vue";
import BaseServiceMixin from "../mixins/BaseServiceMixin";

export default {
    name: "AdminSettingsService",
    mixins: [BaseServiceMixin],

    data() {
        return {
            route: 'woocommerce',
        };
    },

    components: {
        Loader,
        Alert,
        SavingBtn,
    },

    mounted() {
        this.getSettings();
    },

};
</script>

<style scoped>
</style>
