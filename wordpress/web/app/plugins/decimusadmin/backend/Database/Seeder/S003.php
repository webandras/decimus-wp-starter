<?php


namespace Guland\DecimusAdmin\Database\Seeder;

use Guland\DecimusAdmin\Database\Query\OptionQueries;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait S003
{
    use OptionQueries;

    /**
     * Seed options table
     *
     * @return void
     */
    public static function seed_theme_options_table_003(): void
    {
        if ( !count(self::get_option_record('woocommerce')) ) {
            return;
        }

		global $wpdb;

        $key = 'woocommerce';
        $old_options = self::get_option_record($key);
        $new_options = $old_options;
        $new_options['option_value']['event_registration'] = 0;

        // use the option_value array item, not the whole array!
        $succeed = self::set_option_record($key, $new_options['option_value']);

        // In case of an error
        if ( !$succeed || $wpdb->last_error !== '' ) {
            print_r($wpdb->last_error);
            die;
        }

    }
}
