<template>
    <div id="vue-admin-app">
        <div class="sidebar">
            <h3 class="sidebar-heading">Select sub-settings</h3>
            <AdminNavigation>
                <!-- Load the Admin Navigation Component-->
            </AdminNavigation>
        </div>
        <div class="main-content">
            <h1 class="main-content-heading">Decimus Theme Settings</h1>
            <keep-alive>
                <router-view>
                    <!-- Load the router-view component provided by the vue-router plugin -->
                </router-view>
            </keep-alive>
        </div>
    </div>
    <!-- #vue-admin-app -->
</template>

<script>
import AdminNavigation from "./components/global/AdminNavigation.vue";

export default {
    components: {
        AdminNavigation,
    },
};
</script>

<style>

:root {
    --lightgray: #d6d6d6;
    --fonts: Arial, Helvetica, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    --red: #f15145;
    --darkred: #cc453a;
    --snow: #e2e0e0
}

.toplevel_page_decimus-admin #wpwrap {
/*  height: 0;
  min-height: 0;*/
}

/* General styles */
.mb-20 {
    margin-bottom: 20px;
}

.mb-40 {
    margin-bottom: 40px;
}

.w-340 {
    max-width: 340px;
}

.w-500 {
    max-width: 500px;
}

.decimus-wrapper {
    position: relative;
}

#qib_notice_container {
    display: none;
}

.prompt-box,
.overlay,
.update-nag.notice {
    display: none;
}


/* Plugin styles */
.toplevel_page_decimus-admin .notice {
    display: none;
}

.toplevel_page_decimus-admin #vue-admin-app .notice {
    display: block;
    margin-left: 0;
    width: fit-content;
}

.toplevel_page_decimus-admin #vue-admin-app .notice > p {
    margin-top: 6px;
    margin-bottom: 4px;
}

.toplevel_page_decimus-admin #wpwrap {
    background: white;
}

.toplevel_page_decimus-admin #wpbody {
    background-color: white;
}

.toplevel_page_decimus-admin #wpcontent {
    padding-left: 0;
}

.toplevel_page_decimus-admin #wpbody-content {
    padding-bottom: 0;
}

.toplevel_page_decimus-admin #wpfooter {
    display: none;
}


/* Vue container .notice.notice-error */
#vue-admin-app {
    width: 100%;
    margin-top: -18.2px;
    display: grid;
    grid-template-columns: 270px 1fr;
    grid-template-rows: 1fr;
    grid-template-areas: "sidebar main-content";
    column-gap: 50px;
    height: 100vh;
}

/* Main sections */
#vue-admin-app .sidebar {
    width: 212px;
    padding-left: 24px;
    grid-area: sidebar;
    background-color: var(--lightgray);
    height: fit-content;
}

#vue-admin-app .main-content {
    grid-area: main-content;
    padding-bottom: 60px;
}

#vue-admin-app .main-content-heading {
    margin-top: 45px;
    margin-bottom: 56px;
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 24px;
    line-height: 28px;
    color: black;
}

#vue-admin-app .sidebar .sidebar-heading {
    margin-top: 53px;
    margin-bottom: 32px;
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: rgba(0, 0, 0, 0.6);
}


/* Content styles */
.decimus-wrapper p {
    font-size: 14px;
    font-family: var(--fonts);
}

.decimus-wrapper .black-text {
    font-weight: 500;
    color: black;
}

.decimus-wrapper .important {
    font-weight: 500;
    font-size: 13px;
    line-height: 150%;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: rgba(0, 0, 0, 0.7);
}

.decimus-wrapper small {
    position: absolute;
    bottom: -80px;
    left: 0;
    display: block;
}

.decimus-wrapper h3 {
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: black;
    margin-top: 25px;
    margin-bottom: 20px;
}

.decimus-wrapper h3 > span.small-text {
    font-family: var(--fonts);
    font-style: normal;
    font-weight: normal;
    font-size: 10px;
    line-height: 11px;
    letter-spacing: 0.02em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: black;
}

.decimus-wrapper h2.sub-title {
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: rgba(0, 0, 0, 0.6);
}

/* Form styles */
.decimus-wrapper form label {
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    text-transform: capitalize;
    font-feature-settings: "tnum" on, "lnum" on;
    color: black;
    padding-bottom: 4px;
}

.decimus-wrapper form label.main-label {
    display: block;
    font-weight: 600;
    padding-bottom: 12px;
    padding-top: 15px;
}

.decimus-wrapper form label span,
.decimus-wrapper form label[for="notification-enabled"] span,
.decimus-wrapper form label[for="social-share"] span {
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 16px;
    letter-spacing: 0.02em;
    color: black;
}

.decimus-wrapper form p {
    margin-top: 25px;
    margin-bottom: 20px;
}

.decimus-wrapper form textarea {
    display: block;
    margin-top: 13px;
}

.decimus-wrapper form .description {
    display: block;
    padding-top: 13px;
    font-family: var(--fonts);
    font-style: normal;
    font-weight: 500;
    font-size: 14px;
    line-height: 150%;
    letter-spacing: 0.03em;
    font-feature-settings: "tnum" on, "lnum" on;
    color: rgba(0, 0, 0, 0.6);
}


.decimus-wrapper form input.button-primary {
    margin-top: 10px;
    background: var(--red);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
    border-radius: 1px;
    height: 40px;
    width: 240px;
    text-align: center;
    border: none;
    font-weight: 500;
    font-size: 14px;
    line-height: 15px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    font-feature-settings: "tnum" on, "lnum" on;
    color: white;
}

.decimus-wrapper form input.button-primary:hover,
.decimus-wrapper form input.button-primary:focus {
    background: var(--darkred);
}

.decimus-wrapper form .form-group {
    padding: 0.5rem;
    margin-top: 1.5rem;
    margin-right: 1rem;
    border: 1px solid var(--snow);
    border-radius: 2px;
}

.decimus-wrapper form .form-group p {
    margin-top: 0;
}

.decimus-wrapper form textarea {
    display: block;
    margin-top: 13px;
}
</style>
