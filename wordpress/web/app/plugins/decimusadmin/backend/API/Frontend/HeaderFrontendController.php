<?php

namespace Guland\DecimusAdmin\API\Frontend;

use WP_REST_Request;
use WP_REST_Response;


use Guland\DecimusAdmin\API\ParentController as ParentController;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class HeaderFrontendController extends ParentController {

	public function __construct(
		string $route = 'header',
		string $name_space = 'frontend',
		string $get_settings_route_handle = 'get_header_settings_route_handle',
		string $put_settings_route_handle = 'put_header_settings_route_handle',
		bool $is_protected_route = false
	) {
		parent::__construct();
		$this->route                     = $route;
		$this->name_space                = $name_space;
		$this->get_settings_route_handle = $get_settings_route_handle;
		$this->put_settings_route_handle = $put_settings_route_handle;
		$this->is_protected_route        = $is_protected_route;
	}


	/**
	 * @OA\Get(
	 *   path="/wp-json/decimus/v1/frontend/header",
	 *   tags={"frontend"},
	 *   summary="Get header settings for Decimus Theme",
	 *
	 *   @OA\Response(response="200", description="Get header settings successful."),
	 *   @OA\Response(response="400", description="Get header settings failed."),
	 *   @OA\Response(response="403", description="Forbidden."),
	 *   @OA\Response(response="404", description="Not found.")
	 * )
	 *
	 * @return void
	 */
	public function get_header_settings_route(): void {
		$this->get_settings_route();
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response
	 */
	public function get_header_settings_route_handle( WP_REST_Request $request ): WP_REST_Response {
		return $this->get_settings_route_handle( $request );
	}

}
