<?php

namespace Guland\DecimusAdmin\API;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

use Guland\DecimusAdmin\Database\Query\OptionQueries;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

class ParentController
{
    use OptionQueries;

    protected string $route;
    protected string $name_space;
    protected string $get_settings_route_handle;
    protected string $put_settings_route_handle;

    protected bool $is_protected_route;


    public function __construct(
        string $route = 'global',
        string $name_space = 'admin',
        string $get_settings_route_handle = 'get_settings_route_handle',
        string $put_settings_route_handle = 'put_settings_route_handle',
        bool   $is_protected_route = true)
    {
        $this->route = $route;
        $this->name_space = $name_space;
        $this->get_settings_route_handle = $get_settings_route_handle;
        $this->put_settings_route_handle = $put_settings_route_handle;
        $this->is_protected_route = $is_protected_route;
    }

    /**
     * Get route settings
     *
     * @return void
     */
    public function get_settings_route(): void
    {
        register_rest_route(
            'decimus/v1/' . $this->name_space,
            '/' . $this->route . '/',
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, $this->get_settings_route_handle),
                'permission_callback' => function () {
                    return $this->is_protected_route ? current_user_can('manage_options') : true;
                }
            )
        );
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_settings_route_handle(WP_REST_Request $request): WP_REST_Response
    {
        $res = $this->get_option_record($this->route);

        if ( $res ) {
            return new WP_REST_Response(
                array(
                    'status' => 200,
                    'message' => 'OK',
                    'description' => __('Get ' . $this->route . ' settings successful.', DECIMUS_ADMIN_TEXT_DOMAIN),
                    'data' => $res
                ),
                200
            );
        } else {
            return new WP_REST_Response(
                array(
                    'status' => 400,
                    'message' => 'rest_bad_request',
                    'description' => __('Get ' . $this->route . ' settings failed.', DECIMUS_ADMIN_TEXT_DOMAIN),
                    'data' => null
                ),
                400
            );
        }
    }


    /**
     * Update route settings
     *
     * @return void
     */
    public function put_settings_route(): void
    {
        register_rest_route(
            'decimus/v1/' . $this->name_space,
            '/' . $this->route . '/',
            array(
                'methods' => WP_REST_Server::EDITABLE,
                'callback' => array($this, $this->put_settings_route_handle),
                'permission_callback' => function () {
                    return $this->is_protected_route ? current_user_can('manage_options') : true;
                }
            )
        );
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function put_settings_route_handle(WP_REST_Request $request): WP_REST_Response
    {
        $data = json_decode($request->get_body(), true);
        $res = $this->set_option_record($this->route, $data);

        if ( $res ) {
            return new WP_REST_Response(
                array(
                    'status' => 200,
                    'message' => 'OK',
                    'description' => __('Update ' . $this->route . ' settings successful.', DECIMUS_ADMIN_TEXT_DOMAIN)
                ),
                200
            );
        } else {
            return new WP_REST_Response(
                array(
                    'status' => 400,
                    'message' => 'rest_bad_request',
                    'description' => __('Update ' . $this->route . ' settings failed.', DECIMUS_ADMIN_TEXT_DOMAIN),
                ),
                400
            );
        }
    }

}
