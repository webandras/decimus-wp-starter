<?php

if ( ! function_exists( 'decimus_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function decimus_setup(): void {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Decimus, use a find and replace
		 * to change 'decimus' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'decimus', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}
endif;
add_action( 'after_setup_theme', 'decimus_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function decimus_content_width(): void {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'decimus_content_width', 640 );
}

add_action( 'after_setup_theme', 'decimus_content_width', 0 );


// Excerpt to pages
add_post_type_support( 'page', 'excerpt' );
// Excerpt to pages END

// Add <link rel=preload> to Fontawesome
add_filter( 'style_loader_tag', 'decimus_style_loader_tag' );

function decimus_style_loader_tag( $tag ) {

	$tag = preg_replace( "/id='font-awesome-css'/", "id='font-awesome-css' online=\"if(media!='all')media='all'\"",
		$tag );

	return $tag;
}

// Add <link rel=preload> to Fontawesome END


// Password protected form
function decimus_pw_form(): string {
	$output = '
		  <form action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass" method="post" class="form-inline">' . "\n"
	          . '<input name="post_password" type="password" size="" class="form-control me-2 my-1" placeholder="' . __( 'Password',
			'decimus' ) . '"/>' . "\n"
	          . '<input type="submit" class="btn btn-outline-primary my-1" name="Submit" value="' . __( 'Submit',
			'decimus' ) . '" />' . "\n"
	          . '</p>' . "\n"
	          . '</form>' . "\n";

	return $output;
}

add_filter( "the_password_form", "decimus_pw_form" );
// Password protected form END


// Allow HTML in term (category, tag) descriptions
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		add_filter( $filter, 'wp_filter_post_kses' );
	}
}

foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}
// Allow HTML in term (category, tag) descriptions END


// Allow HTML in author bio
remove_filter( 'pre_user_description', 'wp_filter_kses' );
add_filter( 'pre_user_description', 'wp_filter_post_kses' );
// Allow HTML in author bio END


// Hook after #primary
function decimus_after_primary(): void {
	do_action( 'decimus_after_primary' );
}

// Hook after #primary END


// Disable Gutenberg blocks in widgets (WordPress 5.8)
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
// Disables the block editor from managing widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
// Disable Gutenberg blocks in widgets (WordPress 5.8) END

/**
 * Check if class is activated
 */
if ( ! function_exists( 'is_class_activated' ) ) {
	function is_class_activated( string $class_name = 'Guland\DecimusAdmin\DecimusAdmin' ): bool {
		return class_exists( $class_name ?? '' );
	}
}

// Remove emoji support (for optimization purposes)
add_action( 'init', 'decimus_remove_emoji' );
function decimus_remove_emoji(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'decimus_remove_tinymce_emoji' );
}


// Filter out the tinymce emoji plugin.
function decimus_remove_tinymce_emoji( array $plugins ): array {

	if ( ! is_array( $plugins ) ) {
		return array();
	}

	return array_diff( $plugins, array( 'wpemoji' ) );
}

// Not sure if it is useful to set some headers here
// On shared hosting, there headers cannot be modified in webserver configuration
//add_filter('wp_headers', 'decimus_additional_headers');
function decimus_additional_headers( array $headers ): array {
	if ( ! is_admin() ) {

		$headers['Referrer-Policy']           = 'no-referrer-when-downgrade';
		$headers['X-Content-Type-Options']    = 'nosniff';
		$headers['X-XSS-Protection']          = '1; mode=block';
		$headers['X-Frame-Options']           = 'SAMEORIGIN';
		$headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
	}

	return $headers;
}
