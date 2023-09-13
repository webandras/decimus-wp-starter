<?php

if ( ! function_exists( 'decimus_wpsites_query' ) ) :

	/**
	 * Amount of posts/products in category
	 *
	 * @param $query
	 *
	 * @return void
	 */
	function decimus_wpsites_query( $query ): void {
		if ( $query->is_archive() && $query->is_main_query() && ! is_admin() ) {
			$query->set( 'posts_per_page', 24 );
		}
	}

	add_action( 'pre_get_posts', 'decimus_wpsites_query' );

endif;
// Amount of posts/products in category END


if ( ! function_exists( 'decimus_pagination' ) ) :

	/**
	 * Pagination Categories
	 *
	 * @param $pages
	 * @param $range
	 *
	 * @return void
	 */
	function decimus_pagination( $pages = '', $range = 2 ): void {
		$showitems = ( $range * 2 ) + 1;
		global $paged;
		if ( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;

			if ( ! $pages ) {
				$pages = 1;
			}
		}

		if ( 1 != $pages ) {
			echo '<nav aria-label="' . __( 'Page navigation', 'decimus' ) . '" role="navigation">';
			echo '<span class="sr-only">' . __( 'Page navigation', 'decimus' ) . '</span>';
			echo '<ul class="pagination justify-content-center ft-wpbs mb-4">';


			if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
				echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link( 1 ) . '" aria-label="' . __( 'First Page',
						'decimus' ) . '">&laquo;</a></li>';
			}

			if ( $paged > 1 && $showitems < $pages ) {
				echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link( $paged - 1 ) . '" aria-label="' . __( 'Previous Page',
						'decimus' ) . '">&lsaquo;</a></li>';
			}

			for ( $i = 1; $i <= $pages; $i ++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo ( $paged == $i ) ?
						'<li class="page-item active"><span class="page-link"><span class="sr-only">' . __( 'Current Page',
							'decimus' ) . '</span>' . $i . '</span></li>'
						: '<li class="page-item"><a class="page-link" href="' . get_pagenum_link( $i ) . '"><span class="sr-only">' . __( 'Page ',
							'decimus' ) . '</span>' . $i . '</a></li>';
				}
			}

			if ( $paged < $pages && $showitems < $pages ) {
				echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link( ( $paged === 0 ? 1 : $paged ) + 1 ) . '" aria-label="' . __( 'Next Page',
						'decimus' ) . '">&rsaquo;</a></li>';
			}

			if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
				echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link( $pages ) . '" aria-label="' . __( 'Last Page',
						'decimus' ) . '">&raquo;</a></li>';
			}

			echo '</ul>';
			echo '</nav>';
			// Uncomment this if you want to show [Page 2 of 30]
			// echo '<div class="pagination-info mb-5 text-center">[ <span class="text-muted">Page</span> '.$paged.' <span class="text-muted">of</span> '.$pages.' ]</div>';
		}
	}
endif;
//Pagination Categories END


if ( ! function_exists( 'decimus_post_link_attributes' ) ) :

	/**
	 * Pagination Buttons Single Posts
	 *
	 * @param $output
	 *
	 * @return array|string|string[]
	 */
	function decimus_post_link_attributes( $output ): array|string {
		$code = 'class="page-link"';

		return str_replace( '<a href=', '<a ' . $code . ' href=', $output );
	}
endif;
add_filter( 'next_post_link', 'decimus_post_link_attributes' );
add_filter( 'previous_post_link', 'decimus_post_link_attributes' );
// Pagination Buttons Single Posts END


if ( ! function_exists( 'the_breadcrumb' ) ) :

	/**
	 * Breadcrumb
	 * @return void
	 */
	function the_breadcrumb(): void {
		if ( ! is_home() ) {
			echo '<nav class="breadcrumb mb-4 mt-2 bg-light py-2 px-3 small rounded">';
			echo '<a href="' . home_url( '/' ) . '">' . ( '<i class="fas fa-home"></i>' ) . '</a><span class="divider">&nbsp;/&nbsp;</span>';
			if ( is_category() || is_single() ) {
				the_category( ' <span class="divider">&nbsp;/&nbsp;</span> ' );
				if ( is_single() ) {
					echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
					the_title();
				}
			} elseif ( is_page() ) {
				echo the_title();
			}
			echo '</nav>';
		}
	}
endif;
add_filter( 'breadcrumbs', 'breadcrumbs' );
// Breadcrumb END
