<?php

namespace Gulacsi\TeamMember\Controller;

defined('ABSPATH') or die();

use Exception;
use Gulacsi\TeamMember\Model\Member;
use Gulacsi\TeamMember\Interface\ControllerInterface as ControllerInterface;
use Gulacsi\TeamMember\Exception\Database\{
    DeleteRecordException,
    InsertRecordException,
    PermissionsException,
    UpdateRecordException
};
use Gulacsi\TeamMember\Log\Logger;
use Gulacsi\TeamMember\Form\Validate;
use wpdb;

/**
 * CRUD for members
 */
class MemberController extends Member implements ControllerInterface
{
    use Logger;
    use Validate;


    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
    }


    /**
     * Action switcher
     */
    public function form_action(): void
    {
        $this->logger(self::DEBUG, self::LOGGING);

        global $id;

        if ( isset($_POST) && !empty($_POST) ) {
            $action = $_POST['action'];

            if ( $_POST['id'] ?? 0 ) {
                $id = absint(intval($_POST['id']));
            }

            switch ($action) {
                // add new member form page
                case 'insert':
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/insert.php';
                    break;

                // edit member form page
                case 'edit':
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/edit.php';
                    break;

                // handler function when updating
                case 'handle_update':
                    $this->handle_update();
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/list.php';
                    break;

                // handler function when deleting
                case 'handle_delete':
                    $this->handle_delete();
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/list.php';
                    break;

                // handler function when inserting new member
                case 'handle_insert':
                    $this->handle_insert();
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/list.php';
                    break;

                default:
                    // list elements
                    include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/list.php';
                    break;
            }
        } else {
            include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'pages/member/list.php';
        }
    }


    /**
     * Insert new record, add new team member
     * @return void
     */
    public function handle_insert(): void
    {
        $this->logger(self::DEBUG, self::LOGGING);

        // !!! verify insert nonce !!!
        $this->verify_nonce('insert');

        try {
            // get sanitized form values from inputs
            $sanitized_data = $this->get_form_input_field_values();

            $response = $this->insert($sanitized_data);

            if ( $response === false ) {
                throw new InsertRecordException('Unable to insert new record.');
            } else {
                echo '<div class="notice notice-success is-dismissible"><p>Team member successfully added.</p></div>';
            }
        } catch (InsertRecordException|Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }

    }


    /**
     * Update member
     * @return void
     */
    public function handle_update(): void
    {
        // debug log and log to file
        $this->logger(self::DEBUG, self::LOGGING);

        // !!! verify edit nonce !!!
        $this->verify_nonce('edit');

        try {
            // get sanitized form values from inputs
            $data = $this->get_form_input_field_values();

            // if we do not want to update the profile_photo field
            if ($data['new_file_url'] == null) {
                $response = $this->update($data);
            } else {
                // Change profile_photo too
                $response = $this->update($data, true);
            }

            if ( $response === false ) {
                throw new UpdateRecordException('Unable to update member record.');
            } else {
                echo '<div class="notice notice-success is-dismissible"><p>Team member data successfully updated.</p></div>';
            }
        } catch (UpdateRecordException|Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }

    }


    /**
     * delete member
     * @return void
     */
    public function handle_delete(): void
    {
        $this->logger(self::DEBUG, self::LOGGING);

        $this->verify_nonce('delete');

        try {
            if ( $_POST['id'] ?? 0 ) {
                $id = absint(intval($_POST['id']));

                $response = $this->delete($id);

                if ( $response === false ) {
                    throw new DeleteRecordException('Unable to delete team member.');
                } else {
                    echo '<div class="notice notice-success is-dismissible"><p>Team member successfully deleted.</p></div>';
                }
            }
        } catch (DeleteRecordException|Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }

    }


    /**
     * Add new member
     * @return void
     */
    public function add_form(): void
    {
        $this->logger(self::DEBUG, self::LOGGING);

        try {
            if ( !current_user_can('manage_options') ) {
                throw new PermissionsException('You do not have sufficient permissions to view this page.');
            }

            if ( !empty($_POST) ) {
                $this->form_action();
            } else {
                include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . '/pages/member/insert.php';
            }
        } catch (PermissionsException|Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }
    }


    /**
     * Get list of all records
     * @return void
     */
    public function list_table(): void
    {
        $this->logger(self::DEBUG, self::LOGGING);

        try {
            /** @noinspection PhpUnusedLocalVariableInspection */
            /** @var wpdb $wpdb */
            global $wpdb;

            // note: current_user_can() always returns false if the user is not logged in
            if ( !current_user_can('manage_options') ) {
                throw new PermissionsException(
                    'You do not have sufficient permissions to view this page.'
                );
            }
            $this->form_action();

        } catch (PermissionsException|Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }
    }

}
