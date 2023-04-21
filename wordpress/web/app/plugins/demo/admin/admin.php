<?php

!defined( ABSPATH ) || die();

require_once 'Deci_menu.php';


// Register demo settings
add_action( 'admin_init', [ 'Deci_menu', 'register_demo_settings' ] );

// Demo menu link in admin bar
add_action( 'admin_bar_menu', [ 'Deci_menu', 'add_demo_menu_item' ] );

// Add menu to admin
add_action( 'admin_menu', [ 'Deci_menu', 'add_demo_menu' ] );

// Custom admin page footer text
add_filter( 'admin_footer_text', [ 'Deci_menu', 'demo_footer_text' ] );


// AJAX ENDPOINTS

// Registers AJAX admin endpoint
add_action( 'wp_ajax_deci_admin_settings_action', [ 'Deci_menu', 'deci_admin_settings_action_handler' ] );

