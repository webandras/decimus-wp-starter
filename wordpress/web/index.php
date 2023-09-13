<?php
/**
 * WordPress View Bootstrapper
 */
define('WP_USE_THEMES', true);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/wp/wp-blog-header.php';
