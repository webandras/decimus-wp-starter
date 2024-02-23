<?php
/**
 * Plugin Name: Decimus Category and Taxonomy Image
 * Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/decimus-taxonomy-image
 * Description: Decimus Category and Taxonomy Image Plugin allow you to add image with category/taxonomy. With REST API endpoint to get image from options table.
 * Version: 1.0.3
 * Author: András Gulácsi
 * Author URI: https://github.com/SalsaBoy990
 * License: MIT
 * Text Domain: decimus-taxonomy-image
 * Domain Path: /languages
 *
 * Original Plugin Name: Category and Taxonomy Image
 * Original Plugin URI: https://aftabhusain.wordpress.com/
 * Original Description: Category and Taxonomy Image Plugin allow you to add image with category/taxonomy.
 * Original Version: 1.0.0
 * Original Author: Aftab Husain
 * Original Author URI: https://aftabhusain.wordpress.com/
 * Original Author Email: amu02.aftab@gmail.com
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'plugins_loaded', function () {

	require_once __DIR__ . '/admin/Decimus_taxonomy_image.php';
	require_once __DIR__ . '/api/api.php';

//    register_uninstall_hook( __FILE__, 'Decimus_taxonomy_image::delete_plugin' );
} );

