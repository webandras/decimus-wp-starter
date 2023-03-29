# VUECOMMERCE PLUGIN BY ANDRÁS GULÁCSI 2022

**TODO: migrate to Vue.js version 3**, because version 2 will reach its end-of-life at the end of this year!


## Setup Guide

1. Set `VUE_APP_REST_API_PATH` to your domain, set NODE_ENV in .env file. See example file.

2. Generate **customer key** (CK) and **secret** (CS) for WooCommerce (you can generate these at the plugin's settings).
   Also put it into .env.

3. In order to make calls to WooCommerce REST API endpoints with basic auth, **you need to enable https.
   WooCommerce enforces it.** Use the docker setup with custom domain and ssl certificate implementation.

4. Enable plugin in WP admin.

5. The theme should have a template (Example: **page-vue.php** in decimus theme folder) to be used exclusively for vuecommerce.
   Here, a shortcode is inserted where the root DOM element will be pasted and our SPA rendered from the virtual DOM.

```php
<?php echo do_shortcode('[vuecommerce_filter_products]'); ?>
```

6. Create a WordPress page on the CMS, and set it to use the `page-vue.php` template.

7. Go on with the rest of the default instructions below.

## Project setup

```
npm install
```

### Compiles and hot-reloads for development

```
npm run serve
```

It generally runs at `http://localhost:8081` because 8080 is already in use. if not, change in the
`Guland\DecimusAdmin\Core\AdminPage` trait's `add_admin_scripts()` method!

### Compiles and minifies for production

```
npm run build
```

Currently, the production bundles have hashes in the filenames, so you need to change it in the
`Guland\VueCommerceBlocks\VueCommerceBlocks` class's `add_vue_scripts()` method!

TODO 2: Vue.js 3 should have a vue.config.js file having **`filenameHashing` set to false** in order to disable the hashes.
This setup currently does not have a manual config.


### Lints and fixes files

```
npm run lint
```

### Customize configuration

See [Configuration Reference](https://cli.vuejs.org/config/).
