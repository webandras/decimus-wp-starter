<?php

namespace Decimus\Team_member\Controller;

defined( 'ABSPATH' ) or die();


require_once dirname( __FILE__, 4 ) . '/decimus-general/exceptions/database.php';

use Exception;
use Decimus\Team_member\Model\Member;
use Decimus\Team_member\Interface\Controller_interface;
use Decimus\Team_member\Form\Validate;


/**
 * CRUD for members
 */
final class Member_controller extends Member implements Controller_interface
{
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
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        if ( current_user_can( 'administrator' ) ) {

            global $id;

            if ( isset( $_POST ) && !empty( $_POST ) ) {
                $action = $_POST['action'];

                if ( $_POST['id'] ?? 0 ) {
                    $id = absint( intval( $_POST['id'] ) );
                }

                switch ( $action ) {
                    // add new member form page
                    case 'insert':
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/insert.php';
                        break;

                    // edit member form page
                    case 'edit':
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/edit.php';
                        break;

                    // handler function when updating
                    case 'handle_update':
                        $this->handle_update();
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/list.php';
                        break;

                    // handler function when deleting
                    case 'handle_delete':
                        $this->handle_delete();
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/list.php';
                        break;

                    // handler function when inserting new member
                    case 'handle_insert':
                        $this->handle_insert();
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/list.php';
                        break;

                    default:
                        // list elements
                        include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/list.php';
                        break;
                }
            } else {
                include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . 'views/member/list.php';
            }
        }
    }


    /**
     * Insert new record, add new team member
     * @return void
     */
    public function handle_insert(): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        try {
            // !!! verify insert nonce !!!
            $this->verify_nonce( 'insert' );

            if ( !current_user_can( 'manage_options' ) ) {
                throw new \Decimus_permissions_exception( 'You do not have sufficient permissions to view this page.' );
            }


            // get sanitized form values from inputs
            $sanitized_data = $this->get_form_input_field_values();

            $response = $this->insert( $sanitized_data );

            if ( $response === false ) {
                throw new \Decimus_insert_record_exception( 'Unable to insert new record.' );
            } else {
                echo '<div class="notice notice-success is-dismissible"><p>Team member successfully added.</p></div>';
            }

        } catch ( \Decimus_insert_record_exception|\Decimus_permissions_exception|Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';

            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }

    }


    /**
     * Update member
     * @return void
     */
    public function handle_update(): void
    {
        // debug log and log to file
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        try {
            // !!! verify edit nonce !!!
            $this->verify_nonce( 'edit' );

            if ( !current_user_can( 'manage_options' ) ) {
                throw new \Decimus_permissions_exception( 'You do not have sufficient permissions to view this page.' );
            }


            // get sanitized form values from inputs
            $data = $this->get_form_input_field_values();

            // if we do not want to update the profile_photo field
            if ( $data['new_file_url'] == null ) {
                $response = $this->update( $data );
            } else {
                // Change profile_photo too
                $response = $this->update( $data, true );
            }

            if ( $response === false ) {
                throw new \Decimus_update_record_exception( 'Unable to update member record.' );
            } else {
                echo '<div class="notice notice-success is-dismissible"><p>Team member data successfully updated.</p></div>';
            }

        } catch ( \Decimus_update_record_exception|\Decimus_permissions_exception|Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';

            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }

    }


    /**
     * delete member
     * @return void
     */
    public function handle_delete(): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        try {

            $this->verify_nonce( 'delete' );

            if ( !current_user_can( 'manage_options' ) ) {
                throw new \Decimus_permissions_exception( 'You do not have sufficient permissions to view this page.' );
            }


            if ( $_POST['id'] ?? 0 ) {
                $id = absint( intval( $_POST['id'] ) );

                $response = $this->delete( $id );

                if ( $response === false ) {
                    throw new \Decimus_delete_record_exception( 'Unable to delete team member.' );
                } else {
                    echo '<div class="notice notice-success is-dismissible"><p>Team member successfully deleted.</p></div>';
                }
            }

        } catch ( \Decimus_delete_record_exception|\Decimus_permissions_exception|Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }

    }


    /**
     * Add new member
     * @return void
     */
    public function add_form(): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        try {

            if ( !current_user_can( 'manage_options' ) ) {
                throw new \Decimus_permissions_exception( 'You do not have sufficient permissions to view this page.' );
            }

            if ( !empty( $_POST ) ) {
                $this->form_action();
            } else {
                include DECIMUS_TEAM_MEMBER_PLUGIN_DIR . '/views/member/insert.php';
            }

        } catch ( \Decimus_permissions_exception|Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }
    }


    /**
     * Get list of all records
     * @return void
     */
    public function list_table(): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        try {

            global $wpdb;

            // note: current_user_can() always returns false if the user is not logged in
            if ( !current_user_can( 'manage_options' ) ) {
                throw new \Decimus_permissions_exception(
                    'You do not have sufficient permissions to view this page.'
                );
            }

            $this->form_action();

        } catch ( \Decimus_permissions_exception|Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';
            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }
    }

}
