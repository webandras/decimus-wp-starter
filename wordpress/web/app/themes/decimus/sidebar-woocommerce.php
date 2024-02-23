<?php
/**
 * The sidebar containing the woocommerce widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Decimus
 */

if ( ! is_active_sidebar( 'woocommerce' ) && ! decimus_is_woocommerce_activated() ) {
	return;
}
if (!isset($args['sidebarType'])) {
	$args['sidebarType'] = 'mobile';
}
?>
<div id="woocommerce-sidebar" class="sidebar-area col-md-3 col-xxl-3 mt-4 mt-md-0 <?php echo $args['sidebarType'] === 'mobile' ? 'mobile-sidebar' : 'desktop-sidebar' ?>">
    <aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'woocommerce' ); ?>
    </aside>
    <!-- #secondary -->
</div>
