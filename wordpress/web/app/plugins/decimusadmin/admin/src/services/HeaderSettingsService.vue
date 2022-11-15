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
                        >Show Account button</label
                        >

                        <label for="account_button">
                            <input
                                name="account_button"
                                v-model.number="settings.option_value.account_button"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable account button</span>
                        </label>
                    </p>
                    <p>
                        <label class="main-label"
                        >Show Cart button</label
                        >

                        <label for="cart_button">
                            <input
                                name="cart_button"
                                v-model.number="settings.option_value.cart_button"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable cart button</span>
                        </label>
                    </p>
                    <p>
                        <label class="main-label"
                        >Show Search button</label
                        >

                        <label for="search_button">
                            <input
                                name="search_button"
                                v-model.number="settings.option_value.search_button"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable search button</span>
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
    name: "HeaderSettingsService",
    mixins: [BaseServiceMixin],

    data() {
        return {
            route: 'header',
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
