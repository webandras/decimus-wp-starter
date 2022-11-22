<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Decimus
 */


// Category Badge
if ( ! function_exists( 'decimus_category_badge' ) ) :
	function decimus_category_badge() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
            echo '<div class="category-badge mb-2">';
            $thelist = '';
			$i = 0;
            foreach( get_the_category() as $category ) {
		      if ( 0 < $i ) $thelist .= ' ';
						    $thelist .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="badge bg-primary text-white">' . $category->name.'</a>';
						    $i++;
            }
            echo $thelist;	
            echo '</div>';
		}
	}
endif;
// Category Badge End


// Category
if ( ! function_exists( 'decimus_category' ) ) :
	function decimus_category(): void {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'decimus' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links"></span>', $categories_list ); // WPCS: XSS OK.	
			}		
		}
	}
endif;
// Category End


// Date
if ( ! function_exists( 'decimus_date' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function decimus_date() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <span class="time-updated-separator">/</span> <time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			'%s',
			'<span rel="bookmark">' . $time_string . '</span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}
endif;
// Date End


// Author
if ( ! function_exists( 'decimus_author' ) ) :

	function decimus_author() {
		$byline = sprintf(
			esc_html_x( 'by %s', 'post author', 'decimus' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}
endif;
// Author End


// Comments
if ( ! function_exists( 'decimus_comments' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function decimus_comments() {


		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo ' | <i class="far fa-comments"></i> <span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment', 'decimus' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
	
	}
endif;
// Comments End


// Edit Link
if ( ! function_exists( 'decimus_edit' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function decimus_edit() {

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit', 'decimus' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			' | <span class="edit-link">',
			'</span>'
		);
	}
endif;
// Edit Link End
		

// Single Comments Count
if ( ! function_exists( 'decimus_comment_count' ) ) :
	/**
	 * Prints HTML with the comment count for the current post.
	 */
	function decimus_comment_count() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo ' | <i class="far fa-comments"></i> <span class="comments-link">';

			/* translators: %s: Name of current post. Only visible to screen readers. */
			// comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'decimus' ), get_the_title() ) );
			comments_popup_link( sprintf( __( 'Leave a comment', 'decimus' ), get_the_title() ) );


			echo '</span>';
		}
	}
endif;
// Single Comments Count End


// Tags
if ( ! function_exists( 'decimus_tags' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function decimus_tags() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {


			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<div class="tags-links text-muted small-size mt-3">' . esc_html__( 'Tagged %1$s', 'decimus' ) . '</div>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}
    add_filter( "term_links-post_tag", 'decimus_add_tag_class');

    function decimus_add_tag_class($links): array|string {
        return str_replace('<a href="', '<a class="badge bg-secondary text-white" href="', $links);
    }
endif;
// Tags End


// Featured Image
if ( ! function_exists( 'decimus_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function decimus_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail('full', array('class' => 'rounded mb-3')); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</a>

		<?php
		endif; // End is_singular().
	}
endif;
// Featured Image End


// Internet Explorer Warning Alert
if ( ! function_exists( 'decimus_ie_alert' ) ) :
	/**
	 * Displays an alert if page is browsed by Internet Explorer
	 */
	function decimus_ie_alert() {
            $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
            if (preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false)) {
                echo '
                    <div class="container pt-5">
                        <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
                            <h1>' . esc_html__( 'Internet Explorer detected', 'decimus' ) . '</h1>
                            <p class="lead">' . esc_html__( 'This website will offer limited functionality in this browser.', 'decimus' ) . '</p>
                            <p class="lead">' . esc_html__( 'Please use a modern and secure web browser like', 'decimus' ) . ' <a href="https://www.mozilla.org/firefox/" target="_blank">Mozilla Firefox</a>, <a href="https://www.google.com/chrome/" target="_blank">Google Chrome</a>, <a href="https://www.opera.com/" target="_blank">Opera</a> ' . esc_html__( 'or', 'decimus' ) . ' <a href="https://www.microsoft.com/edge" target="_blank">Microsoft Edge</a> ' . esc_html__( 'to display this site correctly.', 'decimus' ) . '</p>
                        </div>
                    </div>
               ';
            }
        
	}
endif;


