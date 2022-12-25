<?php

if ( current_user_can('manage_options') ) {
    // delete current member
    global $wpdb;
    $valid = true;

    /** @noinspection SqlNoDataSourceInspection */
    $sql = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . "self::TABLE_NAME WHERE id = %d", $id);

    $row = $wpdb->query($sql);
} else {
    wp_die('You are not authorized to perform this action.');
}
