<?php

namespace Guland\DecimusAdmin\Core;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait AdminPage {

	/**
	 * Add vue bundles generated by Vue to admin page
	 *
	 * @param string $hook
	 *
	 * @return void
	 */
	public function add_admin_scripts( string $hook ): void {
		if ( $hook != 'toplevel_page_decimus-admin' ) {
			return;
		}

		// only compile to css in dev env
		if ( WP_ENV === 'development' ) {
			wp_enqueue_script( 'decimus-js-admin-chunk-vendors', 'https://172.28.119.227:8080/js/chunk-vendors.js', [], false, true );
			wp_register_script( 'decimus-js-admin-app', 'https://172.28.119.227:8080/js/app.js', [], false, true );
		} else {
			// register the Vue scripts
			wp_enqueue_script( 'decimus-js-admin-chunk-vendors', plugins_url() . '/decimusadmin/admin/dist/js/chunk-vendors.c326f51a.js', [], false, true );
			wp_register_script( 'decimus-js-admin-app', plugins_url() . '/decimusadmin/admin/dist/js/app.27b94a6b.js', [], false, true );
			wp_enqueue_style( 'decimus-admin-css', plugins_url() . '/decimusadmin/admin/dist/css/app.41c06b16.css', [], self::DB_VERSION );
			wp_enqueue_style( 'decimus-admin-chunk-vendors-css', plugins_url() . '/decimusadmin/admin/dist/css/chunk-vendors.71a233fb.css', [], self::DB_VERSION );
		}


		// make custom data available to the Vue app with wp_localize_script.
		global $post;
		wp_add_inline_script(
			'decimus-js-admin-app', // vue script handle defined in wp_register_script.
			// WordPress data that will make available to Vue.
			'const decimusAdminData = ' . json_encode(
				array(
					'plugin_directory_uri' => plugin_dir_path( dirname( __FILE__, 2 ) ),
					// plugin directory path.
					'rest_url'             => untrailingslashit( esc_url_raw( rest_url() ) ),
					// URL to the REST endpoint.
					'app_path'             => 'wp-admin/admin.php?page=decimus-admin',
					// page where the custom page template is loaded.
					'decimus_nonce'        => wp_create_nonce( 'wp_rest' )
					// need this nonce besides the cookie-based authorization
				)
			),
			'before'
		);
		// enqueue the Vue app script with localized data.
		wp_enqueue_script( 'decimus-js-admin-app' );
	}


	/**
	 * Register admin menu page and submenu page
	 * @return void
	 */
	public function add_admin_menu(): void {
		add_menu_page(
			__( 'Decimus Theme Settings', self::TEXT_DOMAIN ), // page title
			__( 'Decimus Theme Settings', self::TEXT_DOMAIN ), // menu title
			'manage_options', // capability
			'decimus-admin', // menu slug
			array( $this, 'add_admin_entrypoint' ), // callback
			'dashicons-superhero', // icon
			3
		);
	}

	public function add_admin_entrypoint(): void {
		$html = '<div id="decimus-theme-admin"></div>';
		echo $html;
	}
}
