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
                        <label class="main-label"
                        >Gutenberg block editor</label
                        >

                        <label for="enable_gutenberg_block_editor_in_widgets">
                            <input
                                name="enable_gutenberg_block_editor_in_widgets"
                                v-model.number="settings.option_value.enable_gutenberg_block_editor_in_widgets"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable Gutenberg block editor in widgets</span>
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
    name: "WooCommerceSettingsService",
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
