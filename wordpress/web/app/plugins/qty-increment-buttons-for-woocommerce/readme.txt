=== Qty Increment Buttons for WooCommerce ===
Contributors: taisho
Tags: woocommerce,quantity,increment,buttons,qty
Donate link: https://www.paypal.me/TaishoDev/usd
Requires at least: 4.6
Tested up to: 6.4.3
Requires PHP: 5.4
Stable tag: 2.7.6
License: GPLv3
License URL: http://www.gnu.org/licenses/gpl-3.0.html
Modified by: András Gulácsi in 2024
Compatible up to: PHP 8.1
Compatible with WooCommerce up to: 8.6.1

Adds professionally looking "-" and "+" quantity increment buttons around product quantity field on WooCommerce pages. Removes default increment arrows inside this field. Easy customization through plugin settings.

== Description ==

Adds professionally looking "-" and "+" quantity increment buttons around product quantity field on WooCommerce pages. Removes default increment arrows inside this field. Easy customization through plugin settings.

<h4>Features</h4>

* Adds "-" and "+" quantity buttons around product quantity field.
* Removes default increment arrows inside the quantity input field, which are built-in by browsers.
* Works on product, cart, checkout, shop, category or optionally all pages.
* Quantity field with increment buttons can be added to shop page, category page or both.
* Should look great on most of the themes out of the box. Extensively tested on Storefront theme.
* Fully responsive design.
* Plugin settings allow to customize buttons.
* Generated with PHP - visible immediately on page load.
* Buttons are not displayed for items sold individually.
* Compatible with Quick View plugins.
* For automatic cart and mini-cart update after changing the quantity on the cart page, you can use my other free plugin [Ajax Cart AutoUpdate for WooCommerce](https://wordpress.org/plugins/ajax-cart-autoupdate-for-woocommerce/).

== Screenshots ==

1. Cart page - Silver style with square buttons merged
2. Product page - Black style with rectangle buttons unmerged
3. Plugin settings

== Installation ==

<h4>Recommended:</h4>
Install and activate from WordPress "Plugins > Add New" screen.

<h4>Manual installation:</h4>
1. Unzip plugin file and upload folder to directory /wp-content/plugins/
1. Activate plugin in WordPress \"Plugins\" menu.

== Frequently Asked Questions ==

= Will this plugin work correctly on my website? =

Qty Increment Buttons for WooCommerce is designed to work on any WooCommerce-based website. It's tested for compatibility with version 3.0 (previously 2.6) and newer. Assumed to work since 2.3 in which quantity buttons were removed from WooCommerce core.

= How to make the buttons display on the Home page?

The buttons will display properly on a regular Home page that is also a WooCommerce shop or category page if the corresponding Archives display option in plugin settings is enabled. For a custom home page (or any other custom page), enabling the plugin to work on all pages in settings should do the trick. However, depending on how much it differs from regular WooCommerce page, the functionality and visuals can be negatively affected.

= My buttons are poorly aligned. How can I fix it? =

While very flexible in theme support, this plugin can't handle all custom layouts. The biggest offenders are themes using !important in their CSS rules that affect the quantity input field and the buttons. It can be solved by using the same selector with different rules in child theme's style.css or extra CSS if the theme allows it. Feel free to report CSS-related problems on the support forum.

= Will this plugin affect my page speed? =

Very little, it's a small plugin that executes only on product, cart, checkout, shop and category pages, unless an option to load the plugin on all pages is checked. CSS in the head is minified.

== Changelog ==

= 2.7.5 =
* Hotfixed an issue from 2.7.4 release that caused quantity buttons used by some themes to be visible and appear next to the plugin buttons.

= 2.7.4 =
* Added "buttons_added" class to div.quantity in quantity-input.php template. This will stop some themes from adding their own buttons.
* Updated the quantity-input.php template to be compatible with the one currently used by WooCommerce (version 4.0.0). The input fields will now receive "placeholder" attribute.
* Fixed a bug that happened while the quantity field was empty and a quantity button used. Now the "change" event triggers correctly and also Storefront's mobile bottom menu doesn't disappear.
* CSS - hide theme's default "-" and "+" buttons with a non-standard class if they have an <a> tag.
* CSS - fixed Add to cart button's formatting for related products. Now it's vertically aligned with the quantity buttons and its button text is vertically centered.
* CSS - fixed Add to cart button's vertical alignment on archive pages if it uses a top margin.
* CSS - fixed a rare case where unmerged buttons would not be perfectly vertically aligned with the input field.

= 2.7.3 =
* Hotfixed PHP 7.4 notice from a 2.7.2 release.

= 2.7.2 =
* Shop & category pages will no longer show quantity buttons for bundle products.
* Quantity will now be rounded to 2 decimal places for proper handling of fractional quantity plugins. It doesn't change how the plugin works for integer values.
* Avada theme - theme's increment buttons are now removed through deregister script.
* Astra theme - theme's increment buttons are now removed through filter.
* Astra theme - quantity increment buttons on archive pages will now use theme's custom hook for correct positioning.
* CSS - spin buttons that still showed within the quantity input field in Chrome for some websites will now properly disappear. Part of the fix is that quantity field backgrounds are set to 'none' by default.
* CSS - home page will be treated as a shop page in terms of applied CSS if the plugin is set to show the buttons on the shop page. It fixes a problem with Add to cart button not aligned properly on a home page.
* CSS - increased specificity to more reliably hide "-" and "+" buttons appearing by default in some themes. There can still be functional or visual issues that require properly removing such buttons by one of the following ways: theme settings, PHP filter, dequeuing a script.
* CSS - Add to cart button's text will now be centered in case your theme had a different idea about its positioning.
* Removed Spanish translation files from plugin core due to this translation no longer being maintained. All translations are now here, maintaned by contributors: https://translate.wordpress.org/projects/wp-plugins/qty-increment-buttons-for-woocommerce/

= 2.7.1 =
* An immediate fix for 2.7.0 released a couple of hours earlier. 2.7.0 mistakenly used a development file. 2.7.1 is the final and bug-free version.

= 2.7.0 =
* IMPORTANT if you use custom CSS to override CSS of this plugin - specificity for almost all elements has been increased yet another time (double ID) to make sure that CSS of this plugin is prioritized over default theme CSS.
* Tested for compatibility with PHP 7.4.
* Tested for compatibility with WordPress 5.4.
* Tested for compatibility with WooCommerce 4.0.
* The oldest supported WooCommerce version is now 3.0 (previously 2.6). While the older versions should work correctly with this and future releases of QIB, I will only run tests on 3.x.x and 4.x.x WooCommerce versions.
* Resolved an issue with Add to cart button on archive pages always adding only ONE item in WooCommerce 4.0.
* JavaScript-generated quantity buttons coming with some themes will now be automatically hidden through CSS (display: none).
* Quantity increment buttons on archive pages in OceanWP and WooVina theme will now use their custom hooks for correct positioning.
* Storefront theme - on mobile devices the quantity change done with quantity buttons will no longer cause handheld footer bar disappearing.

= 2.6.1 =
* Reworked the code to make translate.wordpress.org load all strings for translation automatically.
* Added filters to enable customization of jQuery code for quantity change: qib_change_event_listeners, qib_change_quantity_change.

= 2.6 =
* Increased required WordPress version from 4.4 to 4.6 for translate.wordpress.org compatibility.
* Tested for compatibility with WooCommerce 3.8.0.
* Tested for compatibility with WordPress 5.3.
* Tweak - the quantity input field and increment buttons are now hidden on archive pages for out of stock products.
* Tweak - if the quantity input field is empty, clicking on increment button will fill it with: min value for "-" (possible to be 0), step value for "+". 
* CSS - resolved a problem with text inside quantity increment buttons and quantity input field not vertically centered for some themes.
* CSS - resolved a problem with text inside Add to cart button not vertically centered on Firefox.

= 2.5.1 =
* Products on archive pages will be now added to cart in correct quantity even if their container has a class 'product'.
* Added a filter to enqueue a custom quantity-input.php template instead of the one used by Qty Increment Buttons.
* Removed a misleading description from quantity-input.php template (it was copied from the original template) and replaced it with a relevant description.

= 2.5 =
* IMPORTANT if you use any custom CSS to override CSS of this plugin - all selectors were changed and include an ID to make sure that plugin styles have a higher specificity than theme styles.
* The plugin will now apply the styles correctly on almost any custom page and theme, even if built with Elementor or similar plugin. To support other pages than the default ones, "Load on all pages" in plugin settings must be checked. 
* New setting: On archive pages, select where quantity input field and increment buttons should appear in HTML structure. Available only if "Archive display" isn't "None". Possible options are "Before Add to cart" (default) and "After Add to cart".
* Improved transparency of plugin settings by displaying part of information on question mark icon hover.
* Quantity template is now included later, to prevent themes or other plugins from including their own buttons, what leads to conflicts.
* CSS - fixed formatting for grouped products.
* CSS - text inside quantity increment buttons, quantity input field and Add to cart button is now perfectly vertically centered. Depending on font size, previously it could be up to a few pixels from the center.

= 2.4 =
* Removed "Theme style" option from "Button style" in plugin settings. If this option is currently selected, it will be changed to "Silver". The tests showed disappointing "Theme style" formatting out of the box on almost every theme. Plugin styles make a better starting point for custom styling.
* Declared compatibility with WooCommerce 3.7.0 (no changes were needed).
* Removed .js file from plugin files. It is no longer used since version 2.3.
* CSS - added another selector to apply plugin styles correctly on archive pages of some themes.
* CSS - plugin settings that affect quantity increment button size will no longer be ignored when max-height or max-width is specified.

= 2.3.2 =
* Fix - CSS minification that was accidentally turned off in release 2.3 is now correctly applied.
* Fix - don't format Add to cart button on an archive page if such page isn't set to display the quantity field and buttons according to "Archive display" option in plugin settings.
* CSS - quantity field - force the value to be centered.

= 2.3.1 =
* Fix - in addition to already present Ajax support, the buttons now work correctly if they are a part of JavaScript-generated content. It includes pop-ups, sliders, etc.
* Fix - related products have the quantity field and increment buttons displayed if "Archives display" setting isn't "None" instead of is "Shop & Category".
* Fix - related products' Add to cart button now adds product quantity correctly instead of always 1.
* CSS - display Add to cart button on the product page in one line with the quantity increment buttons and quantity field even if it's wrapped in a DIV.
* CSS - plugin settings that affect quantity input field's size will no longer be ignored when max-height or max-width is specified.
* CSS - prevent border-radius attribute of quantity field DIV from hiding the corners of quantity field border.
* CSS - increased border-radius (rounded corners) for the buttons and quantity field in unmerged layout from 2px to 4px, to give them more smooth look.
* CSS - button and quantity field focus - continuous outline is now a border, to display rounded corners for unmerged buttons.
* CSS - quantity field focus - the color is now the same as border color instead of using the default focus color that could be very inconsistent.
* CSS - button focus - dotted outline inside the buttons used by a few styles has been moved 1px towards the center.

= 2.3 =
* Increased required PHP version from 5.3 to 5.4 due to changes in the backend of plugin settings.
* Added a PHP version check on plugin activation to prevent activation when the required PHP version is not installed on server.
* Checkout is now an additional page where the plugin is loaded, to support direct checkout plugins and other plugins that allow to change quantity on the checkout page.
* New setting: load the plugin on all pages, instead of only on WooCommerce product, cart, checkout, shop and category pages. Unchecked by default. Allows Quick View plugins to work correctly on any page.
* Instead of enqueuing a .js file, the plugin script is now enqueued inline by a function wc_enqueue_js.

= 2.2.1 =
* Fix - resolved a bug introduced with 2.1.1 version that caused JavaScript events for some elements impossible to trigger. It would result in no effect after clicking etc.

= 2.2 =
* IMPORTANT if you use custom CSS to override CSS of this plugin for quantity input field - specificity for DIV and input has been increased to make sure that CSS of this plugin is prioritized over default theme CSS.
* CSS - prevent quantity buttons and quantity input field from breaking to next line(s) with buttons unmerged for some themes.
* CSS - unmerged buttons will now show correctly separated from the input field when HTML minification plugins incorrectly remove line breaks.
* CSS - plugin settings that affect quantity input field's size will no longer be ignored when min-height or min-width is specified.
* CSS - prevent border from increasing the size of quantity increment buttons and quantity input field, to keep their height exactly the same.
* CSS - all default margins from Add to cart button are removed, to correctly display the only currently supported layout: Add to cart button to the right of the quantity field and increment buttons, in one line.

= 2.1.1 =
* Fix to quantity not being changeable after some AJAX requests. Resolves issues for Quick View plugins. Tested on Quick View WooCommerce and WooCommerce Quick View.

= 2.1 =
* Changed the way buttons are generated. The plugin now overrides quantity-input.php template, as it supports all WooCommerce pages out of the box and offers better compatibility for WooCommerce plugins.
* Output buffer is no longer used. Required PHP version lowered from 5.6 to 5.3.
* New setting: Add quantity input field and quantity increment buttons to shop page, category page or both. Default is "None".
* New setting: Horizontally align quantity input field and quantity increment buttons on the cart page in desktop view. Default is "Center" (used in all previous versions).
* Tested for compatibility with the most popular Quick View plugins.
* Improvement of default WooCommerce behavior on the cart page when min = max quantity. Instead of being entirely hidden, quantity is now displayed as a plain number, what is consistent with how products sold individually are displayed.
* 3 new button styles: Blue, Brown, Red.
* Multiple layout, description and default value improvements in plugin settings.
* Translation-ready.
* New translation: Spanish.

= 2.0 =
* In order to avoid overriding templates and late paint with JavaScript, when there are no fitting hooks and filters, output buffer is used to modify HTML elements with PHP, before they are shown on page.
* Increment buttons are now hidden for items sold individually and in any other case when WooCommerce hides the input field for the product.
* Improved style rules to handle several additional cases when output layout would not be displayed as intended because of inherited styles.
* Fixed positioning of outline when the quantity input field is focused in Firefox.
* CSS selectors without any declaration (no declaration is needed with current plugin settings and WooCommerce version) are no longer included in style.
* Containers now have a class instead of an ID, to avoid duplicate IDs on cart page.

= 1.01 =
* Fixed description on installed plugins listing in the admin panel, which described my other plugin.
* Minify function received unique prefix already present in other functions, to avoid possible name conflicts.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.5 =
* IMPORTANT - after updating to this version, custom CSS used to override styling of this plugin will need to be updated in order to work. This is a result of modifications that greatly improve compatibility with multiple themes and custom pages. Here is the [list of changes](https://wordpress.org/plugins/qty-increment-buttons-for-woocommerce/#developers).