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
                        <label for="email_address">Email Address</label><br/>
                        <input
                            type="email"
                            name="email_address"
                            v-model="settings.option_value.email_address"
                            class="regular-text"
                        />
                    </p>
                    <p>
                        <label for="phone_number">Phone Number</label><br/>
                        <input
                            type="tel"
                            name="phone_number"
                            v-model="settings.option_value.phone_number"
                            placeholder="+36201234567"
                            class="regular-text"
                        />
                    </p>
                    <p>
                        <label for="facebook">Facebook link</label><br/>
                        <input
                            type="text"
                            name="facebook"
                            v-model="settings.option_value.facebook"
                            placeholder="https://facebook.com/"
                            class="regular-text"
                        />
                    </p>
                    <p>
                        <label for="messenger">Messenger link</label><br/>
                        <input
                            type="text"
                            name="messenger"
                            v-model="settings.option_value.messenger"
                            placeholder="https://m.me/"
                            class="regular-text"
                        />
                    </p>
                    <p>
                        <label for="facebook">Instagram link</label><br/>
                        <input
                            type="text"
                            name="instagram"
                            v-model="settings.option_value.instagram"
                            placeholder="https://instagram.com/"
                            class="regular-text"
                        />
                    </p>
                    <p>
                        <label for="youtube">Youtube link</label><br/>
                        <input
                            type="text"
                            name="youtube"
                            v-model="settings.option_value.youtube"
                            placeholder="https://youtube.com/"
                            class="regular-text"
                        />
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
    name: "ContactSettingsService",
    mixins: [BaseServiceMixin],

    data() {
        return {
            route: 'contact',
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
