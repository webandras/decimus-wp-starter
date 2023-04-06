<?php
/*
Plugin Name: Decimus Team Members
Plugin URI: https://github.com/SalsaBoy990/decimus-wp-starter/tree/master/wordpress/web/app/plugins/decimus-team-member
Description: Decimus Team Members plugin
Version: 1.0.0
Author: András Gulácsi
Author URI: https://github.com/SalsaBoy990
License: GPLv2 or later
Text Domain: decimus-team-member
Domain Path: /languages
*/

defined('ABSPATH') or die();


define('DECIMUS_TEAM_MEMBER_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once 'autoload.php';

use \Gulacsi\TeamMember\TeamMember;
use \Gulacsi\TeamMember\Log\Klogger as Klogger;

global $decimus_team_member_log;
// pass the path to folder where to store log files.
$decimus_team_member_log = new Klogger(plugin_dir_path(__FILE__) . '/log', Klogger::INFO);

// singleton instance
TeamMember::get_instance();

register_activation_hook(__FILE__, '\Gulacsi\TeamMember\TeamMember::activate_plugin');

register_uninstall_hook(__FILE__, '\Gulacsi\TeamMember\TeamMember::delete_plugin');
