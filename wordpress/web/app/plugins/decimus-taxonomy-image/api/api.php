<?php

!defined( ABSPATH ) || exit;

require_once 'Taxonomy_image_routes.php';

$taxonomy_image_routes = new Decimus_taxonomy_image_routes;

// Add REST API route to GET the taxonomy image from options table
add_action( 'rest_api_init', array( $taxonomy_image_routes, 'get_taxonomy_image_route' ) );
