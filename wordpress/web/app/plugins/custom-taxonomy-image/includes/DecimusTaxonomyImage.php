<?php

namespace Guland\DecimusTaxonomyImage;

use Guland\DecimusTaxonomyImage\API\Route as Route;
use Guland\DecimusTaxonomyImage\Interface\TaxonomyImageInterface;

// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;


/**
 * Implements custom images for taxonomies
 */
final class DecimusTaxonomyImage implements TaxonomyImageInterface
{
    use Route;

    // class instance
    private static $instance;

    public mixed $options;
    public array $taxonomy_list;

    /**
     *
     * @return self $instance
     */
    public static function get_instance(): self
    {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return void
     */
    private function __construct()
    {
        // load translation files
        add_action('plugins_loaded', array($this, 'load_text_domain'));

        $this->options = get_option(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS);

        // which taxonomies to apply taxonomy images
        if ( $this->options ) {
            $this->taxonomy_list = $this->options['checked_taxonomies'] ?? null;
        }

        if ( isset($this->taxonomy_list) && !empty($this->taxonomy_list) ) {

            // Add actions for add and edit forms for each taxonomy type checked by the user
            foreach ($this->taxonomy_list as $tax) {
                add_action($tax . '_add_form_fields', array($this, 'add_category_image'));
                add_action($tax . '_edit_form_fields', array($this, 'edit_category_image'));
            }
        }

        // edit_$taxonomy
        add_action('edit_term', array($this, 'category_image_save'));
        add_action('create_term', array($this, 'category_image_save'));


        // New menu submenu for plugin options in Settings menu
        add_action('admin_menu', array($this, 'options_menu'));

        // Add REST API route to GET the taxonomy image from options table
        add_action('rest_api_init', array($this, 'get_taxonomy_image_route'));
    }


    /**
     * @return void
     */
    public function __destruct()
    {
    }


    /**
     * Load translation files
     *
     * @return void
     */
    public function load_text_domain(): void
    {
        // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
        $locale = apply_filters('plugin_locale', get_locale(), self::TEXT_DOMAIN);

        load_textdomain(self::TEXT_DOMAIN, trailingslashit(\WP_LANG_DIR) . self::TEXT_DOMAIN . '/' . self::TEXT_DOMAIN . '-' . $locale . '.mo');
        load_plugin_textdomain(self::TEXT_DOMAIN, false, basename(dirname(__FILE__, 2)) . '/languages/');
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/lang/');
    }


    // Function to add category/taxonomy image
    public function add_category_image($taxonomy): void
    { ?>
        <div class="form-field">
            <label for="tag-image"><?php _e('Image', self::TEXT_DOMAIN); ?></label>
            <input type="text" name="tag-image" id="tag-image" value=""/>
            <p class="description"><?php _e('Click on the text box to add taxonomy/category image.', self::TEXT_DOMAIN); ?></p>
        </div>

        <?php $this->add_scripts_styles();
    }


    // Function to edit category/taxonomy image
    public function edit_category_image($taxonomy): void
    { ?>
        <tr class="form-field">
            <th scope="row" style="vertical-align: top;"><label
                        for="tag-image"><?php _e('Image', self::TEXT_DOMAIN); ?></label></th>
            <td>
                <?php
                if ( get_option('_category_image-' . $taxonomy->term_id) != '' ) { ?>
                    <img src="<?php echo get_option('_category_image-' . $taxonomy->term_id); ?>" width="100"
                         height="100" alt="the uploaded category image of the current category"/>
                    <?php
                }
                ?><br/>
                <input type="text" name="tag-image" id="tag-image"
                       value="<?php echo get_option('_category_image-' . $taxonomy->term_id); ?>"/>
                <p class="description"><?php _e('Click on the text box to add taxonomy/category image.', self::TEXT_DOMAIN); ?></p>
            </td>
        </tr>
        <?php $this->add_scripts_styles();
    }


    public function add_scripts_styles(): void
    { ?>

        <script type="text/javascript"
                src="<?php echo plugins_url(); ?>custom-taxonomy-image/includes/thickbox.js"></script>
        <link rel='stylesheet' id='thickbox-css' href='<?php echo includes_url(); ?>js/thickbox/thickbox.css'
              type='text/css' media='all'/>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var fileInput = '';
                jQuery('#tag-image').on('click', null,
                    function () {
                        fileInput = jQuery('#tag-image');
                        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                        return false;
                    });
                window.original_send_to_editor = window.send_to_editor;
                window.send_to_editor = function (html) {
                    if (fileInput) {
                        fileurl = jQuery('img', html).attr('src');
                        if (!fileurl) {
                            fileurl = jQuery(html).attr('src');
                        }
                        jQuery(fileInput).val(fileurl);

                        tb_remove();
                    } else {
                        window.original_send_to_editor(html);
                    }
                };
            });
        </script>
    <?php }


    public function category_image_save($term_id): void
    {
        if ( isset($_POST['tag-image']) ) {
            update_option('_category_image-' . $term_id, sanitize_url($_POST['tag-image']));
        }
    }


    /**
     * Add options submenu
     * @return void
     */
    public function options_menu(): void
    {
        add_options_page(
            __('Decimus Taxonomy Image Settings', self::TEXT_DOMAIN),
            __('Decimus Taxonomy Image', self::TEXT_DOMAIN),
            'manage_options',
            'decimus-taxonomy-image-options',
            array($this, 'options')
        );
        add_action('admin_init', array($this, 'register_settings'));
    }

    // Register plugin settings
    public function register_settings(): void
    {
        register_setting(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS, self::DECIMUS_TAXONOMY_IMAGE_OPTIONS, array($this, 'options_validate'));
        add_settings_section(
            'dti_section',
            __('Taxonomy Image settings', self::TEXT_DOMAIN),
            array($this, 'section_text'),
            'decimus-taxonomy-image-options'
        );
        add_settings_field(
            'dti_checked_taxonomies',
            __('Image settings', self::TEXT_DOMAIN),
            array($this, 'checked_taxonomies'),
            'decimus-taxonomy-image-options',
            'dti_section'
        );
    }

    // Settings section description
    public function section_text(): void
    {
        echo '<p>' .
            __('Please select the taxonomies that needs to have a taxonomy images')
            . '</p>';
    }


    // Included taxonomies checkboxes
    public function checked_taxonomies(): void
    {
        $options = get_option(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS);

        $disabled_taxonomies = array('nav_menu', 'link_category', 'post_format');
        foreach (get_taxonomies() as $tax) : if ( in_array($tax, $disabled_taxonomies) ) continue; ?>
            <input type="checkbox" name="<?php echo self::DECIMUS_TAXONOMY_IMAGE_OPTIONS ?>[checked_taxonomies][<?php echo $tax ?>]"
                   value="<?php echo $tax ?>" <?php checked(isset($options['checked_taxonomies'][$tax])); ?> /> <?php echo $tax; ?>
            <br/>
        <?php endforeach;
    }


    // Validating options
    public function options_validate($input)
    {
        return $input;
    }


    // Plugin option page
    public function options(): void
    {
        if ( !current_user_can('manage_options') ) {
            wp_die('You do not have sufficient permissions to access this page.');
        }
        $options = get_option(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS);
        ?>
        <div class="wrap">

            <h2><?php _e('Decimus Taxonomy Image', self::TEXT_DOMAIN); ?></h2>
            <form method="post" action="options.php">
                <?php settings_fields(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS); ?>
                <?php do_settings_sections('decimus-taxonomy-image-options'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }


    // get taxonomy/category image
    public function get_wp_term_image($term_id)
    {
        return get_option('_category_image-' . $term_id);
    }

    public function delete_plugin()
    {
        delete_option(self::DECIMUS_TAXONOMY_IMAGE_OPTIONS);
    }
}
