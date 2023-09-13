<?php

namespace Guland\DecimusAdmin;

use Exception;

// WP Core / WooCommerce / Utils methods
use Guland\DecimusAdmin\Interfaces\DecimusAdminInterface;
use Guland\DecimusAdmin\Utils\Utils as Utils;
use Guland\DecimusAdmin\Core\Core as Core;
use Guland\DecimusAdmin\WooCommerce\WooCommerce as WooCommerce;

// db migrations and seeds
use Guland\DecimusAdmin\Database\Migration\M001 as M001;
use Guland\DecimusAdmin\Database\Migration\M002 as M002;
use Guland\DecimusAdmin\Database\Seeder\S001 as S001;
use Guland\DecimusAdmin\Database\Seeder\S002 as S002;
use Guland\DecimusAdmin\Database\Seeder\S003 as S003;

// REST API endpoints
use Guland\DecimusAdmin\API\Admin\AdminAdminController as AdminAdminController;
use Guland\DecimusAdmin\API\Admin\ContactAdminController as ContactAdminController;
use Guland\DecimusAdmin\API\Admin\GlobalAdminController as GlobalAdminController;
use Guland\DecimusAdmin\API\Admin\HeaderAdminController as HeaderAdminController;
use Guland\DecimusAdmin\API\Admin\WooCommerceAdminController as WooCommerceAdminController;

use Guland\DecimusAdmin\API\Frontend\ContactFrontendController as ContactFrontendController;
use Guland\DecimusAdmin\API\Frontend\GlobalFrontendController as GlobalFrontendController;
use Guland\DecimusAdmin\API\Frontend\HeaderFrontendController as HeaderFrontendController;
use Guland\DecimusAdmin\API\Frontend\WooCommerceFrontendController as WooCommerceFrontendController;

// Exit if accessed directly
if ( !defined('ABSPATH') ) exit;


/**
 * Implements Admin for Decimus WordPress Theme
 */
final class DecimusAdmin implements DecimusAdminInterface
{
    // Plugin modules
    use Utils;
    use Core;
    use WooCommerce;

    // DB migrations
    use M001;
    use M002;

    // DB seeders
    use S001;
    use S002;
    use S003;

    private static array $allowed_html_tags = [
        'p' => [],
        'b' => [],
        'i' => [],
        'span' => [],
        'br' => [],
        'em' => [],
        'strong' => [],
    ];

    // Class instance
    private static $instance;


    // Admin controllers
    public AdminAdminController $admin;
    public GlobalAdminController $global_admin;
    public ContactAdminController $contact_admin;
    public HeaderAdminController $header_admin;
    public WooCommerceAdminController $woocommerce_admin;

    // Frontend controllers
    public GlobalFrontendController $global_frontend;
    public ContactFrontendController $contact_frontend;
    public HeaderFrontendController $header_frontend;
    public WooCommerceFrontendController $woocommerce_frontend;


    // DB version
    private const DB_VERSION_NAME = 'decimus_admin_db_version';
    private const DB_MIGRATIONS_RUN = 'decimus_migrations_run';
    protected array $migrations = [];


    /**
     * Return class instance, or create it if not exists
     *
     * @return self $instance
     */
    public static function getInstance(): self
    {
        if ( self::$instance == null ) {
            self::$instance = new self(
                new AdminAdminController,
                new GlobalAdminController,
                new ContactAdminController,
                new HeaderAdminController,
                new WooCommerceAdminController,
                new GlobalFrontendController,
                new ContactFrontendController,
                new HeaderFrontendController,
                new WooCommerceFrontendController,
            );
        }
        return self::$instance;
    }


    /**
     * @return void
     */
    private function __construct(
        AdminAdminController          $admin,
        GlobalAdminController         $global_admin,
        ContactAdminController        $contact_admin,
        HeaderAdminController         $header_admin,
        WooCommerceAdminController    $woocommerce_admin,
        GlobalFrontendController      $global_frontend,
        ContactFrontendController     $contact_frontend,
        HeaderFrontendController      $header_frontend,
        WooCommerceFrontendController $woocommerce_frontend,
    )
    {
        // Admin controllers
        $this->admin = $admin;
        $this->global_admin = $global_admin;
        $this->contact_admin = $contact_admin;
        $this->header_admin = $header_admin;
        $this->woocommerce_admin = $woocommerce_admin;

        // Frontend controllers
        $this->global_frontend = $global_frontend;
        $this->contact_frontend = $contact_frontend;
        $this->header_frontend = $header_frontend;
        $this->woocommerce_frontend = $woocommerce_frontend;


        // Load translation files.
        add_action('plugins_loaded', array($this, 'load_text_domain'));

        // Add admin menu and page.
        add_action('admin_menu', array($this, 'add_admin_menu'));

        // Register all REST API endpoints. 
        add_action('rest_api_init', array($this, 'register_all_endpoints'));

        // Add Vue admin app bundles to admin page.
        add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));


        // First activation
        if ( !get_option(self::DB_VERSION_NAME) ) {

            self::create_theme_options_table_001();
            self::seed_theme_options_table();

            self::update_db_version(1000);
            self::update_migrations_run(1000);

        }

        if ( intval(get_option(self::DB_VERSION_NAME)) < self::DB_TABLE_VERSION ) {

            // execute sql migrations not yet run in case of plugin updates
            try {

                // iterate through all versions, run migrations if needed
                for ($i = 1000; $i <= self::DB_TABLE_VERSION; $i++) {
                    $migrations_run_already = $this->get_migrations_run();

                    if (empty($migrations_run_already)) {
                        throw new Exception('Something wrong with migrations run array because it is empty.');
                    }

                    // if this version's migration has not yet run
                    if ($i === 1000 && !in_array(1001, $migrations_run_already, true) ) {
                        self::alter_theme_options_table_002();

                        self::update_db_version(1001);
                        self::update_migrations_run(1001);
                    }

                    if ( !array_search(1002, $migrations_run_already, true) ) {
                        self::seed_theme_options_table_003();
                        self::update_migrations_run(1002);
                    }
                }

                self::update_db_version();

            } catch (Exception $ex) {
                print_r($ex);
                die;
            }
        }

    }


    /**
     * Call every rest api register functions.
     *
     * @return void
     */
    public function register_all_endpoints(): void
    {
        // Admin settings routes.
        $this->admin->get_admin_settings_route();
        $this->admin->put_admin_settings_route();

        $this->global_admin->get_global_settings_route();
        $this->global_admin->put_global_settings_route();

        $this->contact_admin->get_contact_settings_route();
        $this->contact_admin->put_contact_settings_route();

        $this->header_admin->get_header_settings_route();
        $this->header_admin->put_header_settings_route();

        $this->woocommerce_admin->get_woocommerce_settings_route();
        $this->woocommerce_admin->put_woocommerce_settings_route();


        // Frontend settings routes.
        $this->global_frontend->get_global_settings_route();

        $this->contact_frontend->get_contact_settings_route();

        $this->header_frontend->get_header_settings_route();

        $this->woocommerce_frontend->get_woocommerce_settings_route();

    }


    /**
     * @return void
     */
    public function __destruct()
    {
    }


    /**
     * Add an option with the version when activated
     *
     * @return void
     */
    public static function activate_plugin(): void
    {

    }


    /**
     * This code will only run when plugin is deleted
     * it will drop the custom database table
     * @hook register_uninstall_hook
     *
     * @return void
     */
    public static function uninstall_plugin(): void
    {
        self::delete_theme_options_table_001();
    }

    private static function update_db_version(int $version = self::DB_TABLE_VERSION): void
    {
        $option_name = self::DB_VERSION_NAME;

        // check if option already exists
        if ( !get_option($option_name) ) {
            // add new option if option not exists
            add_option($option_name, $version);
        } else {
            update_option($option_name, $version);
        }
    }

    private static function update_migrations_run($version_number = self::DB_TABLE_VERSION): array
    {
        $option_name = self::DB_MIGRATIONS_RUN;

        // check if option already exists
        if ( !get_option($option_name) ) {
            // add new option if option not exists
            add_option($option_name, serialize([$version_number]));

            // return migrations already run
            return unserialize(get_option($option_name));
        } else {
            // add the new version to the migrations run array
            $migrations_run_already = unserialize(get_option($option_name));
            $migrations_run_already[] = $version_number;
            $migrations_updated = serialize($migrations_run_already);
            update_option($option_name, $migrations_updated);

            return $migrations_run_already;
        }
    }

    private function get_migrations_run(): array {
        $migrations_run_already = unserialize(get_option(self::DB_MIGRATIONS_RUN));
        return is_array($migrations_run_already)? $migrations_run_already : [];
    }
}
