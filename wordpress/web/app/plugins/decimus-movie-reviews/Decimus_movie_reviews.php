<?php

! defined( 'ABSPATH' ) && exit;

if ( ! class_exists( 'Decimus_movie_reviews' ) ) {

	final class Decimus_movie_reviews {

		private static $instance;

		public const FIELD_PREFIX = 'decimr_';

		private const CPT_SLUG = 'movie_review';

		private const TEXT_DOMAIN = 'decimus-movie-reviews';


		public static function get_instance(): self {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}


		private function __construct() {

			// initialize the custom taxonomy
			add_action( 'init', 'Decimus_movie_reviews::register_taxonomies' );

			// initialize Movie Review custom post type
			add_action( 'init', 'Decimus_movie_reviews::register_post_type' );

			// initialize widgets
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );


			// add admin menu item
			add_filter( 'admin_menu', array( $this, 'admin_menu' ) );

			// create settings for the plugin (uses the options "api")
			add_filter( 'admin_init', array( $this, 'create_settings' ) );


			// initialize custom fields from Metabox.io:
			// first check for required plugin Meta Box
			add_action( 'tgmpa_register', array( $this, 'check_required_plugins' ) );

			// then define the fields
			add_filter( 'rwmb_meta_boxes', array( $this, 'metabox_custom_fields' ) );

			// custom template for single CPT page
			add_action( 'template_include', array( $this, 'add_cpt_template' ) );

			// frontend styles and javascript
			add_action( 'wp_enqueue_scripts', array( $this, 'add_styles_scripts' ) );
		}


		/**
		 * Registers the Movie Review custom post type
		 *
		 * Defined statically for use in activation hook
		 */
		public static function register_post_type(): void {

			// Set up the arguments for the post type.
			$args = array(

				// A short description of what your post type is. As far as I know, this isn't used anywhere
				// in core WordPress.  However, themes may choose to display this on post type archives.
				'description'         => __( 'Movie reviews post type.', self::TEXT_DOMAIN ), // string

//				'taxonomies'          => array('category', 'post_tag'), // default for posts
				'taxonomies'          => array( 'movie_type' ),

				// Whether the post type should be used publicly via the admin or by front-end users.  This
				// argument is sort of a catchall for many of the following arguments.  I would focus more
				// on adjusting them to your liking than this argument.
				'public'              => true, // bool (default is FALSE)

				// Whether queries can be performed on the front end as part of parse_request().
				'publicly_queryable'  => true, // bool (defaults to 'public').

				// Whether to exclude posts with this post type from front end search results.
				'exclude_from_search' => false, // bool (defaults to the opposite of 'public' argument)

				// Whether individual post type items are available for selection in navigation menus.
				'show_in_nav_menus'   => false, // bool (defaults to 'public')

				// Whether to generate a default UI for managing this post type in the admin. You'll have
				// more control over what's shown in the admin with the other arguments.  To build your
				// own UI, set this to FALSE.
				'show_ui'             => true, // bool (defaults to 'public')

				// Whether to show post type in the admin menu. 'show_ui' must be true for this to work.
				// Can also set this to a string of a top-level menu (e.g., 'tools.php'), which will make
				// the post type screen be a sub-menu.
				'show_in_menu'        => true, // bool (defaults to 'show_ui')

				// Whether to make this post type available in the WordPress admin bar. The admin bar adds
				// a link to add a new post type item.
				'show_in_admin_bar'   => true, // bool (defaults to 'show_in_menu')

				// The position in the menu order the post type should appear. 'show_in_menu' must be true
				'menu_position'       => 4, // int (defaults to 25 - below comments)

				// The URI to the icon to use for the admin menu item or a dashicon class. See:
				// https://developer.wordpress.org/resource/dashicons/
//				'menu_icon'           => null, // string (defaults to use the post icon)
				'menu_icon'           => 'dashicons-format-video', // string (defaults to use the post icon)

				// Whether the posts of this post type can be exported via the WordPress import/export plugin
				// or a similar plugin.
				'can_export'          => true, // bool (defaults to TRUE)

				// Whether to delete posts of this type when deleting a user who has written posts.
				'delete_with_user'    => false, // bool (defaults to TRUE if the post type supports 'author')

				// Whether this post type should allow hierarchical (parent/child/grandchild/etc.) posts.
				'hierarchical'        => false, // bool (defaults to FALSE)

				// Whether the post type has an index/archive/root page like the "page for posts" for regular
				// posts. If set to TRUE, the post type name will be used for the archive slug.  You can also
				// set this to a string to control the exact name of the archive slug.
				'has_archive'         => 'movie-review', // bool|string (defaults to FALSE)

				// Sets the query_var key for this post type. If set to TRUE, the post type name will be used.
				// You can also set this to a custom string to control the exact key.
				'query_var'           => true, // bool|string (defaults to TRUE - post type name)

				// A string used to build the edit, delete, and read capabilities for posts of this type. You
				// can use a string or an array (for singular and plural forms).  The array is useful if the
				// plural form can't be made by simply adding an 's' to the end of the word.  For example,
				// array( 'box', 'boxes' ).
//				'capability_type'     => 'example', // string|array (defaults to 'post')

				// Whether WordPress should map the meta capabilities (edit_post, read_post, delete_post) for
				// you.  If set to FALSE, you'll need to roll your own handling of this by filtering the
				// 'map_meta_cap' hook.
//				'map_meta_cap'        => true, // bool (defaults to FALSE)

				// Provides more precise control over the capabilities than the defaults.  By default, WordPress
				// will use the 'capability_type' argument to build these capabilities.  More often than not,
				// this results in many extra capabilities that you probably don't need.  The following is how
				// I set up capabilities for many post types, which only uses three basic capabilities you need
				// to assign to roles: 'manage_examples', 'edit_examples', 'create_examples'.  Each post type
				// is unique though, so you'll want to adjust it to fit your needs.
//				'capabilities'        => array(
//
//					// meta caps (don't assign these to roles)
//					'edit_post'              => 'edit_example',
//					'read_post'              => 'read_example',
//					'delete_post'            => 'delete_example',
//
//					// primitive/meta caps
//					'create_posts'           => 'create_examples',
//
//					// primitive caps used outside of map_meta_cap()
//					'edit_posts'             => 'edit_examples',
//					'edit_others_posts'      => 'manage_examples',
//					'publish_posts'          => 'manage_examples',
//					'read_private_posts'     => 'read',
//
//					// primitive caps used inside of map_meta_cap()
//					'read'                   => 'read',
//					'delete_posts'           => 'manage_examples',
//					'delete_private_posts'   => 'manage_examples',
//					'delete_published_posts' => 'manage_examples',
//					'delete_others_posts'    => 'manage_examples',
//					'edit_private_posts'     => 'edit_examples',
//					'edit_published_posts'   => 'edit_examples'
//				),

				// How the URL structure should be handled with this post type.  You can set this to an
				// array of specific arguments or true|false.  If set to FALSE, it will prevent rewrite
				// rules from being created.
				'rewrite'             => array(

					// The slug to use for individual posts of this type.
					'slug'       => 'movie-review', // string (defaults to the post type name)

					// Whether to show the $wp_rewrite->front slug in the permalink.
					'with_front' => false, // bool (defaults to TRUE)

					// Whether to allow single post pagination via the <!--nextpage--> quicktag.
					'pages'      => true, // bool (defaults to TRUE)

					// Whether to create pretty permalinks for feeds.
					'feeds'      => true, // bool (defaults to the 'has_archive' argument)

					// Assign an endpoint mask to this permalink.
					'ep_mask'    => EP_PERMALINK, // const (defaults to EP_PERMALINK)
				),

				// What WordPress features the post type supports.  Many arguments are strictly useful on
				// the edit post screen in the admin.  However, this will help other themes and plugins
				// decide what to do in certain situations.  You can pass an array of specific features or
				// set it to FALSE to prevent any features from being added.  You can use
				// add_post_type_support() to add features or remove_post_type_support() to remove features
				// later.  The default features are 'title' and 'editor'.
				'supports'            => array(

					// Post titles ($post->post_title).
					'title',

					// Post content ($post->post_content).
					'editor',

					// Post excerpt ($post->post_excerpt).
					'excerpt',

					// Post author ($post->post_author).
					'author',

					// Featured images (the user's theme must support 'post-thumbnails').
					'thumbnail',

					// Displays comments meta box.  If set, comments (any type) are allowed for the post.
					'comments',

					// Displays meta box to send trackbacks from the edit post screen.
					'trackbacks',

					// Displays the Custom Fields meta box. Post meta is supported regardless.
					'custom-fields',

					// Displays the Revisions meta box. If set, stores post revisions in the database.
					'revisions',

					// Displays the Attributes meta box with a parent selector and menu_order input box.
					'page-attributes',

					// Displays the Format meta box and allows post formats to be used with the posts.
					'post-formats',
				),

				// Labels used when displaying the posts in the admin and sometimes on the front end.  These
				// labels do not cover post updated, error, and related messages.  You'll need to filter the
				// 'post_updated_messages' hook to customize those.
				'labels'              => array(
					'name'                  => __( 'Movie Reviews', self::TEXT_DOMAIN ),
					'singular_name'         => __( 'Movie Review', self::TEXT_DOMAIN ),
					'menu_name'             => __( 'Movie Reviews', self::TEXT_DOMAIN ),
					'name_admin_bar'        => __( 'Movie reviews', self::TEXT_DOMAIN ),
					'add_new'               => __( 'Add New', self::TEXT_DOMAIN ),
					'add_new_item'          => __( 'Add New Movie Review', self::TEXT_DOMAIN ),
					'edit_item'             => __( 'Edit Movie Review', self::TEXT_DOMAIN ),
					'new_item'              => __( 'New Movie Review', self::TEXT_DOMAIN ),
					'view_item'             => __( 'View Movie Review', self::TEXT_DOMAIN ),
					'search_items'          => __( 'Search Movie Reviews', self::TEXT_DOMAIN ),
					'not_found'             => __( 'No movie reviews found', self::TEXT_DOMAIN ),
					'not_found_in_trash'    => __( 'No movie reviews found in trash', self::TEXT_DOMAIN ),
					'all_items'             => __( 'All Movie Reviews', self::TEXT_DOMAIN ),
					'featured_image'        => __( 'Featured Image', self::TEXT_DOMAIN ),
					'set_featured_image'    => __( 'Set featured image', self::TEXT_DOMAIN ),
					'remove_featured_image' => __( 'Remove featured image', self::TEXT_DOMAIN ),
					'use_featured_image'    => __( 'Use as featured image', self::TEXT_DOMAIN ),
					'insert_into_item'      => __( 'Insert into movie review', self::TEXT_DOMAIN ),
					'uploaded_to_this_item' => __( 'Uploaded to this movie review', self::TEXT_DOMAIN ),
					'views'                 => __( 'Filter movie reviews list', self::TEXT_DOMAIN ),
					'pagination'            => __( 'Movie Reviews list navigation', self::TEXT_DOMAIN ),
					'list'                  => __( 'Movie Reviews list', self::TEXT_DOMAIN ),

					// Labels for hierarchical post types only.
					'parent_item'           => __( 'Parent Movie Review', self::TEXT_DOMAIN ),
					'parent_item_colon'     => __( 'Parent Movie Review:', self::TEXT_DOMAIN ),
				)
			);


			register_post_type( self::CPT_SLUG, $args );
		}


		/**
		 * Registers the Movie Review custom post type
		 *
		 * Defined statically for use in activation hook
		 */
		public static function register_taxonomies(): void {

			// Set up the arguments for the taxonomy
			$labels = array(
				'name'              => _x( 'Movie Types', self::TEXT_DOMAIN ),
				'singular_name'     => _x( 'Movie Type', self::TEXT_DOMAIN ),
				'search_items'      => __( 'Search Movie Types', self::TEXT_DOMAIN ),
				'all_items'         => __( 'All Movie Types', self::TEXT_DOMAIN ),
				'view_item'         => __( 'View Movie Type', self::TEXT_DOMAIN ),
				'parent_item'       => __( 'Parent Movie Type', self::TEXT_DOMAIN ),
				'parent_item_colon' => __( 'Parent Movie Type:', self::TEXT_DOMAIN ),
				'edit_item'         => __( 'Edit Movie Type', self::TEXT_DOMAIN ),
				'update_item'       => __( 'Update Movie Type', self::TEXT_DOMAIN ),
				'add_new_item'      => __( 'Add New Movie Type', self::TEXT_DOMAIN ),
				'new_item_name'     => __( 'New Movie Type Name', self::TEXT_DOMAIN ),
				'not_found'         => __( 'No Movie Types Found', self::TEXT_DOMAIN ),
				'back_to_items'     => __( 'Back to Movie Types', self::TEXT_DOMAIN ),
				'menu_name'         => __( 'Movie Type', self::TEXT_DOMAIN ),
			);

			$args = array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'movie-types' ),
//				'rewrite'           => false,
				'show_in_rest'      => true,
			);


			register_taxonomy( 'movie_types', array( self::CPT_SLUG ), $args );
		}


		/**
		 * Activation hook (see register_activation_hook)
		 */
		public static function activate(): void {

			self::register_taxonomies();
			self::register_post_type();
			flush_rewrite_rules();

		}


		/**
		 * Implementation of the TGM Plugin Activation library
		 *
		 * Checks for the plugin(s) we need, and displays the appropriate messages
		 */
		public function check_required_plugins(): void {
			$plugins = array(
				array(
					'name'               => 'Meta Box',
					'slug'               => 'meta-box',
					'required'           => true,
					'force_activation'   => false,
					'force_deactivation' => false,
				),
			);

			$config = array(
				'domain'       => 'decimus_movie_reviews',
				'default_path' => '',
				'parent_slug'  => 'plugins.php',
				'capability'   => 'update_plugins',
				'menu'         => 'install-required-plugins',
				'has_notices'  => true,
				'is_automatic' => false,
				'message'      => '',
				'strings'      => array(
					'page_title' => __( 'Install Required Plugins', self::TEXT_DOMAIN ),
					'menu_title' => __( 'Install Plugins', self::TEXT_DOMAIN ),
					'installing' => __( 'Installing Plugin: %s', self::TEXT_DOMAIN ),
					'oops'       => __( 'Something went wrong with the plugin API.', self::TEXT_DOMAIN ),

					'notice_can_install_required' => _n_noop( 'The Movie Reviews plugin depends on the following plugin: %1$s.',
						'The Movie Reviews plugin depends on the following plugins: %1$s.', self::TEXT_DOMAIN ),

					'notice_can_install_recommended' => _n_noop( 'The Movie Reviews plugin recommends the following plugin: %1$s.',
						'The Movie Reviews plugin recommends the following plugins: %1$s.', self::TEXT_DOMAIN ),

					'notice_cannot_install' => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.',
						'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.',
						self::TEXT_DOMAIN ),

					'notice_can_activate_required' => _n_noop( 'The following required plugin is currently inactive: %1$s.',
						'The following required plugins are currently inactive: %1$s.', self::TEXT_DOMAIN ),

					'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.',
						'The following recommended plugins are currently inactive: %1$s.', self::TEXT_DOMAIN ),

					'notice_cannot_activate' => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.',
						'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.',
						self::TEXT_DOMAIN ),

					'notice_ask_to_update' => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
						'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
						self::TEXT_DOMAIN ),

					'notice_cannot_update' => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.',
						'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.',
						self::TEXT_DOMAIN ),

					'install_link' => _n_noop( 'Begin installing plugin',
						'Begin installing plugins', self::TEXT_DOMAIN ),

					'activate_link' => _n_noop( 'Activate installed plugin',
						'Activate installed plugins', self::TEXT_DOMAIN ),

					'return'           => __( 'Return to Required Plugins Installer',
						self::TEXT_DOMAIN ),
					'plugin_activated' => __( 'Plugin activated successfully.',
						self::TEXT_DOMAIN ),
					'complete'         => __( 'All plugins installed and activated successfully. %s',
						self::TEXT_DOMAIN ),
					'nag_type'         => 'updated',
				)
			);
			tgmpa( $plugins, $config );
		}


		/**
		 * Create custom fields using metabox.io
		 */
		public function metabox_custom_fields(): array {

			// define the movie custom fields
			$meta_boxes[] = array(
				'id'       => 'movie_data',
				'title'    => 'Additional Information',
				'pages'    => array( self::CPT_SLUG ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name' => 'Release Year',
						'desc' => 'Year the movie was released',
						'id'   => self::FIELD_PREFIX . 'movie_year',
						'type' => 'number',
						'std'  => date( 'Y' ),
						'min'  => '1896',
					),
					array(
						'name' => 'Director',
						'desc' => 'Who directed this movie',
						'id'   => self::FIELD_PREFIX . 'movie_director',
						'type' => 'text',
						'std'  => '',
					),
					array(
						'name' => 'IMDB Link',
						'desc' => 'Link for this movie on IMDB',
						'id'   => self::FIELD_PREFIX . 'movie_imdb',
						'type' => 'url',
						'std'  => '',
					),
				)
			);


			$rating_options = get_option( self::FIELD_PREFIX . 'rating_options' );

			if ( empty( $rating_options ) ) {

				$rating_options = $this->get_default_rating_options();

			}

			// define the review custom field(s)
			$meta_boxes[] = array(
				'id'       => 'review_data',
				'title'    => 'Review',
				'pages'    => array( self::CPT_SLUG ),
				'context'  => 'side',
				'priority' => 'high',
				'fields'   => array(
					array(
						'name'    => 'Rating',
						'desc'    => 'On a scale of 1 - 10, 10 being best',
						'id'      => self::FIELD_PREFIX . 'review_rating',
						'type'    => 'select',
						'options' => $this->format_rating_options( $rating_options ),
						'std'     => '',
					),
				)
			);

			return $meta_boxes;
		}


		public function add_cpt_template( $template ) {

			if ( is_singular( self::CPT_SLUG ) ) {
				// check the active theme dir
/*				if ( file_exists( get_stylesheet_directory() . '/single-movie-review.php' ) ) {
					return get_stylesheet_directory() . '/single-movie-review.php';
				}*/

				return plugin_dir_path( __FILE__ ) . 'template/single-movie-review.php';
			}

			return $template;
		}


		public function add_styles_scripts(): void {

			if ( is_singular( self::CPT_SLUG ) ) {
				wp_enqueue_style( 'decimus-movie-reviews-css',
					plugin_dir_url( __FILE__ ) . 'assets/movie-reviews.css' );
			}

		}


		public function register_widgets(): void {

			register_widget( 'Decimus_Widget_Recent_Movie_Reviews' );

		}


		public function admin_menu(): void {

			add_submenu_page(
				'edit.php?post_type=movie_review',
				__( 'Movie Review Options', self::TEXT_DOMAIN ),
				__( 'Settings', self::TEXT_DOMAIN ),
				'manage_options',
				'movie_review_options',
				array( $this, 'options_page' )
			);
		}


		public function options_page(): void {

			$page_title = __( 'Movie Review Options', self::TEXT_DOMAIN );
			echo <<<start
<div class="wrap">
	<h1>$page_title</h1>
<form method="post" action="options.php">
start;

			do_action( 'decimus_custom_action_hook' );

			// insert content here
			settings_fields( self::FIELD_PREFIX . 'movie_review' );
			do_settings_sections( 'movie_review_options' );

			submit_button();

			echo '</form></div>';

		}


		public function create_settings(): void {

			register_setting(
				self::FIELD_PREFIX . 'movie_review',
				self::FIELD_PREFIX . 'rating_options',
				null
			);

			add_settings_section(
				self::FIELD_PREFIX . 'movie_review_options',
				'Teszt',
				function () {
					printf( '<p>%s</p>', __( 'Some content', self::TEXT_DOMAIN ) );
				},
				'movie_review_options'
			);

			for ( $i = 1; $i <= 10; $i ++ ) {
				add_settings_field(
					'rating_option_' . $i,
					__( 'Rating ', self::TEXT_DOMAIN ) . $i . ':',
					array( $this, 'setting_rating_options' ),
					'movie_review_options',
					self::FIELD_PREFIX . 'movie_review_options',
					array( $i )
				);

			}

		}


		/*
		 * Set up form field for the rating options
		 * */
		public function setting_rating_options( $args ): void {

			$id          = absint( array_pop( $args ) );
			$option_name = self::FIELD_PREFIX . 'rating_options';

			$options = get_option( $option_name );
			if ( empty( $options[ $id ] ) ) {
				$default_rating_options = $this->get_default_rating_options();
				$option_value           = $default_rating_options[ $id ];
			} else {
				$option_value = $options[ $id ];
			}


			echo '<input class="widefat" name="' . $option_name . '[' . $id . ']" type="text" value="' . esc_attr( $option_value ) . '" />';
		}


		/**
		 * Helper function that sets the default text for numerical ratings
		 *
		 * @return array
		 */
		private function get_default_rating_options(): array {
			return array(
				1  => __( 'I walked out. And I was home!', self::TEXT_DOMAIN ),
				2  => __( 'I will never get those hours back', self::TEXT_DOMAIN ),
				3  => __( 'Not recommended', self::TEXT_DOMAIN ),
				4  => __( 'Might stay awake on an airplane for it', self::TEXT_DOMAIN ),
				5  => __( 'As they say on the internet: meh', self::TEXT_DOMAIN ),
				6  => __( 'Totally decent', self::TEXT_DOMAIN ),
				7  => __( 'Quite good. Recommended.', self::TEXT_DOMAIN ),
				8  => __( 'One of my favorites of the last X years', self::TEXT_DOMAIN ),
				9  => __( 'Loved it, and you probably will too.', self::TEXT_DOMAIN ),
				10 => __( "Life changing! Mine, yours, everyone's!", self::TEXT_DOMAIN ),
			);
		}


		/**
		 * Helper function that takes an array of rating options (keys 1 - 10)
		 * and formats them how we'd like them for the custom field
		 *
		 * @param $options
		 *
		 * @return array
		 */
		private function format_rating_options( $options ): array {
			$formatted = array(
				'' => __( 'TBR (To be rated)', self::TEXT_DOMAIN )
			);

			foreach ( $options as $key => $val ) {
//				$formatted[ $key ] = $key . ' - ' . __( $val );
				$formatted[ $key ] = sprintf( __( '%1d - %2s', self::TEXT_DOMAIN ), $key, $val );
			}

			return $formatted;
		}


		private function sanitize_options( $opts ): string {
			return sanitize_text_field( $opts );
		}


	}


}

Decimus_movie_reviews::get_instance();
