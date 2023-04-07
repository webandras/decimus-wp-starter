<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'Decimus_taxonomy_image' ) ) {


    /**
     * Implements custom images for taxonomies
     */
    final class Decimus_taxonomy_image
    {

        private const TEXT_DOMAIN = 'decimus-taxonomy-image';
        private const DECIMUS_TAXONOMY_IMAGE_OPTIONS = 'decimus_taxonomy_image_options';

        // class instance
        private static $instance = null;

        // image settings for all taxonomies (stored in options)
        private mixed $options;

        // taxonomies for which cover images are allowed
        private array $taxonomy_list;

        private string $plugin_path;


        public static function get_instance(): self
        {
            if ( self::$instance === null ) {
                self::$instance = new self();
            }
            return self::$instance;
        }


        private function __construct()
        {
            // load translation files
            add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

            $this->plugin_path = plugins_url() . '/decimus-taxonomy-image/';


            // Only needed for admin pages
            if ( is_admin() ) {
                $this->options = get_option( self::DECIMUS_TAXONOMY_IMAGE_OPTIONS );

                // To which taxonomies to apply taxonomy images
                if ( $this->options ) {
                    $this->taxonomy_list = $this->options['checked_taxonomies'] ?? null;
                }

                if ( isset( $this->taxonomy_list ) && !empty( $this->taxonomy_list ) ) {

                    // Add actions for add and edit forms for each taxonomy type checked by the user
                    foreach ( $this->taxonomy_list as $tax ) {
                        add_action( $tax . '_add_form_fields', array( $this, 'add_category_image' ) );
                        add_action( $tax . '_edit_form_fields', array( $this, 'edit_category_image' ) );
                    }
                }

                // edit_$taxonomy
                add_action( 'edit_term', array( $this, 'category_image_save' ) );
                add_action( 'create_term', array( $this, 'category_image_save' ) );

                // New menu submenu for plugin options in Settings menu
                add_action( 'admin_menu', array( $this, 'options_menu' ) );
            }

        }


        public function __destruct()
        {
        }


        /** Load translation files */
        public function load_text_domain(): void
        {
            // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
            $locale = apply_filters( 'plugin_locale', get_locale(), self::TEXT_DOMAIN );

            load_textdomain( self::TEXT_DOMAIN,
                trailingslashit( \WP_LANG_DIR ) . self::TEXT_DOMAIN . '/' . self::TEXT_DOMAIN . '-' . $locale . '.mo' );
            load_plugin_textdomain( self::TEXT_DOMAIN, false, basename( dirname( __FILE__, 2 ) ) . '/languages/' );
            load_plugin_textdomain( self::TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
        }


        /** Add category/taxonomy image */
        public function add_category_image($taxonomy): void
        { ?>
            <div class="form-field">
                <label for="tag-image"><?php _e( 'Image', self::TEXT_DOMAIN ); ?></label>
                <input type="text" name="tag-image" id="tag-image" value=""/>
                <p class="description"><?php _e( 'Click on the text box to add taxonomy/category image.',
                        self::TEXT_DOMAIN ); ?></p>
            </div>

            <?php $this->add_scripts_and_styles();
        }


        /** Edit category/taxonomy image */
        public function edit_category_image($taxonomy): void
        { ?>
            <tr class="form-field">
                <th scope="row" class="decimus-taxonomy-image-title"><label
                            for="tag-image"><?php _e( 'Image', self::TEXT_DOMAIN ); ?></label></th>
                <td>
                    <?php
                    if ( get_option( '_category_image-' . $taxonomy->term_id ) != '' ) { ?>
                        <img src="<?php echo get_option( '_category_image-' . $taxonomy->term_id ); ?>" width="100"
                             height="100" alt="<?php _e( 'The uploaded category image of the current category',
                            self::TEXT_DOMAIN ) ?>"/>
                        <?php
                    }
                    ?><br/>
                    <input type="text" name="tag-image" id="tag-image"
                           value="<?php echo get_option( '_category_image-' . $taxonomy->term_id ); ?>"/>
                    <p class="description"><?php _e( 'Click on the text box to add taxonomy/category image.',
                            self::TEXT_DOMAIN ); ?></p>
                </td>
            </tr>
            <?php $this->add_scripts_and_styles();
        }


        private function add_scripts_and_styles(): void
        {
            $thickbox_modified = date( 'YmdHi', filemtime( $this->plugin_path . 'admin/js/thickbox.js' ) );
            $thickbox_init_modified = date( 'YmdHi', filemtime( $this->plugin_path . 'admin/js/thickbox-init.js' ) );

            wp_enqueue_script(
                'decimus-taxonomy-image-thickbox-js',
                $this->plugin_path . 'admin/js/thickbox.js',
                array( 'jquery' ),
                $thickbox_modified,
                true
            );

            wp_enqueue_script(
                'decimus-taxonomy-image-thickbox-init-js',
                $this->plugin_path . 'admin/js/thickbox-init.js',
                array( 'jquery' ),
                $thickbox_init_modified,
                true
            );


            $thickbox_css_modified = date( 'YmdHi', filemtime( includes_url() . 'js/thickbox/thickbox.css' ) );
            $decimus_taxonomy_css_modified = date( 'YmdHi',
                filemtime( $this->plugin_path . 'admin/css/decimus-taxonomy-image.css' ) );

            wp_enqueue_style(
                'decimus-taxonomy-image-thickbox-css',
                includes_url() . 'js/thickbox/thickbox.css',
                array(),
                $thickbox_css_modified
            );

            wp_enqueue_style(
                'decimus-taxonomy-image-css',
                $this->plugin_path . 'admin/css/decimus-taxonomy-image.css',
                array(),
                $decimus_taxonomy_css_modified
            );

        }


        public function category_image_save($term_id): void
        {
            if ( isset( $_POST['tag-image'] ) ) {
                update_option( '_category_image-' . $term_id, sanitize_url( $_POST['tag-image'] ) );
            }
        }


        /** Add options submenu */
        public function options_menu(): void
        {
            add_options_page(
                __( 'Decimus Taxonomy Image Settings', self::TEXT_DOMAIN ),
                __( 'Decimus Taxonomy Image', self::TEXT_DOMAIN ),
                'manage_options',
                'decimus-taxonomy-image-options',
                array( $this, 'options' )
            );
            add_action( 'admin_init', array( $this, 'register_settings' ) );
        }


        /** Register plugin settings */
        public function register_settings(): void
        {
            register_setting( self::DECIMUS_TAXONOMY_IMAGE_OPTIONS, self::DECIMUS_TAXONOMY_IMAGE_OPTIONS,
                array( $this, 'options_validate' ) );
            add_settings_section(
                'dti_section',
                __( 'Taxonomy Image settings', self::TEXT_DOMAIN ),
                array( $this, 'section_text' ),
                'decimus-taxonomy-image-options'
            );
            add_settings_field(
                'dti_checked_taxonomies',
                __( 'Image settings', self::TEXT_DOMAIN ),
                array( $this, 'checked_taxonomies' ),
                'decimus-taxonomy-image-options',
                'dti_section'
            );
        }


        /** Settings section description */
        public function section_text(): void
        {
            echo '<p>' .
                __( 'Please select the taxonomies that needs to have a taxonomy images', self::TEXT_DOMAIN )
                . '</p>';
        }


        /** Included taxonomies checkboxes */
        public function checked_taxonomies(): void
        {
            $options = get_option( self::DECIMUS_TAXONOMY_IMAGE_OPTIONS );
            $disabled_taxonomies = array( 'nav_menu', 'link_category', 'post_format' );

            foreach ( get_taxonomies() as $tax ) {
                if ( in_array( $tax, $disabled_taxonomies ) ) {
                    continue;
                } ?>
                <input type="checkbox"
                       name="<?php echo self::DECIMUS_TAXONOMY_IMAGE_OPTIONS ?>[checked_taxonomies][<?php echo $tax ?>]"
                       value="<?php echo $tax ?>" <?php checked( isset( $options['checked_taxonomies'][$tax] ) ); ?> /> <?php echo $tax; ?>
                <br/>
            <?php }
        }


        /** Validating options (currently not doing any validation) */
        public function options_validate($input)
        {
            return $input;
        }


        /** Plugin option page */
        public function options(): void
        {
            if ( !current_user_can( 'manage_options' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.', self::TEXT_DOMAIN ) );
            }
            ?>
            <div class="wrap">

                <h2><?php _e( 'Decimus Taxonomy Image', self::TEXT_DOMAIN ); ?></h2>
                <form method="post" action="options.php">
                    <?php settings_fields( self::DECIMUS_TAXONOMY_IMAGE_OPTIONS ); ?>
                    <?php do_settings_sections( 'decimus-taxonomy-image-options' ); ?>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }


        /** get taxonomy/category image */
        public function get_wp_term_image($term_id)
        {
            return get_option( '_category_image-' . $term_id );
        }


        public function delete_plugin(): void
        {
            delete_option( self::DECIMUS_TAXONOMY_IMAGE_OPTIONS );
        }
    }
}

// instantiate singleton
Decimus_taxonomy_image::get_instance();

