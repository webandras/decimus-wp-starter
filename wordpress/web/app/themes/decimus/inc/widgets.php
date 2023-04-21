<?php


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
// Widgets
if ( ! function_exists( 'decimus_widgets_init' ) ) :

	function decimus_widgets_init(): void {

		// Top Nav
		register_sidebar( array(
			'name'          => esc_html__( 'Top Nav', 'decimus' ),
			'id'            => 'top-nav',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="ms-3">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title d-none">',
			'after_title'   => '</div>'
		) );
		// Top Nav End

		// Top Nav Search
		register_sidebar( array(
			'name'          => esc_html__( 'Top Nav Search', 'decimus' ),
			'id'            => 'top-nav-search',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="top-nav-search">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title d-none">',
			'after_title'   => '</div>'
		) );
		// Top Nav Search End

		// Sidebar
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'decimus' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s card card-body mb-1 border-0">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title card-title border-bottom py-2">',
			'after_title'   => '</h2>',
		) );
		// Sidebar End

		// Top Footer
		register_sidebar( array(
			'name'          => esc_html__( 'Top Footer', 'decimus' ),
			'id'            => 'top-footer',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="footer_widget mb-5">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
		// Top Footer End

		// Footer 1
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 1', 'decimus' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="footer_widget mb-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title h4">',
			'after_title'   => '</h2>'
		) );
		// Footer 1 End

		// Footer 2
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 2', 'decimus' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="footer_widget mb-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title h4">',
			'after_title'   => '</h2>'
		) );
		// Footer 2 End

		// Footer 3
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 3', 'decimus' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="footer_widget mb-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title h4">',
			'after_title'   => '</h2>'
		) );
		// Footer 3 End

		// Footer 4
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 4', 'decimus' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="footer_widget mb-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title h4">',
			'after_title'   => '</h2>'
		) );
		// Footer 4 End

		// 404 Page
		register_sidebar( array(
			'name'          => esc_html__( '404 Page', 'decimus' ),
			'id'            => '404-page',
			'description'   => esc_html__( 'Add widgets here.', 'decimus' ),
			'before_widget' => '<div class="mb-4">',
			'after_widget'  => '</div>',
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>'
		) );
		// 404 Page End

	}

	add_action( 'widgets_init', 'decimus_widgets_init' );


endif;
// Widgets END


// Shortcode in HTML-Widget
add_filter( 'widget_text', 'do_shortcode' );
// Shortcode in HTML-Widget End
