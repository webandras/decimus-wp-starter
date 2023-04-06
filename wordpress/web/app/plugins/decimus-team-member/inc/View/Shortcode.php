<?php

namespace Gulacsi\TeamMember\View;

defined('\ABSPATH') or die();

use Exception;
use Gulacsi\TeamMember\Exception\Database\EmptyDBTableException as EmptyDBTableException;
use Gulacsi\TeamMember\Interface\MemberInterface;

/**
 * Shortcode functionality class
 */
class Shortcode implements MemberInterface
{


    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    /**
     * Get all members from the database argument passed by reference
     * @param  array|null  $form_data
     * @return bool
     */
    public function get_all_members(array|null &$form_data): bool
    {
        if (self::LOGGING) {
            global $decimus_team_member_log;
            $decimus_team_member_log->logInfo("Entering - ".__FILE__.":".__METHOD__.":".__LINE__);
        }
        if (self::DEBUG) {
            $info_text = "Entering - ".__FILE__.":".__METHOD__.":".__LINE__;
            echo '<div class="notice notice-info is-dismissible">'.$info_text.'</p></div>';
        }


        try {

            // db abstraction layer
            global $wpdb;
            $valid = true;

            $sql = "SELECT * FROM ".$wpdb->prefix.self::TABLE_NAME;

            $form_data = $wpdb->get_results($sql);

            if (!$form_data) {
                $valid = false;
                throw new EmptyDBTableException('Warning: Table does not contain any records yet.');
            }

        } catch (EmptyDBTableException $ex) {

            echo '<div class="notice notice-warning is-dismissible"><p>'.$ex->getMessage().'</p></div>';
            if (self::LOGGING) {
                global $decimus_team_member_log;
                $decimus_team_member_log->logWarn(
                    $ex->getMessage()." - ".__FILE__.":".__METHOD__.":".__LINE__);
            }

        } catch (Exception $ex) {

            echo '<div class="notice notice-error"><p>'.$ex->getMessage().'</p></div>';
            if (self::LOGGING) {
                global $decimus_team_member_log;
                $decimus_team_member_log->logError(
                    $ex->getMessage()." - ".__FILE__.":".__METHOD__.":".__LINE__);
            }

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
     * @param  array|string  $attrs
     * @return string
     */
    public function team_member_form(array|string $attrs): string
    {
        if (self::LOGGING) {
            global $decimus_team_member_log;
            $decimus_team_member_log->logInfo("Entering - ".__FILE__.":".__METHOD__.":".__LINE__);
        }
        if (self::DEBUG) {
            $info_text = "Entering - ".__FILE__.":".__METHOD__.":".__LINE__;
            echo '<div class="notice notice-info is-dismissible">'.$info_text.'</p></div>';
        }


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

        if ($type === 'table') {
            include DECIMUS_TEAM_MEMBER_PLUGIN_DIR.'/pages/member/shortcode_table.php';
        } else {
            if ($type === 'list') {
                include DECIMUS_TEAM_MEMBER_PLUGIN_DIR.'/pages/member/shortcode_list.php';
            }
        }

        return ob_get_clean();
    }
}
