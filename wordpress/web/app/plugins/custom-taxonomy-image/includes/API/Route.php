<?php

namespace Guland\CustomTaxonomyImage\API;

use WP_REST_Server;
use WP_REST_Response;
use WP_REST_Request;
use WP_Error;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait Route
{
    public function get_taxonomy_image_route(): void
    {
        register_rest_route(
            'wp-custom-taxonomy-image/v1',
            '/image/',
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'get_rest_option'),
                'permission_callback' => array($this, 'permissions_check')
            )
        );
    }

    //?option_name=siteurl
    public function permissions_check(): bool|WP_Error
    {
        if ( !current_user_can('edit_posts') ) {
            return new \WP_Error(
                'rest_unauthorized',
                esc_html__('Unauthorized.', GULAND_CTI_TEXT_DOMAIN),
                array('status' => 401)
            );
        }
        // OK
        return true;
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return WP_REST_Response
     */
    public function get_rest_option(WP_REST_Request $request): WP_REST_Response
    {
        // category image option_name format: '_category_image-category-id'
        $optionName = $request->get_param('option_name');

        // Bad request error response
        if ( $optionName == '' ) {
            return new WP_REST_Response(
                array(
                    'status' => 400,
                    'message' => 'rest_bad_request',
                    'description' => __('Missing parameter \'option_name\'', GULAND_CTI_TEXT_DOMAIN),
                    'image_url' => null
                ),
                400
            );
        }

        $optionNameExploded = explode('-', $optionName);
        $optionNamePrefixWithoutId = '';
        if ( $optionNameExploded ) {
            $optionNamePrefixWithoutId = $optionNameExploded[0];
        }

        // Option OK response
        if ( $optionNamePrefixWithoutId === '_category_image' ) {

            $opt = get_option($optionName);

            if ( $opt ) {
                return new WP_REST_Response(
                    array(
                        'status' => 200,
                        'message' => 'OK',
                        'description' => __('Taxonomy image url returned successfully', GULAND_CTI_TEXT_DOMAIN),
                        'image_url' => $opt
                    ),
                    200
                );
            } else {
                return new WP_REST_Response(
                    array(
                        'status' => 404,
                        'message' => 'rest_not_found',
                        'description' => __('Option not found.', GULAND_CTI_TEXT_DOMAIN),
                        'image_url' => null
                    ),
                    404
                );
            }
        } else {
            // Forbidden error response
            return new WP_REST_Response(
                array(
                    'status' => 403,
                    'message' => 'rest_forbidden',
                    'description' => __('Forbidden. Only taxonomy image options can be accessed through this route', GULAND_CTI_TEXT_DOMAIN),
                    'image_url' => null
                ),
                403
            );
        }
    }
}
