<?php

!defined( ABSPATH ) || exit;

global $wpdb;
$table_name = $wpdb->prefix . 'deci_demo';

//$wpdb->prepare('');
$wpdb->query( "DROP TABLE IF EXIST $table_name" );
