<?php

namespace Gulacsi\TeamMember;

defined('ABSPATH') or die();

use const ABSPATH;
use Gulacsi\TeamMember\View\Shortcode;
use Gulacsi\TeamMember\Controller\MemberController;
use JetBrains\PhpStorm\NoReturn;
use Gulacsi\TeamMember\Interface\MemberInterface;
use const WP_LANG_DIR;

/**
 * Team Members
 */
final class TeamMember implements MemberInterface
{
    // injectables
    private static $instance;
    private static MemberController $controller;
    private static Shortcode $shortcode;

    /**
     * Singleton instance
     * @return self $instance
     */
    public static function get_instance(): self
    {
        if ( self::$instance == null ) {
            self::$instance = new self(
                new MemberController(),
                new Shortcode()
            );
        }
        return self::$instance;
    }


    /**
     * Add hooks here
     * @return void
     */
    private function __construct(
        MemberController $controller,
        Shortcode        $shortcode
    )
    {
        self::$controller = $controller;
        self::$shortcode = $shortcode;


        add_action('plugins_loaded', array($this, 'load_text_domain'));

        // register shortcode to list all members
        add_shortcode('decimus_team_members', array(self::$shortcode, 'team_member_form'));

        // add admin menu and page
        add_action('admin_menu', array($this, 'admin_menu'));

        // put the css into head (only admin page)
        add_action('admin_head', array($this, 'admin_css'));
        // add script on the backend
        add_action('admin_enqueue_scripts', array($this, 'admin_load_scripts'));

        // put the css before end of </body>
        add_action('wp_enqueue_scripts', array($this, 'admin_css'));

        // add ajax script
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_script('decimus-team-member', plugin_dir_url(dirname(__FILE__)) . 'js/teamMember.js', array('jquery'));

            // enable ajax on frontend
            wp_localize_script('decimus-team-member', 'DecimusTeamMemberData', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce(),
            ));
        });

        // connect AJAX request with PHP hooks
        add_action('wp_ajax_decimus_team_member_action', array($this, 'team_member_ajax_handler'));
        add_action('wp_ajax_nopriv_decimus_team_member_action', array($this, 'team_member_ajax_handler'));
    }

    public function __destruct()
    {
    }

    public static function load_text_domain(): void
    {
        // modified slightly from https://gist.github.com/grappler/7060277#file-plugin-name-php
        $domain = self::TEXT_DOMAIN;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(dirname(__FILE__, 2)) . '/languages/');
    }


    /**
     * Register admin menu page and submenu page
     * @return void
     */
    public function admin_menu(): void
    {
        add_menu_page(
            __('Team Members', self::TEXT_DOMAIN), // page title
            __('All Team Members', self::TEXT_DOMAIN), // menu title
            'manage_options', // capability
            'team-member-list', // menu slug
            array(self::$controller, 'list_table'), // callback
            'dashicons-groups' // icon
        );

        add_submenu_page(
            'team-member-list', //parent slug
            __('Add new team member', self::TEXT_DOMAIN), // page title
            __('Add new', self::TEXT_DOMAIN),  // menu title
            'manage_options', // capability
            'team-member-insert', // menu slug
            array(self::$controller, 'insert_record') // callback
        );
    }


    /**
     * Add some styling to the plugin's admin and shortcode UI
     * @return void
     */
    public function admin_css(): void
    {

        wp_enqueue_style(
            'team_member_css',
            plugin_dir_url(dirname(__FILE__)) . 'css/team-member.css'
        );
    }

    public function admin_load_scripts($hook): void
    {
        if (
            $hook !== 'toplevel_page_team-member-list'
            && $hook !== 'team_member_page_team_member_insert'
        ) {
            return;
        }

        wp_enqueue_style(
            'decimus-team-member-admin-css',
            plugin_dir_url(dirname(__FILE__)) . 'css/team-member.css'
        );
        // wp_enqueue_script('custom-js', plugins_url('js/custom.js', dirname(__FILE__, 2)));
    }


    #[NoReturn] public function team_member_ajax_handler()
    {
        if ( check_ajax_referer('decimus_team_member', 'security') ) {
            $args = $_REQUEST['args'];
            $content = self::$shortcode->team_member_form($args);
            wp_send_json_success($content);
        } else {
            wp_send_json_error();
        }
        wp_die();
    }


    /**
     * Create table, if not exists, when plugin is activated
     */
    public static function activate_plugin(): void
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $charset_collate = $wpdb->get_charset_collate();

        /** @noinspection SqlNoDataSourceInspection */
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `profile_photo` VARCHAR(255) NOT NULL,
            `last_name` VARCHAR(100) NOT NULL,
            `first_name` VARCHAR(100) NOT NULL,
            `phone` VARCHAR(20) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `position` VARCHAR(100) NOT NULL,
            `department` VARCHAR(100) NOT NULL,
            `works_since` DATE NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // manage db version in options
        $team_member_option_name = self::TABLE_NAME . '_db_version';
        $team_member_option = get_option($team_member_option_name);

        if ( !$team_member_option ) {
            add_option($team_member_option_name, self::DB_VERSION);
        } else if ( $team_member_option !== self::DB_VERSION ) {
            update_option($team_member_option_name, self::DB_VERSION);
        }
    }

    // when uninstalling the plugin, the uninstall.php will run (see it in the root folder of the plugin)

    /**
     * Delete plugin - it will delete all data
     */
    public static function delete_plugin(): void
    {
        if ( !defined('WP_UNINSTALL_PLUGIN') ) {
            exit();
        }

        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        $team_member_option_name = self::TABLE_NAME . '_db_version';

        /** @noinspection SqlNoDataSourceInspection */
        $wpdb->query("DROP TABLE IF EXISTS $table_name");

        // delete option if exists
        if ( get_option($team_member_option_name) ) {
            delete_option($team_member_option_name);
        }

    }
}
