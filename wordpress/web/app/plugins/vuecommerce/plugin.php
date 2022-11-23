<?php

/**
 * Plugin Name: VueCommerce
 * Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter
 * Description: Vuecommerce Vue.js SPA for posts and products (filtering by categories and attributes, search, pagination) making requests through the WordPress and the Woocommerce REST API.
 * Version: 1.0.0
 * Author: SalsaBoy990
 * Author URI: https://github.com/SalsaBoy990
 * License: LGPL 3.0
 * Text Domain: vuecommerce
 * Domain Path: /lang
 */

// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;

// require all requires once
require_once __DIR__ . '/requires.php';

use Guland\VueCommerceBlocks\VueCommerceBlocks as VueCommerceBlocks;

add_action('plugins_loaded', function () {
    // instantiate main plugin singleton class
    VueCommerceBlocks::get_instance();
});
