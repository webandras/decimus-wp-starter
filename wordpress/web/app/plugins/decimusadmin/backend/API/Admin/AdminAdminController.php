<?php

namespace Guland\DecimusAdmin\API\Admin;

use WP_REST_Request;
use WP_REST_Response;


use Guland\DecimusAdmin\API\ParentController as ParentController;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminAdminController extends ParentController {

	public function __construct(
		string $route = 'admin',
		string $name_space = 'admin',
		string $get_settings_route_handle = 'get_admin_settings_route_handle',
		string $put_settings_route_handle = 'put_admin_settings_route_handle'
	) {
		parent::__construct();
		$this->route                     = $route;
		$this->name_space                = $name_space;
		$this->get_settings_route_handle = $get_settings_route_handle;
		$this->put_settings_route_handle = $put_settings_route_handle;
	}


	/**
	 * Get admin settings for Decimus Theme
	 *
	 * @return void
	 */
	public function get_admin_settings_route(): void {
		$this->get_settings_route();
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function get_admin_settings_route_handle( WP_REST_Request $request ): WP_REST_Response {
		return $this->get_settings_route_handle( $request );
	}


	/**
	 * Update admin settings
	 *
	 * @return void
	 */
	public function put_admin_settings_route(): void {
		$this->put_settings_route();
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function put_admin_settings_route_handle( WP_REST_Request $request ): WP_REST_Response {
		return $this->put_settings_route_handle( $request );
	}
}
