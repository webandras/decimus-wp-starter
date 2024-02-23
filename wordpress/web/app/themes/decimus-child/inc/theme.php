<?php


if ( ! function_exists( 'decimus_child_excerpt_length' ) ) {

	/**
	 * Filter the except length to 30 words.
	 *
	 * @param int $length Excerpt length.
	 *
	 * @return int (Maybe) modified excerpt length.
	 */
	function decimus_child_excerpt_length( int $length ): int {
		return 30;
	}

}
add_filter( 'excerpt_length', 'decimus_child_excerpt_length', 999 );


// Custom breadcrumb
if ( ! function_exists( 'decimus_child_the_breadcrumb' ) ) {
	/**
	 * @return void
	 */
	function decimus_child_the_breadcrumb(): void {
		if ( ! is_home() ) {
			echo '<nav class="breadcrumb mt-3 mb-0 p-2 px-0 small rounded">';
			echo '<a href="' . home_url( '/' ) . '">' . (
				'<i class="fas fa-home"></i>' ) .
			     '</a><span class="divider">&nbsp;/&nbsp;</span>';
			if ( is_category() || is_single() ) {

				$category = get_the_category( ' <span class="divider">&nbsp;/&nbsp;</span> ' );

				if ( count( $category ) > 0 ) {
					echo $category;
				}

				if ( is_single() ) {

					if ( count( $category ) > 0 ) {
						echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
					}
					the_title();
				}
			} elseif ( is_page() ) {
				echo the_title();
			}
			echo '</nav>';
		}
	}

	add_filter( 'breadcrumbs', 'breadcrumbs' );
}
// Breadcrumb END


if ( ! function_exists( 'decimus_child_custom_shortcode_atts_wpcf7_filter' ) ) {

	/**
	 * Custom shortcode attributes used as field values in CF7 forms
	 *
	 * @param $out
	 * @param $pairs
	 * @param $atts
	 *
	 * @return mixed
	 */
	function decimus_child_custom_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ): mixed {
		$new_attr = 'event-name';
		if ( isset( $atts[ $new_attr ] ) ) {
			$out[ $new_attr ] = $atts[ $new_attr ];
		}

		$new_attr = 'event-details';

		if ( isset( $atts[ $new_attr ] ) ) {
			$out[ $new_attr ] = $atts[ $new_attr ];
		}

		// this is used as extra notice, information in the emails
		// fill out the purchase note field for the WooCommerce product
		// $purchase_note = $product->get_purchase_note() ?? '';
		$new_attr = 'event-notice';

		if ( isset( $atts[ $new_attr ] ) ) {
			$out[ $new_attr ] = $atts[ $new_attr ];
		}

		return $out;
	}
}
// Custom shortcode attributes used as field values in CF7 forms
add_filter( 'shortcode_atts_wpcf7', 'decimus_child_custom_shortcode_atts_wpcf7_filter', 10, 3 );


// Add custom image size (with hard crop) 960*600px
// TODO: rename image size and regenerate imgs
add_image_size( 'boritokep', 960, 600, true );

