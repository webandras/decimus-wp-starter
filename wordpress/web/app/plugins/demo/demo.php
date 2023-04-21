<?php
/*
Plugin Name:    Demo Plugin
Plugin URI:     https://decimus.hu
Description:    It is a demo plugin.
Version:        1.0.0
Author:         Gulácsi András
Author URI:     https://decimus.hu
Text Domain:    deci-demo
Domain Path:    /languages
*/

// insert this line of code inside all of your files to prevent usage without WP core
!defined( ABSPATH ) || exit;

if ( !class_exists( 'Deci_demo' ) ) {


    final class Deci_demo
    {
        private const TEXT_DOMAIN = 'deci-demo';

        private static $instance = null;


        private function __construct()
        {
            $this->initialize_hooks();
            $this->setup_database();
        }


        public static function get_instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }


        private function initialize_hooks(): void
        {
            add_action( 'wp_enqueue_scripts', function () {
                wp_enqueue_style( 'demo', plugins_url() . '/demo/public/assets/css/demo.css', array(), false, 'all' );
            } );

            if ( is_admin() ) {
                require_once __DIR__ . '/admin/admin.php';
            }

            require_once __DIR__ . '/public/public.php';

            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

        }


        public static function load_text_domain(): void
        {
            // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
            $domain = self::TEXT_DOMAIN;
            $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

            load_textdomain( $domain,
                trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
            load_plugin_textdomain( $domain, false, basename( dirname( __FILE__, 2 ) ) . '/languages/' );
        }


        private function setup_database(): void
        {
            require_once 'admin/Deci_database.php';

            register_activation_hook( __FILE__, [ 'Deci_database', 'setup' ] );

            // register_deactivation_hook();
            // register_uninstall_hook();

            add_action( 'wp_login', [ 'Deci_database', 'insert_record' ], 10, 2 );
        }
    }

}

// singleton instance
Deci_demo::get_instance();
