<?php

!defined( ABSPATH ) || exit;

if ( !class_exists( 'Decimus_taxonomy_image_routes' ) ) {


    final class Decimus_taxonomy_image_routes
    {
        public function get_taxonomy_image_route(): void
        {
            register_rest_route(
                'decimus-taxonomy-image/v1',
                '/image/',
                array(
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => array( $this, 'get_rest_option' ),
                    'permission_callback' => array( $this, 'permissions_check' )
                )
            );
        }

        //?option_name=siteurl
        private function permissions_check(): bool|WP_Error
        {
            if ( !current_user_can( 'edit_posts' ) ) {
                return new \WP_Error(
                    'rest_unauthorized',
                    'Unauthorized.',
                    array( 'status' => 401 )
                );
            }
            // OK
            return true;
        }


        /**
         * @param  WP_REST_Request  $request
         *
         * @return WP_REST_Response
         */
        private function get_rest_option(WP_REST_Request $request): WP_REST_Response
        {
            // category image option_name format: '_category_image-category-id'
            $option_name = $request->get_param( 'option_name' );

            // Bad request error response
            if ( $option_name !== null ) {
                return new WP_REST_Response(
                    array(
                        'status' => 400,
                        'message' => 'rest_bad_request',
                        'description' => 'Missing parameter \'option_name\'',
                        'image_url' => null
                    ),
                    400
                );
            }

            $option_name_exploded = explode( '-', $option_name );
            $option_name_prefix_without_id = '';
            if ( $option_name_exploded ) {
                $option_name_prefix_without_id = $option_name_exploded[0];
            }

            // Option OK response
            if ( $option_name_prefix_without_id === '_category_image' ) {

                $option = get_option( $option_name );

                if ( $option ) {
                    return new WP_REST_Response(
                        array(
                            'status' => 200,
                            'message' => 'OK',
                            'description' => 'Taxonomy image url returned successfully',
                            'image_url' => $option
                        ),
                        200
                    );
                } else {
                    return new WP_REST_Response(
                        array(
                            'status' => 404,
                            'message' => 'rest_not_found',
                            'description' => 'Option not found.',
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
                        'description' => 'Forbidden. Only taxonomy image options can be accessed through this route',
                        'image_url' => null
                    ),
                    403
                );
            }
        }
    }

}
