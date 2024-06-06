<?php

/**
 * The header for our WooCommerce theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package decimus
 */

?>
<?php
$contact_request  = new WP_REST_Request( 'GET', '/decimus/v1/frontend/contact' );
$contact_response = rest_do_request( $contact_request );
$contact_data     = rest_get_server()->response_to_data( $contact_response, true );

// check if we received the data from the endpoint
$have_contact_data = isset( $contact_data ) && isset( $contact_data['data'] );
$contact_options   = $have_contact_data && isset( $contact_data['data']['option_value'] ) ? $contact_data['data']['option_value'] : [];

$phone_pretty = isset( $contact_options['phone_number'] ) ? esc_html( $contact_options['phone_number'] ) : '';
$email        = isset( $contact_options['email_address'] ) ? sanitize_email( $contact_options['email_address'] ) : '';
$facebook     = isset( $contact_options['facebook'] ) ? sanitize_url( $contact_options['facebook'] ) : '';
$messenger    = isset( $contact_options['messenger'] ) ? sanitize_url( $contact_options['messenger'] ) : '';

$phone_number = generate_phone_number( $phone_pretty );

$header_request  = new WP_REST_Request( 'GET', '/decimus/v1/frontend/header' );
$header_response = rest_do_request( $header_request );
$header_data     = rest_get_server()->response_to_data( $header_response, true );

// check if we received the data from the endpoint
$have_header_data = isset( $header_data ) && isset( $header_data['data'] );
$header_options   = $have_header_data && isset( $header_data['data']['option_value'] ) ? $header_data['data']['option_value'] : [];

$cart    = isset( $header_options['cart_button'] ) && intval( $header_options['cart_button'] );
$account = isset( $header_options['account_button'] ) && intval( $header_options['account_button'] );
$search  = isset( $header_options['search_button'] ) && intval( $header_options['search_button'] );


?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180"
          href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32"
          href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16"
          href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/safari-pinned-tab.svg"
          color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Loads the internal WP jQuery. Required if a 3rd party plugin loads jQuery in header instead in footer -->
	<?php wp_enqueue_script( 'jquery' ); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'container-max-width' ); ?>>

<div id="to-top"></div>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v16.0&appId=3480055112023634&autoLogAppEvents=1"
        nonce="v2t1n8Km"></script>

<div id="page" class="site">

    <header id="masthead" class="site-header">

        <div class="fixed-top">
            <div id="top-fix"></div>

            <div class="bg-dark text-light top-navigation">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-6 contact-info">
                            <a class="me-2" href="tel:<?php echo $phone_number ?>"
                               rel="noreferrer noopener"
                               target="_blank" title="<?php _e( 'Call us!', 'decimus-child' ) ?>"><i
                                        class="fas fa-phone-alt me-1"></i><?php echo $phone_pretty ?></a>
                            <a class="" href="mailto:<?php echo $email ?>"
                               title="<?php _e( 'Message us!', 'decimus-child' ) ?>"><i
                                        class="fas fa-envelope me-1"></i><?php echo $email ?></a>
                        </div>

                        <div class="d-none d-sm-block col-sm-6 text-end">

                            <a href="<?php echo $facebook ?>" class="me-1" aria-label="<?php _e( 'Facebook page link', 'decimus-child' ) ?>"><i class="fab fa-facebook-square"></i></a>
                            <a href="<?php echo $messenger ?>" aria-label="<?php _e( 'Messenger app link', 'decimus-child' ) ?>"><i class="fab fa-facebook-messenger"></i></a>

                            <div class="d-none d-lg-block ms-1 ms-md-2 top-nav-search-lg">
								<?php if ( is_active_sidebar( 'top-nav-search' ) ) : ?>
                                    <div>
										<?php //dynamic_sidebar('top-nav-search'); ?>
                                    </div>
								<?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <nav id="nav-main" class="navbar navbar-expand-lg navbar-dark bg-primary">

                <div class="container-fluid">

                    <!-- Navbar Brand -->
                    <a class="navbar-brand xs d-md-none" href="<?php echo esc_url( home_url() ); ?>"><img
                                src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/logo/logo.png"
                                alt="logo small" width="120"
                                height="17" style="width: 120px; height: auto;"
                                class="logo xs d-inline-block mr-1"></a>

                    <a class="navbar-brand md d-none d-md-block " href="<?php echo esc_url( home_url() ); ?>">
                        <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/logo/logo.png"
                             alt="logo normal"
                             width="180"
                             height="25"
                             class="logo md d-inline-block mr-3">
                        <div class="brand-text"><?php //bloginfo('name'); ?></div>
                    </a>


                    <!-- Offcanvas Navbar -->
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-navbar">
                        <div class="offcanvas-header bg-primary text-white">
                            <h5 class="mb-0 text-white lh-1"><?php esc_html_e( 'Menu', 'decimus' ); ?></h5>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="<?php _e( 'Close', 'decimus-child' ) ?>"></button>
                        </div>
                        <div class="offcanvas-body text-center">
                            <!-- Bootstrap 5 Nav Walker Main Menu -->
							<?php
							wp_nav_menu( array(
								'theme_location' => 'main-menu',
								'container'      => false,
								'menu_class'     => '',
								'fallback_cb'    => '__return_false',
								'items_wrap'     => '<ul id="decimus-navbar" class="navbar-nav ms-auto %2$s">%3$s</ul>',
								'depth'          => 2,
								'walker'         => new bootstrap_5_wp_nav_menu_walker(),
							) );
							?>
                            <!-- Bootstrap 5 Nav Walker Main Menu End -->
                            <hr class="">

                            <nav class="mobile-menu-contact-info nav d-flex flex-column my-3 d-lg-none lh-lg">
                                <a class="base-size text-left" href="tel:<?php echo $phone_number ?>"
                                   rel="noreferrer noopener"
                                   target="_blank"
                                   title="<?php _e( 'Call us!', 'decimus-child' ) ?>">
                                    <i class="fas fa-phone-alt me-1"></i>
									<?php echo $phone_pretty ?></a>
                                <a class="base-size text-left" href="mailto:<?php echo $email ?>"
                                   title="<?php _e( 'Message us!', 'decimus-child' ) ?>">
                                    <i class="fas fa-envelope me-1"></i>
									<?php echo $email ?></a>
                            </nav>

                            <div class="d-block d-lg-none text-start">
                                <div class="fb-page" data-href="<?php echo $facebook ?>" data-tabs=""
                                     data-width="" data-height="" data-small-header="true"
                                     data-adapt-container-width="true" data-hide-cover="false"
                                     data-show-facepile="true">
                                    <blockquote cite="<?php echo $facebook ?>"
                                                class="fb-xfbml-parse-ignore"><a
                                                href="<?php echo $facebook ?>">WebAndrás</a></blockquote>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="header-actions d-flex align-items-center pr-2">

                        <!-- Top Nav Widget -->
                        <div class="top-nav-widget">
							<?php if ( is_active_sidebar( 'top-nav' ) ) : ?>
                                <div>
									<?php dynamic_sidebar( 'top-nav' ); ?>
                                </div>
							<?php endif; ?>
                        </div>

                        <!-- Searchform Large -->
                        <div class="d-none d-lg-block ms-1 ms-md-2 top-nav-search-lg">
							<?php if ( is_active_sidebar( 'top-nav-search' ) ) : ?>
                                <div>
									<?php //dynamic_sidebar('top-nav-search'); ?>
                                </div>
							<?php endif; ?>
                        </div>

						<?php if ( $search === true ) { ?>
                            <!-- Search Toggler -->
                            <button class="btn btn-outline-light ms-1 ms-md-2 " type="button"
                                    data-bs-toggle="modal" data-bs-target="#modal-search" aria-expanded="false"
                                    aria-controls="modal-search"
                                    aria-label="<?php _e( 'Open the search modal window', 'decimus-child' ) ?>"
                            >
                                <i class="fas fa-search"></i>
                            </button>
						<?php } ?>
						<?php if ( decimus_is_woocommerce_activated() && $account ) { ?>
                            <!-- User Toggler -->
                            <button class="btn btn-outline-light ms-1 ms-md-2" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user"
                                    aria-controls="offcanvas-user"
                                    aria-label="<?php _e( 'Open your account / login offcanvas area', 'decimus-child' ) ?>"
                            >
                                <i class="fas fa-user"></i>

                            </button>
						<?php } ?>
						<?php if ( decimus_is_woocommerce_activated() && $cart ) { ?>
                            <!-- Mini Cart Toggler -->
                            <button class="btn btn-outline-light ms-1 ms-md-2 position-relative" type="button"
                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas-cart"
                                    aria-controls="offcanvas-cart"
                                    aria-label="<?php _e( 'Open your shopping cart offcanvas area', 'decimus-child' ) ?>"
                            >
                                <i class="fas fa-shopping-cart"></i>
								<?php if ( in_array( 'woocommerce/woocommerce.php',
									apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
									$count = WC()->cart->cart_contents_count;
									?>
                                    <span class="cart-content"><?php
										if ( $count > 0 ) {
											?>
                                            <span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"><?php echo esc_html( $count ); ?></span>
                                            <span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
											<?php
										}
										?></span>
								<?php } ?>
                            </button>
						<?php } ?>

                        <!-- Navbar Toggler -->
                        <button class="btn btn-outline-light d-lg-none ms-1 ms-md-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvas-navbar"
                                aria-controls="offcanvas-navbar"
                                aria-label="<?php _e( 'Open the sidebar mobile menu', 'decimus-child' ) ?>"
                        >
                            <i class="fas fa-bars"></i>
                        </button>

                    </div><!-- .header-actions -->

                </div><!-- .container -->

            </nav><!-- .navbar -->


        </div><!-- .fixed-top .bg-light -->

        <!-- offcanvas user -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-user">
            <div class="offcanvas-header bg-primary">
                <h5 class="mb-0 text-white"><?php esc_html_e( 'Account', 'decimus' ); ?></h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="<?php _e( 'Close', 'decimus-child' ) ?>"></button>
            </div>
            <div class="offcanvas-body">
                <div class="my-offcancas-account">
					<?php if ( decimus_is_woocommerce_activated() ) {
						require get_template_directory() . '/woocommerce/myaccount/my-account-offcanvas.php';
					}
					?>
                </div>
            </div>
        </div>

        <!-- offcanvas cart -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-cart">
            <div class="offcanvas-header bg-primary">
                <h5 class="mb-0 text-white"><?php esc_html_e( 'Cart', 'decimus' ); ?></h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="<?php _e( 'Close', 'decimus-child' ) ?>"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="cart-loader bg-white position-absolute end-0 bottom-0 start-0 d-flex align-items-center justify-content-center">
                    <div class="loader-icon ">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
                <div class="cart-list">
					<?php if ( decimus_is_woocommerce_activated() && function_exists( 'woocommerce_mini_cart' ) ) { ?>
                        <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>
					<?php } ?>
                </div>
            </div>
        </div>

    </header><!-- #masthead -->

    <!-- Top Nav Search Modal -->
    <div class="modal fade" id="modal-search" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
		<?php if ( is_active_sidebar( 'top-nav-search' ) ) : ?>
            <div class="modal-dialog">

                <div class="search-container modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchModalLabel">Keresés a bejegyzésekben</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="<?php _e( 'Close', 'decimus-child' ) ?>">
                        </button>
                    </div>
                    <div class="modal-body">
						<?php dynamic_sidebar( 'top-nav-search' ); ?>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>

	<?php decimus_ie_alert(); ?>
