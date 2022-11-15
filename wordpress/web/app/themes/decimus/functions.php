<?php
/**
 * Decimus functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Decimus
 */

if ( !defined('ABSPATH') ) {
    exit;
}


// Register Bootstrap 5 Nav Walker
if ( !function_exists('decimus_register_navwalker') ) :
    function decimus_register_navwalker()
    {
        require_once('inc/class-bootstrap-5-navwalker.php');
        // Register Menus
        register_nav_menu('main-menu', 'Main menu');
        register_nav_menu('footer-menu', 'Footer menu');
    }
endif;
add_action('after_setup_theme', 'decimus_register_navwalker');
// Register Bootstrap 5 Nav Walker END


// Register Comment List
if ( !function_exists('decimus_register_comment_list') ) :
    function decimus_register_comment_list()
    {
        // Register Comment List
        require_once('inc/comment-list.php');
    }
endif;
add_action('after_setup_theme', 'decimus_register_comment_list');
// Register Comment List END


//Enqueue scripts and styles
function decimus_scripts(): void
{

    // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/bootstrap.min.css'));
    $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/fontawesome.min.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/theme.js'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/lib/bootstrap.bundle.min.js'));

    // Style CSS
    wp_enqueue_style('decimus-style', get_stylesheet_uri(), array(), $modificated);

    // Bootstrap
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/lib/bootstrap.min.css', array(), $modificated);

    // Fontawesome
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/css/lib/fontawesome.min.css', array(), $modificated);

    // Bootstrap JS
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/lib/bootstrap.bundle.min.js', array(), $modificated, true);

    // Theme JS
    wp_enqueue_script('decimus-script', get_template_directory_uri() . '/js/theme.js', array(), $modificated, true);

    if ( is_singular() && comments_open() && get_option('thread_comments') ) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'decimus_scripts');
//Enqueue scripts and styles END


/**
 * Theme settings
 */
require_once get_template_directory() . '/inc/theme.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom components
 */
require_once get_template_directory() . '/inc/components.php';

/**
 * Custom widgets
 */
require_once get_template_directory() . '/inc/widgets/post-archives.php';
require_once get_template_directory() . '/inc/widgets/post-categories.php';
require_once get_template_directory() . '/inc/widgets/post-tags.php';
require_once get_template_directory() . '/inc/widgets/recent-posts.php';
