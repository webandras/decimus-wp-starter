<?php

namespace Guland\DecimusAdmin\Database\Migration;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait M001 {
	/**
	 * Create Decimus theme options table
	 *
	 * @return void
	 */
	public static function create_theme_options_table_001(): void {
		global $wpdb;
		$table_name      = $wpdb->prefix . self::ADMIN_OPTIONS_TABLE;
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `option_key` VARCHAR(255) NOT NULL,
        `option_value` TEXT NOT NULL,
        PRIMARY KEY (`id`)
      ) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	/** Delete Decimus theme options table when uninstalling plugin
	 *
	 * @return void
	 */
	public static function delete_theme_options_table_001(): void {
		global $wpdb;
		$table_name = $wpdb->prefix . self::ADMIN_OPTIONS_TABLE;
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	}
}
