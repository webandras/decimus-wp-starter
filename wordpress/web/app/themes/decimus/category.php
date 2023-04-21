<?php
/**
 * The template for displaying category pages
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
	                            $category = get_category( get_query_var( 'cat' ) );
	                            $cat_id = $category->cat_ID;

	                            $meta_image = Decimus_taxonomy_image::get_wp_term_image($cat_id);
	                            //It will give category/term image url

                                if ($meta_image) { ?>
                                    <img class="mb-2" style="height: 100px; width:100%; object-fit:cover;" src="<?php echo esc_url($meta_image) ?>" alt="<?php single_cat_title(); ?>">
                                <?php }
                            }
                            ?>



                            <h1><?php single_cat_title(); ?></h1>
                            <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
                        </header>

                        <!-- Grid Layout -->
                        <?php if ( have_posts() ) : ?>
                            <?php while (have_posts()) : the_post(); ?>
                                <div class="card horizontal mb-4">
                                    <div class="row">
                                        <!-- Featured Image-->
                                        <?php if ( has_post_thumbnail() )
                                            echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail(null, 'medium') . '</div>';
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
                                                <?php if ( 'post' === get_post_type() ) : ?>
                                                    <small class="text-muted d-block mb-2">
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
                                                                               href="<?php the_permalink(); ?>"><?php _e('Read more »', 'decimus'); ?></a>
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

                    </main><!-- #main -->

                </div><!-- col -->

                <?php get_sidebar(); ?>
            </div><!-- row -->

        </div><!-- #primary -->
    </div><!-- #content -->

<?php
get_footer();
