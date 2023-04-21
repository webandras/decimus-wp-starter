<?php

!defined( ABSPATH ) || exit;

require_once 'Deci_shortcode.php';
require_once 'Deci_weather.php';

// Hides to admin bar
add_action( 'init', [ 'Deci_shortcode', 'hide_admin_bar_on_frontend' ] );

// Demo weather shortcode
add_shortcode( 'demo_code', [ 'Deci_shortcode', 'demo_code_callback' ] );

// Current weather shortcode
add_shortcode( 'current_weather', [ $deci_weather, 'get_current_weather_shortcode' ] );
