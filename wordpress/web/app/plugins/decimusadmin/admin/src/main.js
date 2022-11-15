import Vue from "vue";
import router from "@/app-routes";
import VueI18n from "vue-i18n";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faLink,
  faTimes,
  faSearch,
  faChevronDown,
  faChevronUp,
} from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

import App from "@/App.vue";

require("animate.css");

library.add(faLink, faTimes, faSearch, faChevronDown, faChevronUp);
Vue.component("font-awesome-icon", FontAwesomeIcon);

Vue.config.productionTip = false;

Vue.use(VueI18n);

// Ready translated locale messages
const messages = {
  en: {
    navigation: {},
  },
  hu: {
    navigation: {},
  }
};

const i18n = new VueI18n({
  locale: "hu", // set locale
  messages: messages, // set locale ui texts
});

new Vue({
  render: (h) => h(App),
  router,
  i18n,
}).$mount("#decimus-theme-admin");

