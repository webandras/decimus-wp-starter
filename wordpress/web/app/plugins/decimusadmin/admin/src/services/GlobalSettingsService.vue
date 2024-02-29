<template>
    <div>
        <form
            v-if="showSettings"
            method="post"
            v-on:submit="updateSettings($event)"
        >
            <div class="mb-20 w-500">

                <p>
                    <label for="menu-type">Theme Skin</label><br/>
                    <select
                        required
                        name="menu-type"
                        v-model="settings.option_value.skin"
                    >
                        <option
                            selected="selected"
                            :defaultValue="settings.option_value.skin"
                        >
                            {{ settings.option_value.skin || "Lux" }}
                        </option>
                        <!-- Bootswatch themes -->
                        <option value="cerulean">Cerulean</option>
                        <option value="cosmo">Cosmo</option>
                        <option value="cyborg">Cyborg</option>
                        <option value="darkly">Darkly</option>
                        <option value="decimus">Decimus (default)</option>
                        <option value="flatly">Flatly</option>
                        <option value="journal">Journal</option>
                        <option value="litera">Litera</option>
                        <option value="lumen">Lumen</option>
                        <option value="lux">Lux</option>
                        <option value="materia">Materia</option>
                        <option value="minty">Minty</option>
                        <option value="morph">Morph</option>
                        <option value="pulse">Pulse</option>
                        <option value="quartz">Quartz</option>
                        <option value="sandstone">Sandstone</option>
                        <option value="simplex">Simplex</option>
                        <option value="sketchy">Sketchy</option>
                        <option value="slate">Slate</option>
                        <option value="solar">Solar</option>
                        <option value="spacelab">Spacelab</option>
                        <option value="superhero">Superhero</option>
                        <option value="united">United</option>
                        <option value="vapor">Vapor</option>
                        <option value="yeti">Yeti</option>
                        <option value="zephyr">Zephyr</option>

                    </select>
                </p>

                <div class="form-group">
                    <p>
                        <label class="main-label"
                        >Scroll to top arrow</label
                        >

                        <label for="enable_scroll_to_top_arrow">
                            <input
                                name="enable_scroll_to_top_arrow"
                                v-model.number="settings.option_value.enable_scroll_to_top_arrow"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable the scroll to top arrow in footer</span>
                        </label>
                    </p>

                    <p>
                        <label class="main-label">Social Share Links</label>

                        <label for="enable_social_share_links">
                            <input
                                name="enable_social_share_links"
                                v-model.number="settings.option_value.enable_social_share_links"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable social share links component</span> </label
                        ><br/>

                        <span class="description"
                        >Some hints here...</span
                        >
                    </p>

                    <p>
                        <label class="main-label"
                        >Blog sidebar</label
                        >

                        <label for="enable_blog_sidebar">
                            <input
                                name="enable_blog_sidebar"
                                v-model.number="settings.option_value.enable_blog_sidebar"
                                type="checkbox"
                                value="1"
                            />
                            <span>Enable the blog sidebar</span>
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
    name: "GlobalSettingsService",
    mixins: [BaseServiceMixin],

    data() {
        return {
            route: 'global',
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
