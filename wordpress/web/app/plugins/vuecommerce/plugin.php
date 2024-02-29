<?php

/**
 * Plugin Name: VueCommerce
 * Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/vuecommerce
 * Description: Vuecommerce Vue.js SPA for posts and products (filtering by categories and attributes, search, pagination) making requests through the WordPress and the Woocommerce REST API.
 * Version: 1.0.1
 * Author: Gulácsi András
 * Author URI: https://github.com/SalsaBoy990
 * License: LGPL 3.0
 * Text Domain: vuecommerce
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/backend/VuecommerceSpa.php';

add_action( 'plugins_loaded', function () {
	VuecommerceSpa::get_instance();
} );
