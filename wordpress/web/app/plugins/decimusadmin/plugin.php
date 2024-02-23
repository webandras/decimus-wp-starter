<?php

/**
 * Plugin Name: Decimus Theme Admin Plugin
 * Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/decimusadmin
 * Description: Decimus Theme Admin Plugin
 * Version: 1.0.0
 * Author: András Gulácsi
 * Author URI: https://github.com/SalsaBoy990
 * License: LGPL 3.0
 * Text Domain: decimus-admin
 * Domain Path: /lang
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// require all requires once
require_once __DIR__ . '/requires.php';

use Guland\DecimusAdmin\DecimusAdmin as DecimusAdmin;

//require_once __DIR__ . '/vendor/autoload.php';

add_action(
	'plugins_loaded',
	function () {
		// instantiate singleton instance
		DecimusAdmin::getInstance();
	},
	0
);

// migrate db, seed it (seed will only run the first time the plugin is activated)
register_activation_hook( __FILE__, '\Guland\DecimusAdmin\DecimusAdmin::activate_plugin' );

// delete db options when uninstalling the plugin
register_uninstall_hook( __FILE__, '\Guland\DecimusAdmin\DecimusAdmin::uninstall_plugin' );
