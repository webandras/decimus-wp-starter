<?php

namespace Guland\DecimusAdmin\Database\Migration;


// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait M002
{
    /**
     * Create Decimus theme options table
     *
     * @return void
     */
    public static function alter_theme_options_table_002(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::ADMIN_OPTIONS_TABLE;

        $sql = "ALTER TABLE $table_name ADD UNIQUE KEY (option_key);";

        $wpdb->query($sql);
    }
}
