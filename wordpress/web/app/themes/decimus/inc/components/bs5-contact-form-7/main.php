<?php
/**
 * Plugin Name: bS Contact Form 7
 * Plugin URI: https://bootscore.me/plugins/bs-contact-form-7/
 * Description: Adds Bootstrap 5 alerts and checkboxes to Contact Form 7. It´s an additional plugin and needs <a href="https://wordpress.org/plugins/contact-form-7/">CF7</a> to work.
 * Author: bootScore
 * Author URI: https://bootscore.me
 * Version: 5.1.1
 */


if ( ! function_exists( 'decimus_change_cf7_form_elements' ) ) :

	/**
	 * Adjust contact form 7 radios and checkboxes to match bootstrap 5 custom radio structure.
	 *
	 * @param $content
	 *
	 * @return array|string|string[]|null
	 */
	function decimus_change_cf7_form_elements( $content ): array|string|null {
		$content = preg_replace( '/<label><input type="(checkbox|radio)" name="(.*?)" value="(.*?)" \/><span class="wpcf7-list-item-label">/i',
			'<label class="form-check form-check-inline form-check-\1"><input type="\1" name="\2" value="\3" class="form-check-input"><span class="wpcf7-list-item-label form-check-label">',
			$content );
		$content = preg_replace( '/wpcf7-checkbox\sform-check-input/i',
			'',
			$content ); //removes wrong classes on type=checkbox

		return $content;
	}

endif;
add_filter( 'wpcf7_form_elements', 'decimus_change_cf7_form_elements' );


if ( ! function_exists( 'decimus_cf7_deregister_styles' ) ) :

	/**
	 * Disable Contact Form 7 Styles
	 *
	 * @return void
	 */
	function decimus_cf7_deregister_styles(): void {
		wp_deregister_style( 'contact-form-7' );
	}

endif;
add_action( 'wp_print_styles', 'decimus_cf7_deregister_styles', 100 );

// Remove <p> tags (CF7 5.7)
//add_filter( 'wpcf7_autop_or_not', '__return_false' );

if ( ! function_exists( 'decimus_load_session_data_for_checkout' ) ) :

	/**
	 * @return void
	 */
	function decimus_load_session_data_for_checkout(): void {
		global $wp;
		if ( function_exists( 'is_checkout' ) && is_checkout() && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) ) {
			echo '<script>/* put data to inputs from session if exists */
        jQuery(document).ready(function ($) {
            var userDataFromSession = sessionStorage.getItem("currentUserData");
            if (userDataFromSession) {
                userDataFromSession = JSON.parse(userDataFromSession);
                $("#billing_first_name").val(userDataFromSession.name);
                $("#billing_email").val(userDataFromSession.email);
            }
        });
      </script>';
		}
	}

endif;
add_action( 'wp_footer', 'decimus_load_session_data_for_checkout', 9999 );
