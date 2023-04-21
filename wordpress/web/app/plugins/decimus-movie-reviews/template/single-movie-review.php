<?php
/*
 * Default template for movie reviews
 */

get_header(); ?>

<?php
if ( is_class_activated() ) {
	$global_request  = new WP_REST_Request( 'GET', '/decimus/v1/frontend/global' );
	$global_response = rest_do_request( $global_request );
	$global_data     = rest_get_server()->response_to_data( $global_response, true );

	// check if we received the data from the endpoint
	$have_global_data = isset( $global_data ) && isset( $global_data['data'] );
	$global_options   = $have_global_data && isset( $global_data['data']['option_value'] ) ? $global_data['data']['option_value'] : [];

	$enable_blog_sidebar = isset( $global_options['enable_blog_sidebar'] ) ? intval( $global_options['enable_blog_sidebar'] ) : 0;
}
?>

<div id="content" class="site-content container-fluid side-padding narrow-content py-5 mt-3">
    <div id="primary" class="content-area">

		<?php if ( isset( $enable_blog_sidebar ) && ! $enable_blog_sidebar ) { ?>
        <div class="row">
            <div class="col-md-10 offset-md-1 col-xxl-8 offset-xxl-2">
				<?php } ?>

                <!-- Hook to add something nice -->
				<?php decimus_after_primary(); ?>

				<?php the_breadcrumb(); ?>

				<?php if ( isset( $enable_blog_sidebar ) && ! $enable_blog_sidebar ) { ?>
            </div>
        </div>
	<?php } ?>

        <div class="row">
			<?php if ( isset( $enable_blog_sidebar ) && $enable_blog_sidebar ) { ?>
            <div class="col-md-8 col-xxl-9">
				<?php } else { ?>
                <div class="col-md-10 offset-md-1 col-xxl-8 offset-xxl-2">
					<?php } ?>

					<?php
					// Start the loop.
					while ( have_posts() ) :
					the_post();

					$the_field_prefix = Decimus_movie_reviews::FIELD_PREFIX;

					$poster     = get_the_post_thumbnail( get_the_ID(), 'full' );
					$poster_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );

					$rating         = (int) post_custom( $the_field_prefix . 'review_rating' );
					$rating_display = mr_get_rating_stars( $rating );

					$director  = wp_strip_all_tags( post_custom( $the_field_prefix . 'movie_director' ) );
					$imdb_link = esc_url( post_custom( $the_field_prefix . 'movie_imdb' ) );
					$year      = (int) post_custom( $the_field_prefix . 'movie_year' );

					$movie_terms = get_the_terms( get_the_ID(), 'movie_types' );
					$movie_type  = '';
					if ( $movie_terms && ! is_wp_error( $movie_terms ) ) {
						$movie_types = array();

						foreach ( $movie_terms as $term ) {
							$movie_types[] = $term->name;
						}

						$movie_type = implode( ", ", $movie_types );
					}
					?>


                    <main id="main" class="site-main">

                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry' ); ?>>

                            <header class="entry-header">
                                <!--								--><?php //the_post();
								?>
								<?php the_title( '<h1>', '</h1>' ); ?>

                                <p class="entry-meta">
                                    <small class="text-muted">
										<?php
										/*									decimus_date();
																			_e(' by ', 'decimus');
																			the_author_posts_link();
																			decimus_comment_count();*/
										?>
                                    </small>
                                </p>
                                <!--							--><?php //decimus_post_thumbnail();
								?>
                            </header>

                            <div class="entry-content">
                                <div class="left">

									<?php if ( isset( $poster ) ) : ?>
                                        <div class="poster">
											<?php if ( $imdb_link ) : ?>
												<?php print '<a href="' . $imdb_link . '" target="_blank">' . $poster . '</a>'; ?>
											<?php else : ?>
												<?php print $poster; ?>
											<?php endif; ?>
                                        </div>
									<?php endif; ?>

									<?php if ( ! empty( $rating_display ) ) : ?>
                                        <div class="rating rating-<?php print $rating; ?>">
											<?php print $rating_display; ?>
                                        </div>
									<?php endif; ?>

                                    <div class="movie-meta">

										<?php if ( ! empty( $director ) ) : ?>
                                            <div class="director">
                                                <label>Directed by:</label> <?php print $director; ?>
                                            </div>
										<?php endif; ?>

										<?php if ( ! empty( $movie_type ) ) : ?>
                                            <div class="types">
                                                <label>Genre:</label> <?php print $movie_type; ?>
                                            </div>
										<?php endif; ?>

										<?php if ( ! empty( $year ) ) : ?>
                                            <div class="release-year">
                                                <label>Release Year:</label> <?php print $year; ?>
                                            </div>
										<?php endif; ?>

                                    </div>

									<?php if ( ! empty( $imdb_link ) ) : ?>
                                        <div class="link">
                                            <a href="<?php print $imdb_link; ?>" target="_blank">View on IMDb »</a>
                                        </div>
									<?php endif; ?>

                                </div> <!-- // left -->

                                <div class="right">
                                    <div class="review-body">
										<?php the_content(); ?>
                                    </div>
                                </div> <!-- // right -->


                            </div>

                            <footer class="entry-footer clear-both">
                                <div class="mb-4">
									<?php decimus_category_badge(); ?>
									<?php decimus_tags(); ?>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination d-flex justify-content-between mb-5">
                                        <li class="page-item">
											<?php previous_post_link( '%link' ); ?>
                                        </li>
                                        <li class="page-item">
											<?php next_post_link( '%link' ); ?>
                                        </li>
                                    </ul>
                                </nav>
                            </footer>
							<?php edit_post_link( __( 'Edit' ) ); ?>

                        </article>

						<?php // End the loop.
						endwhile;
						?>

						<?php
						// Previous/next post navigation.
						/*                        the_post_navigation( array(
												'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next' ) . '</span> ' .
												'<span class="screen-reader-text">' . __( 'Next review:' ) . '</span> ' .
												'<span class="post-title">%title</span>',
												'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous' ) . '</span> ' .
												'<span class="screen-reader-text">' . __( 'Previous review:' ) . '</span> ' .
												'<span class="post-title">%title</span>',
												) );*/
						?>

						<?php
						// If comments are open, or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
//							comments_template();
						} ?>

                    </main> <!-- #main -->

                </div><!-- col -->
				<?php if ( isset( $enable_blog_sidebar ) && $enable_blog_sidebar ) { ?>
					<?php get_sidebar(); ?>
				<?php } ?>
            </div><!-- row -->

        </div><!-- #primary -->
    </div><!-- #content -->

	<?php get_footer(); ?>

