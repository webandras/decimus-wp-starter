# VUECOMMERCE PLUGIN BY ANDRÁS GULÁCSI 2022

## Setup Guide

1. Set `VUE_APP_REST_API_PATH` to your domain, set NODE_ENV in .env file. See example file.

2. Generate **customer key** (CK) and **secret** (CS) for WooCommerce (you can generate these at the plugin's settings).
   Also put it into .env.

3. In order to make calls to WooCommerce REST API endpoints with basic auth, you need to enable https.
   WooCommerce enforces it. Use the docker setup with custom domain and ssl certificate implementation.

4. Enable plugin in WP admin.

5. The theme should have a template (Example: page-vue.php) to be used exclusively for vuecommerce.
   Here, a shortcode is inserted where the root DOM element will be pasted and our SPA rendered from the virtual DOM.

6. Create a WordPress page on the CMS, and set it to use the page-vue.php template.

7. Go on with the rest of the default instructions below.

## Project setup

```
npm install
```

### Compiles and hot-reloads for development

```
npm run serve
```

### Compiles and minifies for production

```
npm run build
```

### Lints and fixes files

```
npm run lint
```

### Customize configuration

See [Configuration Reference](https://cli.vuejs.org/config/).
