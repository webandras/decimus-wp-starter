<?php

// Fired when the plugin is uninstalled.

// If not called by WordPress, die.
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) die;
 
// Single site options.
delete_option('qib_settingz');
delete_option('qib_first_activate');
delete_option('qib_dismiss_notice');