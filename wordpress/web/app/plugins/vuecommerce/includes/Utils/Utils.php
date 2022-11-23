<?php

namespace Guland\VueCommerceBlocks\Utils;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

use WP_REST_REQUEST;

trait Utils
{

    /**
     * Register custom fields for GET requests. Extend WP REST API
     *
     * Ref: https://developer.wordpress.org/reference/functions/register_rest_field/
     */
    public function extend_posts_api_response(): void
    {
        register_rest_field(
            array('post'), // The post type.
            'vue_meta', // The name of the new key.
            array(
                'get_callback' => array($this, 'get_post_meta_fields'),
                'update_callback' => null,
                'schema' => null,
            )
        );
    }

    /**
     * GET callback for the "vue_meta" field defined above.
     * Make additional fields available in the response.
     *
     * @param array $post_object Details of the current post.
     * @param string $field_name Field Name set in register_rest_field().
     * @param WP_REST_Request $request Current request.
     *
     * @return array
     */
    public function get_post_meta_fields(array $post_object, string $field_name, WP_REST_Request $request): array
    {
        $term_ids = array();
        $term_names = array();
        $term_links = array();

        $post_id = $post_object['id']; // get the post id.
        $post_categories = get_the_category($post_id);


        foreach ($post_categories as $category_id) {
            $term_data = get_category($category_id);
            $term_name = $term_data->category_nicename;
            $term_url = get_term_link($term_data->term_id, $term_data->taxonomy);
            $term_link = '<a href="' . $term_url . '">' . $term_name . '</a>';

            $term_ids[] = $term_data->term_id;
            $term_names[] = $term_data->name;
            $term_links[] = $term_link;
        }

        // add categories, custom excerpt, featured image to the api response.
        $img_id = get_post_thumbnail_id($post_id);
        // $img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);

        // return data to the get_callback.
        // this data will be made available in the key "vue_meta".
        return array(
            'custom_excerpt' => wp_trim_words(
                $post_object['excerpt']['rendered'],
                25,
                ' &hellip;'
            ),
            'term_ids' => $term_ids,
            'term_names' => $term_names,
            'term_links' => $term_links,
            'featuredmedia_alt' => get_post_meta(
                $img_id,
                '_wp_attachment_image_alt',
                true
            ),
            'featuredmedia_url' => get_the_post_thumbnail_url(
                $post_id,
                'full'
            ),
        );

    }
}
