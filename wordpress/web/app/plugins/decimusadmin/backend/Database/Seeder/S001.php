<?php

namespace Guland\DecimusAdmin\Database\Seeder;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait S001 {
	/**
	 * Seed options table
	 *
	 * @return void
	 */
	public static function seed_theme_options_table(): void {
		global $wpdb;
		$table_name = $wpdb->prefix . self::ADMIN_OPTIONS_TABLE;

		$sections              = [ 'global', 'contact', 'header', 'woocommerce', 'admin' ];
		$default_theme_options = [
			'global'      => [
				'skin'                       => 'lux',
				'enable_scroll_to_top_arrow' => 1,
				'enable_social_share_links'  => 1,
				'enable_blog_sidebar'        => 0,
				'map_embed_link'             => 'https://www.google.com/maps/place/M%C3%B3ra+Ferenc+M%C3%BAzeum/@46.2526336,20.1523296,17.65z/data=!4m5!3m4!1s0x4744886dc031f6f9:0xa348c66b86daf536!8m2!3d46.2522808!4d20.1520785',
			],
			'contact'     => [
				'phone_number'  => '+36201234567',
				'email_address' => 'hello@example.com',
				'facebook'      => 'https://facebook.com/',
				'messenger'     => 'https://m.me/',
				'instagram'     => 'https://instagram.com/',
				'youtube'       => 'https://youtube.com/',
			],
			'header'      => [
				'account_button' => 1,
				'cart_button'    => 1,
				'search_button'  => 1,
			],
			'woocommerce' => [
				'show_single_product_meta' => 1,
				'enable_product_videos'    => 1,
			],
			'admin'       => [
				'enable_gutenberg_block_editor_in_widgets' => 0,
			],
		];

		foreach ( $sections as $key ) {

			$serialized_content = serialize( $default_theme_options[ $key ] );

			// create a query
			$insert_record_query = $wpdb->prepare(
				"INSERT INTO $table_name
                (option_key, option_value)
                VALUES (%s, %s)",
				$key,
				$serialized_content
			);

			$succeed = $wpdb->query( $insert_record_query );

			// In case of an error
			if ( ! $succeed || $wpdb->last_error !== '' ) {
				print_r( $wpdb->last_error );
				die;
			}
		}
	}
}
