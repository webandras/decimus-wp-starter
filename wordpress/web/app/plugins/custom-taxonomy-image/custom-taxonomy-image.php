<?php
/**
 * Plugin Name: Custom Category and Taxonomy Image
 * Plugin URI: https://andrasgulacsi.hu
 * Description: Custom Category and Taxonomy Image Plugin allow you to add image with category/taxonomy.
 * With REST API endpoint to get image from options table.
 * Version: 1.0.1
 * Author: András Gulácsi
 * Author URI: https://decimus.hu
 * License: GPLv2
 * Text Domain: custom-taxonomy-image
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

// require all requires once
require_once __DIR__ . '/requires.php';

use Guland\CustomTaxonomyImage\CustomTaxonomyImage as CustomTaxonomyImage;

add_action('plugins_loaded', 'custom_taxonomy_image_init', 0);
if ( !function_exists('custom_taxonomy_image_init') ) {
    function custom_taxonomy_image_init(): void
    {
        // instantiate main plugin singleton class
        CustomTaxonomyImage::get_instance();
    }
}
