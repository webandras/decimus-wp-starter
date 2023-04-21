<?php
/**
 * The sidebar containing the woocommerce widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Decimus
 */

if ( !is_active_sidebar('woocommerce') && !is_woocommerce_activated() ) {
    return;
}
?>
<div id="woocommerce-sidebar" class="sidebar-area col-md-3 col-xxl-3 mt-4 mt-md-0">
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar('woocommerce'); ?>
    </aside>
    <!-- #secondary -->
</div>
