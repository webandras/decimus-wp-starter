<?php
/**
 * Plugin Name: Decimus Category and Taxonomy Image
 * Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/decimus-taxonomy-image
 * Description: Decimus Category and Taxonomy Image Plugin allow you to add image with category/taxonomy. With REST API endpoint to get image from options table.
 * Version: 1.0.2
 * Author: András Gulácsi
 * Author URI: https://github.com/SalsaBoy990
 * License: GPLv2
 * Text Domain: decimus-taxonomy-image
 * Domain Path: /lang
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
if ( !defined('ABSPATH') ) exit;

require_once __DIR__ . '/autoload.php';

use Guland\DecimusTaxonomyImage\DecimusTaxonomyImage as DecimusTaxonomyImage;

add_action('plugins_loaded', 'decimus_taxonomy_image_init', 0);
if ( !function_exists('decimus_taxonomy_image_init') ) {
    function decimus_taxonomy_image_init(): void
    {
        // instantiate singleton
        DecimusTaxonomyImage::get_instance();

        register_uninstall_hook(__FILE__, '\Gulacsi\DecimusTaxonomyImage\DecimusTaxonomyImage::delete_plugin');
    }
}
