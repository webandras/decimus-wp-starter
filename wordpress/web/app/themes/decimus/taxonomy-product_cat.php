<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Decimus
 */

get_header();
?>

    <div id="content" class="site-content container-fluid side-padding narrow-content py-5 mt-5">
        <div id="primary" class="content-area">

            <!-- Hook to add something nice -->
			<?php decimus_after_primary(); ?>

            <div class="row">
                <div class="col">

                    <main id="main" class="site-main">

                        <!-- Title & Description -->
                        <header class="page-header mb-5">
	                        <?php
	                        if (class_exists('Decimus_taxonomy_image'))
	                        {
                                // movie_types
                                $term = get_queried_object();
		                        $cat_id = $term->term_id;

		                        //It will give category/term image url
		                        $meta_image = Decimus_taxonomy_image::get_wp_term_image($cat_id);

		                        if ($meta_image) { ?>
                                    <img class="mb-2" style="height: 100px; width:100%; object-fit:cover;" src="<?php echo esc_url($meta_image) ?>" alt="<?php the_archive_title(); ?>">
		                        <?php }
	                        }
	                        ?>

                            <h1><?php the_archive_title(); ?></h1>
							<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                        </header>

                        <!-- Grid Layout -->
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php
								$the_field_prefix = Decimus_movie_reviews::FIELD_PREFIX;

								$poster     = get_the_post_thumbnail( get_the_ID(), 'full' );
								$poster_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),
									'full' );

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


                                <div class="card horizontal mb-4">
                                    <div class="row">
                                        <!-- Featured Image-->
										<?php if ( has_post_thumbnail() ) {
											echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail( null,
													'medium' ) . '</div>';
										}
										?>

                                        <div class="col">
                                            <div class="card-body">

                                                <!-- Title -->
                                                <h2 class="blog-post-title">
                                                    <a href="<?php the_permalink(); ?>">
														<?php the_title(); ?>
                                                    </a>
                                                </h2>
                                                <!-- Meta -->
												<?php if ( 'movie_review' === get_post_type() ) : ?>
                                                    <small class="text-muted d-block mb-2">
														<?php
														decimus_date();
														decimus_edit();
														?>
                                                    </small>
												<?php endif; ?>
                                                <!-- Excerpt & Read more -->
                                                <div class="card-text mt-auto">
													<?php the_excerpt(); ?>
                                                    <a class="read-more" href="<?php the_permalink(); ?>"><?php _e( 'Read more »', 'decimus' ); ?></a>
                                                </div>
                                                <hr>

												<?php if ( ! empty( $rating_display ) ) { ?>
                                                    <div class="rating rating-<?php print $rating; ?>">
														<?php print $rating_display; ?>
                                                    </div>
												<?php } ?>

                                                <!-- Tags -->
                                                <div class="movie-meta">

													<?php if ( ! empty( $director ) ) { ?>
                                                        <div class="director">
                                                            <label>Directed by:</label> <?php print $director; ?>
                                                        </div>
													<?php } ?>

													<?php if ( ! empty( $movie_type ) ) { ?>
                                                        <div class="types">
                                                            <label>Genre:</label> <?php print $movie_type; ?>
                                                        </div>
													<?php } ?>

													<?php if ( ! empty( $year ) ) { ?>
                                                        <div class="release-year">
                                                            <label>Release Year:</label> <?php print $year; ?>
                                                        </div>
													<?php } ?>

                                                </div>

												<?php if ( ! empty( $imdb_link ) ) { ?>
                                                    <div class="link">
                                                        <a href="<?php print $imdb_link; ?>" target="_blank">View on
                                                            IMDb »</a>
                                                    </div>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
							<?php endwhile; ?>
						<?php endif; ?>

                        <!-- Pagination -->
                        <div>
							<?php decimus_pagination(); ?>
                        </div>

                    </main><!-- #main -->

                </div><!-- col -->

				<?php get_sidebar(); ?>
            </div><!-- row -->

        </div><!-- #primary -->
    </div><!-- #content -->

<?php
get_footer();
