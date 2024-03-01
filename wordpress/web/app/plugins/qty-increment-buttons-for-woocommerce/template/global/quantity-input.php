<?php
/**
 * This template is a modified /woocommerce/templates/global/quantity-input.php.
 * @version 4.0.0
 *
 * Responsible for adding custom quantity increment buttons and a container with unique class that helps in styling.
 * If changes are needed, create a custom copy of this template and load through a filter.
 * Example implementation for functions.php file (get_stylesheet_directory returns active theme's directory, child or parent):
 
   add_filter('qib_quantity_template_path', 'qib_replace_template');
      function qib_replace_template($template_path) {
      $template_path = get_stylesheet_directory() . '/custom templates/quantity-input.php';
      return $template_path;
   }
 
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	if ( is_cart() ) {
		echo esc_html( $min_value ); ?>
		<input type="hidden" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />			
		<?php
	} else {
		printf ( '<div class="quantity hidden"> 
					<input type="hidden" %s class="qty" name="%s" value="%s"/>
				 </div>',
				 isset($input_id) ? 'id="' . esc_attr( $input_id ) . '"' : '',
				 esc_attr( $input_name ),
				 esc_attr( $min_value )
				);
	}
} else {			
	/* translators: %s: Quantity. */
	$label = ! empty( $args['product_name'] ) ? sprintf( __( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : __( 'Quantity', 'woocommerce' );
	?>
	<div class="qib-container">
		<button type="button" class="minus qib-button" >-</button>
		<div class="quantity buttons_added">
			<?php if (isset($input_id)) printf('<label class="screen-reader-text" for="%s">%s</label>', esc_attr($input_id), esc_html( $label ) ); ?>
			<input
				type="number"
				<?php if (isset($input_id)) printf('id="%s"', esc_attr($input_id) ); ?>
				class="<?php echo esc_attr( isset($classes) ? join( ' ', (array) $classes ) : 'input-text qty text' ); ?>"
				step="<?php echo esc_attr( $step ); ?>"
				min="<?php echo esc_attr( $min_value ); ?>"
				max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
				name="<?php echo esc_attr( $input_name ); ?>"
				value="<?php echo esc_attr( $input_value ); ?>"
				title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'woocommerce' ); ?>"
				size="4"
				placeholder="<?php echo esc_attr( $placeholder ); ?>"
				inputmode="<?php echo esc_attr( $inputmode ); ?>" />
		</div>
		<button type="button" class="plus qib-button" >+</button>
	</div>
	<?php
}