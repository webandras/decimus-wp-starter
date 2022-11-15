<?php

namespace Guland\DecimusAdmin\Database\Query;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait OptionQueries
{
    /*
     * Get Decimus theme option record
     *
     * @return array
     */
    public static function get_option_record(string $key = 'ROUTE'): array
    {
        global $wpdb;
        $table_name = $wpdb->prefix . DECIMUS_ADMIN_OPTIONS_TABLE;

        $prepared_query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE option_key = %s LIMIT 1",
            $key
        );
        $record = $wpdb->get_row($prepared_query, ARRAY_A);

        if ( !$record ) {
            return [];
        }

        // needs un-serialization
        if ( is_serialized($record['option_value']) ) {
            $record['option_value'] = unserialize(trim($record['option_value']));
        }

        return $record;
    }

    /*
     * Update Decimus theme option record
     *
     * @return bool
     */
    public static function set_option_record(string $key = 'header', array $value = []): bool
    {
        global $wpdb;
        $serialized_content = serialize($value);

        $table_name = $wpdb->prefix . DECIMUS_ADMIN_OPTIONS_TABLE;

        $prepared_query = $wpdb->prepare(
            "UPDATE $table_name SET option_value = %s WHERE option_key = %s",
            $serialized_content,
            $key
        );

        $succeed = $wpdb->query($prepared_query);

        if ( $wpdb->last_error !== '' ) {
           return $wpdb->last_error;
        }

        return true;
    }
}
