import axios from "axios";

export default {
    data: () => ({
        settings: {}, // store all options state
        /* eslint-disable no-undef */
        decimusAdminData,

        // Control notification alerts after request
        apiResponse: {},
        areSettingsUpdated: false,
        showSettings: false,
        showAlertSettings: false,

        namespace: "decimus/v1/admin",
        route: "global",
    }),
    methods: {

        /**
         * Show then hide alert after a timeout
         */
        showAlertCallback(delay = 3000) {
            this.showAlertSettings = true;
            const successTimeout = setTimeout(() => {
                //this.showAlertSettings = false;
                this.apiResponse = {};
                clearTimeout(successTimeout);
            }, delay);
        },


        /**
         * GET decimus/v1/admin/ROUTE
         */
        async getSettings() {
            try {
                //const restURL = process.env.NODE_ENV === 'development' ? process.env.VUE_APP_REST_API_PATH : this.wpData.rest_url;
                const restURL = this.decimusAdminData.rest_url;
                const decimusNonce = this.decimusAdminData.decimus_nonce;
                // `headers` are custom headers to be sent
                const headers = {"X-WP-Nonce": decimusNonce};

                // send an initial request and await the response
                const response = await axios(`${restURL}/${this.namespace}/${this.route}`, {
                    headers,
                });

                const responseData = response.data;
                console.log(responseData);

                if (responseData.status === 200) {
                    // get the data
                    this.settings = responseData.data;
                    console.log(this.settings);

                    // re-initialize value
                    this.areSettingsUpdated = false;
                    this.showSettings = true;
                } else {
                    this.apiResponse = response.data;
                    this.showAlertCallback();
                }
            } catch (error) {
                this.apiResponse = {error};
                this.areSettingsUpdated = false;
                this.showAlertCallback();
            }
        },


        /**
         * PUT decimus/v1/admin/ROUTE
         */
        async updateSettings(event) {

            console.log({event });
            event.preventDefault();
            try {
                this.areSettingsUpdated = true;

                console.log(this.settings.option_value);

                //const restURL = process.env.NODE_ENV === 'development' ? process.env.VUE_APP_REST_API_PATH : this.wpData.rest_url;
                /* eslint-disable no-unused-vars */
                const restURL = this.decimusAdminData.rest_url;
                /* eslint-disable no-unused-vars */
                const path = `${this.namespace}/${this.route}`;
                const newSettings = this.settings.option_value;

                console.log(JSON.stringify(newSettings));

                const decimusNonce = this.decimusAdminData.decimus_nonce;
                // `headers` are custom headers to be sent
                const headers = {"X-WP-Nonce": decimusNonce};

                // send request and await the response
                const response = await axios.put(
                    `${restURL}/${path}`,
                    JSON.stringify(newSettings),
                    {headers}
                );
                this.apiResponse = response.data;

                console.log(this.apiResponse);

                // hide saving button, show alert for a timeout
                this.areSettingsUpdated = false;
                this.showAlertCallback();
            } catch (error) {
                this.apiResponse = {error};
                this.areSettingsUpdated = false;
                this.showAlertCallback();
            }
        },


    },

}

