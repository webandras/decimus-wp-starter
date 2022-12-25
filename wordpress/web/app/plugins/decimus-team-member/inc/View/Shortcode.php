<?php

namespace Gulacsi\TeamMember\View;

defined('\ABSPATH') or die();

use Exception;
use Gulacsi\TeamMember\Exception\Database\EmptyDBTableException as EmptyDBTableException;
use Gulacsi\TeamMember\Log\Logger;
use Gulacsi\TeamMember\Interface\MemberInterface;

/**
 * Shortcode functionality class
 */
class Shortcode implements MemberInterface
{
    use Logger;


    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    /**
     * Get all members from the database argument passed by reference
     * @param array|null $form_data
     * @return bool
     */
    public function get_all_members(array|null &$form_data): bool
    {
        $this->logger(self::DEBUG, self::LOGGING);

        try {
            // db abstraction layer
            global $wpdb;
            $valid = true;

            /** @noinspection SqlNoDataSourceInspection */
            $sql = "SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME;

            $form_data = $wpdb->get_results($sql);

            // print_r($formData);

            if ( !$form_data ) {
                $valid = false;
                throw new EmptyDBTableException('Warning: Table does not contain any records yet.');
            }
        } catch (EmptyDBTableException $ex) {
            echo '<div class="notice notice-warning is-dismissible"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        } catch (Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        } finally {
            return $valid;
        }
    }


    /**
     * List all members as shortcode
     * table and list views
     * extract shortcode arguments
     * - type: 'list' or 'table'
     * - default: 'list'
     *
     * @param array|string $attrs
     * @return string
     */
    public function team_member_form(array|string $attrs): string
    {
        $this->logger(self::DEBUG, self::LOGGING);

        /** @noinspection PhpUnusedLocalVariableInspection */
        global $post;

        $form_data = null;
        $valid = $this->get_all_members($form_data);

        /**
         * extract shortcode arguments
         * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
         * type: 'list' or 'table'
         * default: 'list'
         */
        extract(shortcode_atts(array(
            'type' => 'list',
            'name' => 1,
            'first_name_first' => 0,
            'photo' => 1,
            'phone' => 1,
            'email' => 1,
            'position' => 1,
            'department' => 0,
            'works_since' => 0,
        ), $attrs));

        ob_start();

        if ( $type === 'table' ) {
            include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . '/pages/member/shortcode_table.php';
        } else if ( $type === 'list' ) {
            include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . '/pages/member/shortcode_list.php';
        }

        return ob_get_clean();
    }
}
