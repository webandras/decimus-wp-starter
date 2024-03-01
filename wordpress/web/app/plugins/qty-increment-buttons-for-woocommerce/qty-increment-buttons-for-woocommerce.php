<?php
/*
 * Plugin Name: Qty Increment Buttons for WooCommerce
 * Description: Adds professionally looking "-" and "+" buttons around product quantity field, on product and cart page.
 * Version: 2.7.6
 * Author: taisho
 * WC requires at least: 3.0.0
 * WC tested up to: 4.4.1
 * Modified by András Gulácsi in 2024
 * Compatible with PHP up to: 8.1
 * Compatible with WooCommerce up to: 8.6.1
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

register_activation_hook( __FILE__, 'qib_activate' );
function qib_activate() {  
	// Prevent plugin activation if the minimum PHP version requirement is not met.
	if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		$msg = '<p><strong>Qty Increment Buttons for WooCommerce</strong> requires PHP version 5.4 or greater. Your server runs ' . PHP_VERSION . '.</p>';
		wp_die( $msg, 'Plugin Activation Error',  array( 'response' => 200, 'back_link' => TRUE ) );
	}
	// Store time of first plugin activation (add_option does nothing if the option already exists).
	add_option( 'qib_first_activate', time());
}

// Create settings class.
class qib_settings {	
	
	private $options;	
	private $settings_page_name;
	private $settings_menu_name;

    private $settings_list;

    private $plugin_hook_suffix;

    public function __construct() {
		
		if ( is_admin() ) {
		
			$this->settings_page_name = 'qty-increment-buttons';
			$this->settings_menu_name = 'Qty Increment Buttons';	
			
			// Initialize and register settings. 
			add_action( 'admin_init', [ $this, 'display_options' ] );
			// Add settings page.
			add_action( 'admin_menu', [ $this, 'add_settings_page' ] );		
			// Add settings link to plugins page.
			add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_settings_link' ] );
			// Scripts for settings page - dynamically show / hide based on checked options.
			add_action( 'admin_enqueue_scripts',  [ $this, 'admin_enqueue_scripts' ] );
			// Display a notice encouraging to rate the plugin if not dismissed.
			include_once( 'includes/qib-feedback-notice.php' );
			
		}
		
		$this->settings_list = [
			
			'qib_all_pages' 			=> [ 'val' => false, 	'title' => __( 'Load on all pages', 'qty-increment-buttons-for-woocommerce' ), 	'type' => 'checkbox', 	'tab' => 'configuration',
											 'descr' => __( 'Check if the plugin doesn\'t work correctly on your website out of the box.', 'qty-increment-buttons-for-woocommerce' ),
											 'info' => __( 'Turn this setting on to enable the plugin on pages other than product, cart, checkout, shop, category and correctly display Quick Views initiated from these pages.', 'qty-increment-buttons-for-woocommerce' ),
											 'tip' => true],
			'qib_archive_display' 		=> [ 'val' => __( 'None', 'qty-increment-buttons-for-woocommerce' ), 	'title' => __( 'Archives display', 'qty-increment-buttons-for-woocommerce' ),		'type' => 'select', 	'tab' => 'configuration',
											 'descr' => __( 'Archive pages that should display quantity input field and increment buttons. Doesn\'t affect variable products and products sold individually.', 'qty-increment-buttons-for-woocommerce' ),
											 'options' => [ __( 'None', 'qty-increment-buttons-for-woocommerce' ), __( 'Shop', 'qty-increment-buttons-for-woocommerce' ), __( 'Category', 'qty-increment-buttons-for-woocommerce' ), __( 'Shop & Category', 'qty-increment-buttons-for-woocommerce' ) ],
											 'tip' => true ],
			'qib_archive_after' 		=> [ 'val' => __( 'Before Add to cart', 'qty-increment-buttons-for-woocommerce' ), 'title' => __( 'Archive position', 'qty-increment-buttons-for-woocommerce' ), 	'type' => 'select', 'tab' => 'configuration',
											 'descr' => __( 'Position of quantity input field and increment buttons in HTML structure of archives. Visual position may be different depending on float. "After Add to cart" is the only way to keep all these elements in one line on some themes.', 'qty-increment-buttons-for-woocommerce' ),									
											 'options' => [ __( 'Before Add to cart', 'qty-increment-buttons-for-woocommerce' ), __( 'After Add to cart', 'qty-increment-buttons-for-woocommerce' ) ],
											 'arg' => ( is_bool( get_option( 'qib_settingz' ) ) || get_option( 'qib_settingz' )['qib_archive_display'] == 'None' ) ? [ 'class' => 'hidden' ] : null,
											 'tip' => true ],
			'qib_auto_table' 			=> [ 'val' => false, 	'title' => __( 'Auto cart columns', 'qty-increment-buttons-for-woocommerce' ), 	'type' => 'checkbox', 	'tab' => 'configuration',
											 'descr' => __( 'Check if you notice buttons overflowing to next column or breaking to next line on the cart page.', 'qty-increment-buttons-for-woocommerce' ),
											 'info' => __( 'Auto sizes columns on the cart page. Already used by a vast majority of themes. Alternatively merge buttons and reduce widths to fit into fixed-size column.', 'qty-increment-buttons-for-woocommerce' ),
											 'tip' => true ],
			'qib_merge_buttons' 		=> [ 'val' => true, 	'title' => __( 'Merge buttons', 'qty-increment-buttons-for-woocommerce' ), 		'type' => 'checkbox', 	'tab' => 'configuration',
											 'descr' => __( 'Remove space between increment buttons and quantity input field, visually merging these elements.', 'qty-increment-buttons-for-woocommerce' ) ],											
			'qib_cart_align' 			=> [ 'val' => __( 'Center', 'qty-increment-buttons-for-woocommerce' ), 'title' => __( 'Cart page align', 'qty-increment-buttons-for-woocommerce' ),		'type' => 'select', 	'tab' => 'configuration',
											 'descr' => __( 'Horizontal alignment in cart page quantity column. Affects input field and increment buttons. Desktop view only.', 'qty-increment-buttons-for-woocommerce' ),
											 'options' => [ __( 'Left', 'qty-increment-buttons-for-woocommerce' ), __( 'Center', 'qty-increment-buttons-for-woocommerce' ), __( 'Right', 'qty-increment-buttons-for-woocommerce' ) ],
											 'tip' => true ],
			'qib_button_style' 			=> [ 'val' => __( 'Silver', 'qty-increment-buttons-for-woocommerce' ), 'title' => __( 'Button style', 'qty-increment-buttons-for-woocommerce' ), 			'type' => 'select', 	'tab' => 'configuration',
											 'descr' => __( 'Includes button colors (background, font, hover background, focus outline), button styles (focus outline) and quantity input field colors (border). You can adjust this style within your child theme CSS or additional CSS if your theme allows it.', 'qty-increment-buttons-for-woocommerce' ),											 
											 'options' => [ __( 'Black', 'qty-increment-buttons-for-woocommerce' ), __( 'Blue', 'qty-increment-buttons-for-woocommerce' ), __( 'Brown', 'qty-increment-buttons-for-woocommerce' ), __( 'Orange', 'qty-increment-buttons-for-woocommerce' ), __( 'Red', 'qty-increment-buttons-for-woocommerce' ), __( 'Silver', 'qty-increment-buttons-for-woocommerce' ) ],
											 'tip' => true ],											 
			'qib_button_height' 		=> [ 'val' => 35, 		'title' => __( 'Button height', 'qty-increment-buttons-for-woocommerce' ), 		'type' => 'number', 	'tab' => 'configuration',
											 'descr' => __( 'Recommended 25-40 pixels. Quantity input field and Add to cart button will have the same height.', 'qty-increment-buttons-for-woocommerce' ),
											 'arg' => [ 'class' => 'qib_sizes' ] ],
			'qib_button_width' 			=> [ 'val' => 30, 		'title' => __( 'Button width', 'qty-increment-buttons-for-woocommerce' ), 		'type' => 'number', 	'tab' => 'configuration',
											 'descr' => __( 'Recommended 25-40 pixels.', 'qty-increment-buttons-for-woocommerce' ),
											 'arg' => [ 'class' => 'qib_sizes' ] ],
			'qib_quantity_width' 	=> 	   [ 'val' => 45, 		'title' => __( 'Quantity field width', 'qty-increment-buttons-for-woocommerce' ),'type' => 'number', 	'tab' => 'configuration',
											 'descr' => __( 'Recommended 35-50 pixels.', 'qty-increment-buttons-for-woocommerce' ),
											 'arg' => [ 'class' => 'qib_sizes' ] ],
		];

        /* Declare compatibility with High Performance Order Storage (HPOS), because the plugin is not touching this functionality. */
	    add_action( 'before_woocommerce_init', function() {
		    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			    \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		    }
	    } );
	}
	   
	public function display_options() {
		
		$active_tab = 'configuration';
		
		// Option group (section ID), option name (one row in database with an array for all settings), args (sanitized)
		register_setting( 'qib_settingz', 'qib_settingz', [ $this, 'sanitize' ] );	
		// ID / title / cb / page
        add_settings_section( 'configuration_section', null, [ $this, 'print_section_info' ], $this->settings_page_name );
		
		// Add setting fields from 'settings_list' array based on an active tab.
		$arr = $this->settings_list;
		foreach($arr as $key => $item) {
			if ( $active_tab == $arr[$key]['tab']  ) {
				
				$args = [ 'name' => $key ];
				if ( isset ( $arr[$key]['arg'] ) ) $args+= $arr[$key]['arg'];				
				
				if ( isset ( $arr[$key]['tip'] ) ) {					
					$tip = '<span class="qib_help_tip"><span>' . ( isset ( $arr[$key]['info'] ) ? $arr[$key]['info'] : $arr[$key]['descr'] ) . '</span></span>';
				} else {
					$tip = '';
				}
				
				add_settings_field (
					$key, // ID
					$arr[$key]['title'] . $tip, // Title and a help tip icon if exists
					[ $this, 'qib_print_field' ], // Callback
					$this->settings_page_name, // Page
					$active_tab . '_section', // Section ID
					$args // Optional args	
				);
			}
		}
	}		

    public function add_settings_page() {
        // This page will be under "Settings"
        $this->plugin_hook_suffix = add_options_page(
            'Settings Admin', $this->settings_menu_name, 'manage_options', $this->settings_page_name, [ $this, 'create_settings_page' ]
        );
    }
	
	public function add_settings_link( $links ) {
		$links = array_merge( [
			'<a href="' . esc_url( admin_url( '/options-general.php?page=' . $this->settings_page_name ) ) . '">' . __( 'Settings' ) . '</a>'
		], $links );
		return $links;
	}
	
	public function admin_enqueue_scripts ( $page ) {	
		if ( $page !== $this->plugin_hook_suffix )
			return;
		$plugin_slug = 'qty-increment-buttons-for-woocommerce';
		$plugin_short_slug = 'qty-increment-buttons';	
		wp_enqueue_script( $plugin_short_slug . '-admin', plugins_url() . '/' . $plugin_slug . '/js/' . $plugin_slug . '-admin' . '.js', [ 'jquery' ], '', true );	
	}
	
	/**
	 * Get the option that is saved or the default.
	 *
	 * @param string $index. The option we want to get.
	 */
	public function qib_get_settings( $index = false ) {
		
		$arr = $this->settings_list;
		foreach($arr as $key => $item) {
			$defaults[$key] = $arr[$key]['val'];
		}
		
		$settings = get_option( 'qib_settingz' );
		
		// Change deprecated "Theme style" to "Silver" if previous settings exist and if used.		
		if ( ! is_bool ( get_option( 'qib_settingz' ) ) ) {			
			if ( $settings['qib_button_style'] == 'Theme style' ) {		
				$settings['qib_button_style'] = 'Silver';
				update_option( 'qib_settingz', $settings );
			}
		}
			
		$settings = get_option( 'qib_settingz', $defaults );		

		if ( $index && isset( $settings[ $index ] ) ) {
			return $settings[ $index ];
		}

		return $settings;
	}
		
    public function create_settings_page() {
		$this->options = $this->qib_get_settings();
		
        ?>
        <div class="wrap">				
			<div class="qib_admin_links">
				<a href="https://wordpress.org/support/plugin/qty-increment-buttons-for-woocommerce/" target="_blank"><?php esc_html_e( 'Support & suggestions', 'qty-increment-buttons-for-woocommerce' );?></a>
				|
				<a href="https://wordpress.org/support/plugin/qty-increment-buttons-for-woocommerce/reviews/?rate=5#new-post" target="_blank"><?php esc_html_e( 'Rate this plugin', 'qty-increment-buttons-for-woocommerce' );?></a>				
			</div>
			<h1>Qty Increment Buttons for WooCommerce</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'qib_settingz' );
                do_settings_sections( $this->settings_page_name );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
		
		$new_input = $this->qib_get_settings();
		$arr = $this->settings_list;	
		
		foreach($arr as $key => $item) {		
			switch ( $arr[$key]['type'] ) {
				case 'checkbox' :
					if( isset( $input[$key] ) ) {
						$new_input[$key] = ( $input[$key] == 1 ? 1 : 0 );
					} else {
						$new_input[$key] = 0;
					}
					break;
				case 'text' :
				case 'select' :
					if( isset( $input[$key] ) )
						$new_input[$key] = sanitize_text_field( $input[$key] );
					break;
				case 'number' :
					if( isset( $input[$key] ) )
						$new_input[$key] = absint( $input[$key] );
					break;					 
			}		
		}
	
        return $new_input;
    }	

    /** 
     * Print the Section text
     */
	
    public function print_section_info() {
		return;
        // print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
	 
	public function qib_print_field(array $args) {
		 
		$field = $args['name'];
	
		switch ( $this->settings_list[$field]['type'] ) {
			
			case 'checkbox' :
			
				$fieldset = 
					'<fieldset>
						<label><input id="%1$s" type="checkbox" name="qib_settingz[%1$s]" value="1" %2$s />%3$s</label>
					</fieldset>';			

				printf (
					$fieldset,
					esc_attr( $field ),
					isset( $this->options[$field] ) && ( 1 == $this->options[$field] )  ? 'checked="checked" ':'',
					$this->settings_list[$field]['descr']
				);			
				break;
				
			case 'select' :
				
				$options = $this->settings_list[$field]['options'];
				foreach($options as $item) {			
					$items[ $item ] =  $item;
				}			
				
				printf (					
					'<select id="%1$s" name="qib_settingz[%1$s]">',
					esc_attr( $field )
				);
				
				foreach( $items as $value => $option ) {         
					printf (
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $value ),
						selected( $value, $this->options[$field], false ),
						esc_html( $option )
					);
				}
				
				printf (
					'</select>'					
				);
				break;
			
			case 'text' :
			case 'number' :
			
				$fieldset = '<input type="text" id="%1$s" name="qib_settingz[%1$s]" value="%2$s" />';
				$fieldset .= isset ( $this->settings_list[$field]['descr'] ) ? '<p class="description">%3$s</p>' : '';				
				$descr = isset ( $this->settings_list[$field]['descr'] ) ? $this->settings_list[$field]['descr'] : '';
			
				printf (
					$fieldset,
					esc_attr( $field ),
					isset( $this->options[$field] ) ? esc_attr( $this->options[$field]) : '',
					$descr
				);			
				break;
		}	
	
	}
}

$qib_settingz_page = new qib_settings();

if( is_admin() ) {	
	
	// Change plugin settings page CSS.
	add_action( 'admin_head-settings_page_qty-increment-buttons', 'qib_settings_style' );	
	function qib_settings_style() {
		
		$my_style = '
		input[type=checkbox], input[type=radio] {
			margin: -4px 8px 0 0;
		}
		input, select {
			margin: 1px 1px 1px 0;
		}	
		.form-table th {
			padding: 10px 10px 10px 0;
			width: 150px;
		}
		.form-table td {
			padding: 5px 10px;
		}
		.form-table td p {
			margin-bottom: 6px;
		}
		.wrap h1 {		
			padding: 9px 0;
		}	
		.qib_admin_links {
			float: right;
			margin: 15px 50px 15px 0;
			vertical-align: middle;
		}
		.qib_sizes input {
			float: left;
			width: 35px;
			text-align: center;	
		}
		.qib_sizes p.description {
			float: left;
			margin-left: 10px;
		}
		.qib_help_tip::after {
			font-family: dashicons;
			content:  "\f223";
			float: right;
			vertical-align: middle;
			font-weight: normal;
		}
		.qib_help_tip span {	
			display: none;
			position: absolute;			
			left: 162px;
			margin-top: 7px;
			padding: 7px;
			box-sizing: border-box;
			border: 2px #de930a solid;
			background-color: #fff6b4;
			font-size: 13px;
			font-weight: normal;
		}
		.qib_help_tip:hover :nth-child(1){
			display: block;		
		}
		';	
		
		echo '<style>' . qib_minify($my_style) . '</style>';
		
	}

}

// Only if WooCommerce is active (doesn't work for Github installations which have version number in folder name).
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( get_site_option('active_sitewide_plugins') && array_key_exists( 'woocommerce/woocommerce.php', get_site_option('active_sitewide_plugins') ) ) ) {
		
	// Remove plugin prefix and dash "_" from argument keys.
	function qib_replaceArrayKeys( $array ) {
		$replacedKeys = str_replace('qib_', '', array_keys( $array ));
		return array_combine( $replacedKeys, $array );
	}	
	$args = qib_replaceArrayKeys ( $qib_settingz_page->qib_get_settings() );
	
	// Override template 'woocommerce/templates/global/quantity-input.php' that is used to create WooCommerce quantity field. Adds plain quantity buttons.
	// Needs to be outside the template_redirect to work with AJAX of Quick View plugins.
	// The template file can be replaced through filter. More details in \template\global\quantity-input.php.
	add_filter('woocommerce_locate_template', 'qib_override_default_template', 9999, 3);
	// Run plugin.
	add_action('template_redirect', function() use ( $args ) { qty_increment_buttons( $args ); }, 1);
	function qty_increment_buttons( $args ) {
		
		if ( $args['all_pages'] != 1 ) {			
			// Must be a product, cart, checkout, shop or category page.
			if (! is_product() && ! is_cart() && ! is_checkout() && ! is_shop() && ! is_product_category()) return;
		}
	
		// Apply plugin styles through CSS in page head.
		add_action( 'wp_head', function() use ( $args ) { qib_apply_styles( $args ); } );
		// Enqueue js script inline using wc_enqueue_js to make the buttons actually work.
		add_action( 'template_redirect', 'qib_enqueue_script' );
		
		// Handle archive display options.
		if ( $args['archive_display'] == 'None' ) return;
		if ( $args['archive_display'] == 'Shop' &&  is_product_category() ) return;
		if ( $args['archive_display'] == 'Category' && is_shop() ) return;		
		
		// Check what theme is currently active.
		$theme = wp_get_theme()->name;
		$parent_theme = wp_get_theme()->parent_theme;

		// Add quantity field on the archive page before or after Add to cart button that has 10 priority, depending on 'qib_archive_after' setting.
		// For some themes that change archive pages significantly, their own hooks are used instead of standard one.
		// Product must be purchasable, not sold individually, in stock, not variable and not bundle.		
		if ( $args['qib_archive_after'] = 'Before Add to cart' ) {
			if ( $theme == 'Astra' || $parent_theme == 'Astra' ) {
				add_action( 'astra_woo_shop_add_to_cart_before', 'qib_quantity_field_archive', 9 );
			} elseif ( $theme == 'OceanWP' || $parent_theme == 'OceanWP' ) {		
				add_action( 'ocean_before_archive_product_add_to_cart_inner', 'qib_quantity_field_archive' );			
			} elseif ( $theme == 'WooVina' || $parent_theme == 'WooVina' ) {			
				add_action( 'woovina_before_archive_product_add_to_cart_inner', 'qib_quantity_field_archive' );			
			} else {
				add_action( 'woocommerce_after_shop_loop_item', 'qib_quantity_field_archive', 9 );			
			}		
		} else {
			if ( $theme == 'Astra' || $parent_theme == 'Astra' ) {
				add_action( 'astra_woo_shop_add_to_cart_after', 'qib_quantity_field_archive', 9 );
			} elseif ( $theme == 'OceanWP' || $parent_theme == 'OceanWP' ) {				
				add_action( 'ocean_after_archive_product_add_to_cart_inner', 'qib_quantity_field_archive' );
			} elseif ( $theme == 'WooVina' || $parent_theme == 'WooVina' ) {			
				add_action( 'woovina_after_archive_product_add_to_cart_inner', 'qib_quantity_field_archive' );		
			} else {
				add_action( 'woocommerce_after_shop_loop_item', 'qib_quantity_field_archive', 11 );						
			}
		}
		
		// Remove default quantity buttons.
		if ( $theme == 'Astra' || $parent_theme == 'Astra' ) {
			add_filter( 'astra_add_to_cart_quantity_btn_enabled', '__return_false' );
		} elseif ( $theme == 'Avada' || $parent_theme == 'Avada' ) {			
			Fusion_Dynamic_JS::deregister_script('avada-quantity');  
		}		
			
		// Add script that allows adding custom quantity on Add to cart button click for archive pages.
		add_action( 'template_redirect', 'qib_add_to_cart_quantity_handler' );
	}	

	function qib_quantity_field_archive() {		
		$product = wc_get_product( get_the_ID() );
		if ( $product->is_purchasable() && ! $product->is_sold_individually() && $product->is_in_stock() && 'variable' != $product->get_type() && 'bundle' != $product->get_type() ) {
			woocommerce_quantity_input( [ 'min_value' => 1, 'max_value' => $product->backorders_allowed() ? '' : $product->get_stock_quantity() ] );
		}
	}	

	function qib_add_to_cart_quantity_handler() {
		
		wc_enqueue_js( '
		
			jQuery(document).on( "click", ".quantity input", function() {
				return false;
			});
			
			jQuery(document).on( "change input", ".quantity .qty", function() {					
				
				var add_to_cart_button = jQuery( this ).closest( ".product" ).find( ".add_to_cart_button" );

				// For AJAX add-to-cart actions				
				add_to_cart_button.attr( "data-quantity", jQuery( this ).val() );

				// For non-AJAX add-to-cart actions
				add_to_cart_button.attr( "href", "?add-to-cart=" + add_to_cart_button.attr( "data-product_id" ) + "&quantity=" + jQuery( this ).val() );				
			});
			
		' );

	}
	
	add_filter('qib_quantity_template_path', 'qib_template_path');
	function qib_template_path($template_path) {
		return $template_path;
	}
		
	function qib_override_default_template( $template, $template_name, $template_path ) {		
		if ($template_name == 'global/quantity-input.php') {
			$path = plugin_dir_path( __FILE__ ) . 'template/' . $template_name;		
			$template = apply_filters( 'qib_quantity_template_path', $path );		
		}		
		return $template;
	}
	
	function qib_enqueue_script() { 
		
		$event_listeners =
		'		
		// Make the code work after page load.
		$(document).ready(function(){			
			QtyChng();		
		});

		// Make the code work after executing AJAX.
		$(document).ajaxComplete(function () {
			QtyChng();
		});
		';
		
		$event_listeners = apply_filters( 'qib_change_event_listeners', $event_listeners);
		
		$quantity_change =
		
		'
		// Find quantity input field corresponding to increment button clicked.
		var qty = $( this ).siblings( ".quantity" ).find( ".qty" );
		// Read value and attributes min, max, step.
		var val = parseFloat(qty.val());
		var max = parseFloat(qty.attr( "max" ));
		var min = parseFloat(qty.attr( "min" ));		
		var step = parseFloat(qty.attr( "step" ));
		
		// Change input field value if result is in min and max range.
		// If the result is above max then change to max and alert user about exceeding max stock.
		// If the field is empty, fill with min for "-" (0 possible) and step for "+".
		if ( $( this ).is( ".plus" ) ) {
			if ( val === max ) return false;				   
			if( isNaN(val) ) {
				qty.val( step );			
			} else if ( val + step > max ) {
				qty.val( max );
			} else {
				qty.val( val + step );
			}	   
		} else {			
			if ( val === min ) return false;
			if( isNaN(val) ) {
				qty.val( min );
			} else if ( val - step < min ) {
				qty.val( min );
			} else {
				qty.val( val - step );
			}
		}
		
		qty.val( Math.round( qty.val() * 100 ) / 100 );
		qty.trigger("change");
		$( "body" ).removeClass( "sf-input-focused" );
		';
		
		$quantity_change = apply_filters( 'qib_change_quantity_change', $quantity_change);		
	
		wc_enqueue_js( $event_listeners .		
			
			'
			function QtyChng() {
				$(document).off("click", ".qib-button").on( "click", ".qib-button", function() {'				
					. $quantity_change .					
				'});
			}
			'
		);
		
	}
	
	function qib_apply_styles( $args ) {

		$white_dotted_outline =  'outline-offset: -3px; outline-width: 1px; outline-color: #ebe9eb; outline-style: dotted;';

		switch ( $args['button_style'] ) {		
			case 'Black':
				$border_color = 'border-color: black;';
				$bnormal = 'color: white; background: black;';
				$bfocus = $white_dotted_outline;
				$bhover = 'background: #42413f;';
				break;
			case 'Blue':
				$border_color = 'border-color: #6b6969;';
				$bnormal = 'color: white; background: #2164cc;';
				$bfocus = $white_dotted_outline;
				$bhover = 'background: #2c61b3;';
				break;
			case 'Brown':
				$border_color = 'border-color: #3e3434;';
				$bnormal = 'color: white; background: #7b4747;';
				$bfocus = $white_dotted_outline;
				$bhover = 'background: #674242;';
				break;
			case 'Orange':
				$border_color = 'border-color: #da7f1a;';
				$bnormal = 'color: white; background: orange;';
				$bfocus = 'border: 2px #da731f solid; outline: none;';
				$bhover = 'background: #f38226;';
				break;
			case 'Red':
				$border_color = 'border-color: #b50e0e;';
				$bnormal = 'color: white; background: #d62c2c;';
				$bfocus = $white_dotted_outline;
				$bhover = 'background: #c51818;';
				break;				
			case 'Silver':
				$border_color = 'border-color: #cac9c9;';
				$bnormal = 'color: black; background: #e2e2e2;';
				$bfocus = 'border: 2px #b3b3aa solid;  outline: none;';
				$bhover = 'background: #d6d5d5;';
				break;	
		}
		
		$bnormal .= $border_color;
		
		switch ( $args['cart_align'] ) {
			case 'Left':
				$calign = 'left';
				$cjustify = 'flex-start';
				break;
			case 'Center':
				$calign = 'center';
				$cjustify = 'center';
				break;
			case 'Right':
				$calign = 'right';	
				$cjustify = 'flex-end';		
				break;
		}
		
		switch ( $args['archive_display'] ) {
			case 'None':
				$archive_selector = false;
				break;
			case 'Shop':
				$archive_selector = is_shop() || is_front_page() || is_home();
				break;
			case 'Category':
				$archive_selector = is_product_category();
				break;
			case 'Shop & Category':
				$archive_selector = is_shop() || is_product_category() || is_front_page() || is_home();
				break;
		}
		
		// 1. Remove default spin buttons.	
		// 2. Make sure that increment buttons are positioned before and after quantity input field.
		// 3. Format increment buttons. Format quantity input field and Add to cart button to keep their size consistent with increment buttons.
		// 4. Prevent buttons from breaking to next line.
		// 5. Other adjustments.

		// use flex only to enable justify-content, otherwise use inline-flex
	
		$my_style = "
		.qib-container input[type='number']:not(#qib_id):not(#qib_id) {
			-moz-appearance: textfield;
		}
		.qib-container input[type='number']:not(#qib_id):not(#qib_id)::-webkit-outer-spin-button,
		.qib-container input[type='number']:not(#qib_id):not(#qib_id)::-webkit-inner-spin-button {
			-webkit-appearance: none;
			display: none;
			margin: 0;
		}
		
		form.cart button[type='submit']:not(#qib_id):not(#qib_id),"
		. ( 1 == $archive_selector ? ".add_to_cart_button:not(#qib_id):not(#qib_id)," : "" ) .
		"form.cart .qib-container + div:not(#qib_id):not(#qib_id)  {
			display: inline-block;
			margin: 0;
			padding-top: 0;
			padding-bottom: 0;
			float: none;
			vertical-align: top;
			text-align: center;
		}		
		form.cart button[type='submit']:not(#qib_id):not(#qib_id):not(_)"
		. ( 1 == $archive_selector ? ",.add_to_cart_button:not(a):not(#qib_id):not(#qib_id)" : "" ) . "{
			line-height: 1;
		}
		form.cart button[type='submit']:not(#qib_id):not(#qib_id):not(_):not(_) {	
			height: " . $args['button_height'] . "px;
			text-align: center;
		}"
		. ( 1 == $archive_selector ?
			".add_to_cart_button:not(#qib_id):not(#qib_id):not(_){
				line-height: " . $args['button_height'] . "px;
				margin-top: 0;
			}" : "" ) .
		"form.cart .qib-container + button[type='submit']:not(#qib_id):not(#qib_id),		
		form.cart .qib-container + div:not(#qib_id):not(#qib_id):not(_) {
			margin-left: 1em;
		}
		form.cart button[type='submit']:focus:not(#qib_id):not(#qib_id) {
			outline-width: 2px;
			outline-offset: -2px;
			outline-style: solid;
		}		
		
		.qib-container div.quantity:not(#qib_id):not(#qib_id) {"
			. ( 1 == $args['merge_buttons'] ? "float: left;" : "float: none;") .
			"line-height: 1;
			display: inline-block;
			margin: 0;
			padding: 0;
			border: none;
			border-radius: 0;
			width: auto;
			min-height: initial;
			min-width: initial;
			max-height: initial;
			max-width: initial;			
		}
		.qib-button:not(#qib_id):not(#qib_id) {
			line-height: 1;
			display: inline-block;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			height: " . $args['button_height'] . "px;
			width: " . $args['button_width'] . "px;"
			. $bnormal . ( 1 == $args['merge_buttons'] ? "float: left;" : "") .
			"min-height: initial;
			min-width: initial;
			max-height: initial;
			max-width: initial;
			vertical-align: middle;
			font-size: 16px;
			letter-spacing: 0;
			border-style: solid;
			border-width: 1px;		
			transition: none;"
			. ( 1 == $args['merge_buttons'] ? "border-radius: 0;" : "border-radius: 4px;" ) .		
		"}		
		.qib-button:focus:not(#qib_id):not(#qib_id) {"
			. $bfocus .
		"}
		.qib-button:hover:not(#qib_id):not(#qib_id) {"
			. $bhover .
		"}
		.qib-container .quantity input.qty:not(#qib_id):not(#qib_id) {
			line-height: 1;
			background: none;
			text-align: center;			
			vertical-align: middle;
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			height: " . $args['button_height'] . "px;
			width: " . $args['quantity_width'] . "px;
			min-height: initial;
			min-width: initial;
			max-height: initial;
			max-width: initial;
			box-shadow: none;
			font-size: 15px;
			border-style: solid;"
			. $border_color . ( 1 == $args['merge_buttons'] ? "border-width: 1px 0; border-radius: 0;" : "border-width: 1px; border-radius: 4px;") .			
		"}
		.qib-container .quantity input.qty:focus:not(#qib_id):not(#qib_id) {"
			. $border_color . "outline: none; border-width: " . ( 1 == $args['merge_buttons'] ? "2px 1px;" : "2px;" ) . "border-style: solid;" .	
		"}		
		.woocommerce table.cart td.product-quantity:not(#qib_id):not(#qib_id) {
			white-space: nowrap;
		}
		@media (min-width: 768px) { .woocommerce table.cart td.product-quantity:not(#qib_id):not(#qib_id) {
			text-align: " . $calign . ";
		}}"
		. ( 1 == $args['auto_table'] ?
			".woocommerce table.cart:not(#qib_id):not(#qib_id) {
				table-layout: auto;
			}"
		: "" )
		. ( 1 == $args['merge_buttons'] ?
			"@media (min-width: 768px) { .woocommerce table.cart td.product-quantity .qib-container:not(#qib_id):not(#qib_id) {
				display: flex;
				justify-content: " . $cjustify . ";
			}}"
		: ".qib-container > *:not(:last-child):not(#qib_id):not(#qib_id) {
				margin-right: 5px!important;
			}" ) . "
		.qib-container:not(#qib_id):not(#qib_id) {"
			. ( 1 == $args['merge_buttons'] ? "display: inline-block;" : "display: inline-flex;") .						
		"}
		.woocommerce-grouped-product-list-item__quantity:not(#qib_id):not(#qib_id) {
			margin: 0;
			padding-left: 0;
			padding-right: 0;
			text-align: left;
		}
		.woocommerce-grouped-product-list-item__quantity .qib-container:not(#qib_id):not(#qib_id) {
			display: flex;
		}
		.quantity .minus:not(#qib_id):not(#qib_id), .quantity .plus:not(#qib_id):not(#qib_id), .quantity > a:not(#qib_id):not(#qib_id) {
			display: none;
		}
		.products.oceanwp-row .qib-container:not(#qib_id):not(#qib_id) {
			margin-bottom: 8px;
		}
		";
	
		echo '<style>' . qib_minify($my_style) . '</style>';
	
	}
}

function qib_minify( $input ) {
	$output = $input;
	// Remove whitespace
	$output = preg_replace('/\s*([{}|:;,])\s+/', '$1', $output);
	// Remove trailing whitespace at the start
	$output = preg_replace('/\s\s+(.*)/', '$1', $output);
	// Remove comments
	// $output = preg_replace('#/\*.*?\*/#s', '', $output);
	$output = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/', '', $output);
	return $output;
}
