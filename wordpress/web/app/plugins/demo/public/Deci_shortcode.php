<?php

!defined( ABSPATH ) || die();

if ( !class_exists( 'Deci_shortcode' ) ) {


    final class Deci_shortcode
    {

        // Just a demo shortcode callback
        public static function demo_code_callback($attr): string
        {
            wp_enqueue_script( 'demo-js', plugins_url() . '/demo/public/assets/js/demo.js', array( 'jquery' ), false,
                true );

            return ( isset( $attr['color'] ) ) ?
                '<div class="demo-border"><div class="demo-badge badge bg-' . esc_attr( $attr['color'] ) . '">Demo shortcode</div></div>'
                :
                '<div class="demo-border"><div class="demo-badge badge bg-light">Demo shortcode</div></div>';
        }


        // Hides the admin bar
        public static function hide_admin_bar_on_frontend(): void
        {
            $option = get_option( 'demo-option' ) ?? '';
            settype( $option, 'string' );

            if ( $option === 'yes' ) {
                // returns false
                add_filter( 'show_admin_bar', '__return_false' );
            }
        }

    }

}
