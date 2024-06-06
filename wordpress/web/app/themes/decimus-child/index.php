<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Decimus
 */

get_header();
?>
    <div class="front-slider bg-light mb-6">
        <!-- Header -->
        <div class="decimus-header pt-5 text-center">
            <h1 class="display-1"><?php bloginfo( 'name' ); ?></h1>
            <p class="lead px-4 px-lg-0">
                A Bootstrap 5 WordPress Theme based on the Bootscore 5 theme. <?php bloginfo( 'description' ); ?></p>
        </div>

        <div class="decimus-header pt-5 text-center">
            <h1 class="display-1"><?php bloginfo( 'name' ); ?></h1>
            <p class="lead px-4 px-lg-0">A Bootstrap 5 WordPress Theme based on the Bootscore 5 theme 2</p>
        </div>

        <div class="decimus-header pt-5 text-center">
            <h1 class="display-1"><?php bloginfo( 'name' ); ?></h1>
            <p class="lead px-4 px-lg-0">...with Vue.js Theme Admin Settings</p>
        </div>
    </div>

    <div id="content" class="site-content container-fluid narrow-content pb-5 mt-2">
        <div id="primary" class="content-area">

            <!-- Hook to add something nice -->
			<?php decimus_after_primary(); ?>

            <main id="main" class="site-main">

                <!-- Sticky Post -->
				<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
                    <div class="row">
                        <div class="col">
							<?php
							$args      = array(
								'posts_per_page'      => 2,
								'post__in'            => get_option( 'sticky_posts' ),
								'ignore_sticky_posts' => 2,
							);
							$the_query = new WP_Query( $args );
							if ( $the_query->have_posts() ) :
								while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                        <div class="card horizontal mb-4">
                                            <div class="row">
                                                <!-- Featured Image-->
												<?php if ( has_post_thumbnail() ) { ?>
                                                <div class="card-img-left col-md-6 col-lg-4">
                                                    <!-- Featured -->
                                                    <div class="badge bg-danger position-absolute"><span class=""><i
                                                                    class="fas fa-star"></i></i></span></div>
													<?php echo get_the_post_thumbnail( null, 'medium' ) . '</div>';
													} ?>
                                                    <div class="col">
                                                        <div class="card-body <?php echo has_post_thumbnail() === true ? '' : 'card__without_cover' ?>">

															<?php if ( has_post_thumbnail() === true ) { ?>
                                                                <!-- Category badge -->
                                                                <div class="post-badge">
																	<?php decimus_category_badge(); ?>
                                                                </div>
															<?php } ?>

                                                            <!-- Title -->
                                                            <h2 class="blog-post-title mt-0 mt-lg-4">
                                                                <a href="<?php the_permalink(); ?>">
																	<?php the_title(); ?>
                                                                </a>
                                                            </h2>

                                                            <!-- Meta -->
															<?php if ( 'post' === get_post_type() ) : ?>
                                                                <small class="disabled mb-3 d-inline-block">
																	<?php
																	decimus_date();
																	decimus_author();
																	decimus_comments();
																	decimus_edit();
																	?>
                                                                </small>
															<?php endif; ?>
                                                            <!-- Excerpt & Read more -->
                                                            <div class="card-text mt-auto">
																<?php the_excerpt(); ?> <a class="read-more"
                                                                                           href="<?php the_permalink(); ?>"><?php _e( 'Read more »',
																		'decimus' ); ?></a>
                                                            </div>

                                                            <div class="mt-4">
                                                                <!-- Tags -->
																<?php decimus_tags(); ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                    </article>
								<?php
								endwhile;
							endif;
							wp_reset_postdata();
							?>
                        </div>
                        <!-- col -->
                    </div>
                    <!-- row -->
				<?php endif; ?>
                <!-- Post List -->
                <div class="row">
                    <div class="col col-md-8 col-lg-9 col-xxl-9">

                        <!-- Grid Layout -->
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<?php if ( is_sticky() ) {
									continue;
								} //ignore sticky posts?>
                                <div class="card horizontal mb-4">
                                    <div class="row">
                                        <!-- Featured Image-->
										<?php if ( has_post_thumbnail() ) {
											echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail( null,
													'medium' ) . '</div>';
										}
										?>
                                        <div class="col">
                                            <div class="card-body <?php echo has_post_thumbnail() === true ? '' : 'card__without_cover' ?>">
												<?php if ( has_post_thumbnail() === true ) { ?>
                                                    <!-- Category badge -->
                                                    <div class="post-badge">
														<?php decimus_category_badge(); ?>
                                                    </div>
												<?php } ?>

                                                <!-- Title -->
                                                <h2 class="blog-post-title">
                                                    <a href="<?php the_permalink(); ?>">
														<?php the_title(); ?>
                                                    </a>
                                                </h2>

                                                <!-- Meta -->
												<?php if ( 'post' === get_post_type() ) : ?>
                                                    <small class="disabled mb-3 d-inline-block">
														<?php
														decimus_date();
														decimus_author();
														decimus_comments();
														decimus_edit();
														?>
                                                    </small>
												<?php endif; ?>
                                                <!-- Excerpt & Read more -->
                                                <div class="card-text mt-auto">
													<?php the_excerpt(); ?> <a class="read-more"
                                                                               href="<?php the_permalink(); ?>"><?php _e( 'Read more »',
															'decimus' ); ?></a>
                                                </div>

                                                <div class="mt-4">
                                                    <!-- Tags -->
													<?php decimus_tags(); ?>
                                                </div>

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

                    </div>
                    <!-- col -->
					<?php get_sidebar(); ?>
                </div>
                <!-- row -->
            </main><!-- #main -->

        </div><!-- #primary -->
    </div><!-- #content -->
<?php
get_footer();
