<?php

// This code will only run when plugin is deleted
if ( !defined('WP_UNINSTALL_PLUGIN') ) {
    exit();
}
/*
global $wpdb;
$table_name = $wpdb->prefix . 'gcompany_team';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

// check if option exists, then delete
if ( get_option('company_team_db_version') ) {
    delete_option('company_team_db_version');
}*/
