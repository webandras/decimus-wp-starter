<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/Vuecommerce_rest_extend.php';


if ( !class_exists( 'Vuecommerce_spa' ) ) {

    /**
     * Implements the backend for our Vue.js SPA
     */
    final class Vuecommerce_spa
    {

        private const TEXT_DOMAIN = 'vuecommerce';
        private const VERSION = '1.0.0';


        // class instance
        private static $instance;

        private $api;


        /**
         *
         * @return self $instance
         */
        public static function get_instance(): self
        {
            if ( self::$instance == null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        public function __construct()
        {
            // Load translation files
            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

            // SPA frontend scripts and css
            add_action( 'wp_enqueue_scripts', array( $this, 'add_vue_scripts' ) );

            //Add shortcode to WordPress
            add_shortcode( 'vuecommerce_filter_products', array( $this, 'vuecommerce_filter_products' ) );


            // Extend REST API for additional post meta
            $this->api = new Vuecommerce_rest_extend;
            add_action( 'rest_api_init', array( $this->api, 'extend_posts_api_response' ) );
        }


        public function __destruct()
        {
        }


        /** Load translations */
        public function load_text_domain(): void
        {
            // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
            $domain = self::TEXT_DOMAIN;
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

            load_textdomain( $domain,
                trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
            load_plugin_textdomain( $domain, false, basename( dirname( __FILE__, 2 ) ) . '/languages/' );
            load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
        }


        public function add_vue_scripts(): void
        {
            // register the Vue bundles for the vue page template only
            if ( is_page_template( 'page-vue.php' ) ) {

                // only compile to css in dev env
                if ( WP_ENV === 'development' ) {
                    wp_enqueue_script( 'vuecommerce-js-chunk-vendors', 'http://localhost:8081/js/chunk-vendors.js', [],
                        false, true );
                    wp_register_script( 'vuecommerce-js-app', 'http://localhost:8081/js/app.js', [], self::VERSION,
                        true );
                } else {
                    wp_enqueue_script( 'vuecommerce-js-chunk-vendors',
                        plugins_url() . '/vuecommerce/dist/js/chunk-vendors.47079796.js', [], self::VERSION, true );
                    wp_register_script( 'vuecommerce-js-app', plugins_url() . '/vuecommerce/dist/js/app.cfc97523.js',
                        [],
                        false, true );
                }

                // style.css
                wp_enqueue_style( 'vuecommerce-css-chunk-vendors',
                    plugins_url() . '/vuecommerce/dist/css/chunk-vendors.71a233fb.css' );
                wp_enqueue_style( 'vuecommerce-css-app', plugins_url() . '/vuecommerce/dist/css/app.d4e6fd0a.css' );

                // make custom data available for the Vue frontend with wp_localize_script.
                global $post;

                $post_categories = get_terms( array(
                    'taxonomy' => 'category', // default post categories.
                    'hide_empty' => true,
                    'fields' => 'all',
                ) );

                // get category image from options
                $post_category_images = [];
                $category_image_option_name_prefix = '_category_image-';
                foreach ( $post_categories as $category ) {
                    $image = get_option( $category_image_option_name_prefix . $category->term_id );
                    // only if there is an image attached to the category
                    if ( $image ) {

                        // the category's term_id as key is needed for identification
                        $post_category_images[$category->term_id] = $image;
                    }
                }

                // get product categories
                $product_categories = get_terms( array(
                    'taxonomy' => 'product_cat',
                    'orderby' => 'name',
                    'order' => 'asc',
                    'hide_empty' => true,
                    'fields' => 'all'
                ) );


                wp_localize_script(
                    'vuecommerce-js-app', // vue script handle defined in wp_register_script.
                    'wpData', // js object that will be made available for Vue.
                    array( // data to be made available for the Vue app in 'wpData'
                        'plugin_directory_uri' => plugin_dir_path( dirname( __FILE__, 2 ) ), // plugin directory path.
                        'rest_url' => untrailingslashit( esc_url_raw( rest_url() ) ), // URL to the REST endpoint.
                        'app_path' => $post->post_name, // page where the custom page template is loaded.
                        'post_categories' => $post_categories,
                        'post_category_images' => $post_category_images,
                        'product_categories' => $product_categories
                    )
                );

                // enqueue the Vue app script with localized data.
                wp_enqueue_script( 'vuecommerce-js-app' );
            }
        }


        // Add shortcode
        public function vuecommerce_filter_products(): string
        {
            // Vue code here will go here on the frontend
            return '<div id="vuecommerce-filter-products"></div>';
        }
    }

}
