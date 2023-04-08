<?php
/*
Plugin Name: Decimus Team Members
Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/decimus-team-member
Description: Decimus Team Members plugin
Version: 1.0.1
Author: András Gulácsi
Author URI: https://github.com/SalsaBoy990
License: GPLv2 or later
Text Domain: decimus-team-member
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die();

require_once dirname( __FILE__, 2 ) . '/decimus-general/decimus-general.php';
require_once 'autoload.php';

use \Decimus\Team_member\Team_member;

define( 'DECIMUS_TEAM_MEMBER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// logger instance
global $dtm_log;
$dtm_log = new Decimus_logger( DECIMUS_TEAM_MEMBER_PLUGIN_DIR . '/log', Decimus_logger::INFO );

// main instance
Team_member::get_instance();

register_activation_hook( __FILE__, '\Decimus\Team_member\Team_member::activate_plugin' );
register_uninstall_hook( __FILE__, '\Decimus\Team_member\Team_member::delete_plugin' );
