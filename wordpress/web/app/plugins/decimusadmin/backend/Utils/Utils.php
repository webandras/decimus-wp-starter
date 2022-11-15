<?php

namespace Guland\DecimusAdmin\Utils;

// Exit if accessed directly
if ( !defined('ABSPATH') ) {
    exit;
}

trait Utils
{

    /**
     * Load language files
     *
     * @return void
     */
    public function load_text_domain(): void
    {
        // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
        $domain = DECIMUS_ADMIN_TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(dirname(__FILE__, 2)) . '/languages/');
        load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . '/lang/');
    }
}
