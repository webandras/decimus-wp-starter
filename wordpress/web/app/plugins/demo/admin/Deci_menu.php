<?php

!defined( ABSPATH ) || die();

if ( !class_exists( 'Deci_menu' ) ) {


    final class Deci_menu
    {

        // Add option to store plugin settings
        public static function register_demo_settings(): void
        {
            register_setting( 'deci-demo-group', 'demo-option' );
        }


        // Define plugin menus
        public static function add_demo_menu(): void
        {

            // Main menu page
            add_menu_page(
                __( 'Demo Page', 'deci-demo' ),
                __( 'Demo Page', 'deci-demo' ),
                'manage_options',
                'deci_demo',
                [ 'Deci_menu', 'add_menu_item' ],
                'dashicons-tide',
                100
            );

            // Submenu
            add_submenu_page(
                'deci_demo',
                __( 'Demo Submenu', 'deci-demo' ),
                __( 'Demo Submenu', 'deci-demo' ),
                'administrator',
                'deci_demo_sub',
                [ 'Deci_menu', 'add_submenu_content' ],
                1
            );

        }


        // Main menu content callback
        public static function add_menu_item(): void
        {
            echo 'Hello world';
        }


        // Submenu content callback
        public static function add_submenu_content(): void
        {

            wp_enqueue_script(
                'deci-demo-admin-ajax-js',
                plugins_url() . '/demo/admin/js/demo-admin.js',
                array( 'jquery' ),
                false,
                true
            );

            // pass data to javascript for our js script
            wp_localize_script( 'deci-demo-admin-ajax-js', 'deciAdminData', [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'security' => wp_create_nonce( 'deci-admin-settings-nonce' )
            ] );

            // Settings form
            if ( current_user_can( 'manage_options' ) ) {
                ?>
                <h1><?php _e( 'Demo settings', 'deci-demo' ) ?></h1>
                <form action="options.php" method="post">
                    <?php // settings_fields('deci-demo-group'); ?>

                    <input id="hide-admin" type="checkbox" name="demo-option" class="deci-demo-field"
                           value="yes" <?php checked( get_option( 'demo-option' ), 'yes' ) ?>>

                    <label for="hide-admin"><?php _e( 'Hide Admin bar in frontend', 'deci-demo' ) ?></label>

                    <?php // submit_button(__('Save', 'deci-demo')); ?>
                </form>
                <?php
            }
        }


        // Add menu item to top bar menu
        public static function add_demo_menu_item(): void
        {
            global $wp_admin_bar;

            $custom_menu = [
                'id' => 'demo_menu',
                'title' => __( 'Demo plugin title', 'deci-demo' ),
                'parent' => 'top-secondary',
                'href' => site_url(),
            ];

            $wp_admin_bar->add_node( $custom_menu );
        }


        // Add text to the admin page footer
        public static function demo_footer_text($text): string
        {
            $text = '<p style="color: forestgreen">' . __( 'Demo plugin advertisement', 'deci-demo' ) . '</p>';

            return $text;
        }


        // AJAX admin handler endpoint callback
        public static function admin_settings_action_handler()
        {
            // validate nonce
            if ( check_ajax_referer( 'deci-admin-settings-nonce', 'security' ) ) {
                $current_option = get_option( 'demo-option' );

                if ( $current_option !== 'yes' ) {
                    update_option( 'demo-option', 'yes' );
                } else {
                    update_option( 'demo-option', 'no' );
                }

                wp_send_json_success( 'OK' );
//                wp_die();
            } else {
                wp_send_json_error( 'This action is not permitted. Invalid nonce!' );
            }
        }


    }

}


