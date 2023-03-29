<?php

namespace Guland\DecimusAdmin\Database\Seeder;

use Guland\DecimusAdmin\Database\Query\OptionQueries;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait S002
{
    use OptionQueries;

    /**
     * Seed options table
     *
     * @return void
     */
    public static function seed_theme_options_table_002(): void
    {
        if ( count(self::get_option_record('contact_social')) ) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . DECIMUS_ADMIN_OPTIONS_TABLE;

        $key = 'contact_social';
        $default_theme_options = [
            'contact_social' => [
                'enable_back_to_top_arrow' => 1,
            ],
        ];

        $serialized_content = serialize($default_theme_options[$key]);

        // create a query
        $insert_record_query = $wpdb->prepare(
            "INSERT INTO $table_name
            (option_key, option_value)
            VALUES (%s, %s)",
            $key,
            $serialized_content
        );

        $succeed = $wpdb->query($insert_record_query);

        // In case of an error
        if ( !$succeed || $wpdb->last_error !== '' ) {
            print_r($wpdb->last_error);
            die;
        }
    }
}
