<?php
/*
Plugin Name: Decimus Movie Reviews
Plugin URI: https://decimus.hu/
Description: A plugin for movie reviews
Version: 1.0.0
Author: Gulácsi András
Author URI: https://decimus.hu/
License: MIT
Domain: decimus-movie-reviews
*/

! defined( 'ABSPATH' ) && exit;


// required plugin class
require_once dirname( __FILE__ ) . '/backend/class-tgm-plugin-activation.php';

// widget for movie reviews
require_once dirname( __FILE__ ) . '/backend/widgets.class.php';

// main class
require_once 'Decimus_movie_reviews.php';


// activation / deactivation hooks
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, [ 'Decimus_movie_reviews', 'activate' ] );

// disable this filter hook for ACF plugin (it disables WordPress' default custom fields, but we need them)
add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );

/*--------------------------
		Helper functions
	--------------------------*/

function mr_get_rating_stars( $rating = null ) {
	$rating = (int) $rating;
	if ( $rating > 0 ) {
		$rating_stars   = array();
		$rating_display = '';

		// add filled stars first
		for ( $i = 0; $i < floor( $rating / 2 ); $i ++ ) {
			$rating_stars[] = '<span class="dashicons dashicons-star-filled"></span>';
		}

		// if the rating is odd, add a half-filled star
		if ( $rating % 2 === 1 ) {
			$rating_stars[] = '<span class="dashicons dashicons-star-half"></span>';
		}

		// pad the rest with empties
		$rating_stars = array_pad( $rating_stars, 5, '<span class="dashicons dashicons-star-empty"></span>' );

		return implode( "\n", $rating_stars );
	}

	return false;
}

