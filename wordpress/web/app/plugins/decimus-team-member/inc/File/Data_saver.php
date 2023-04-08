<?php

namespace Decimus\Team_member\File;

defined( 'ABSPATH' ) or die();

require_once dirname( __FILE__, 4 ) . '/decimus-general/exceptions/file.php';

use Decimus\Team_member\Interface\Member_interface;


final class Data_saver implements Member_interface
{

    private static $instance;

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    /**
     * Get class instance, if not exists -> instantiate it
     * @return self $instance
     */
    public static function get_instance(): self
    {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Save table data to json file
     * @param  string  $filename
     * @param  string  $json_data
     * @return void
     */
    public function save_to_json(string $filename, string $json_data): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }

        // remove illegal characters
        $filename = sanitize_file_name( $filename );
        $download_link = plugin_dir_url( dirname( __FILE__, 2 ) ) . 'download/' . $filename . '.json';
        $filepath = dirname( __FILE__, 3 ) . '/download/' . $filename . '.json';

        // Write to file
        try {
            $result = fopen( $filepath, 'w+' );

            if ( $result === false ) {
                throw new \Decimus_file_open_exception( 'Cannot open the file because it is currently not accessible.' );
            }

            fwrite( $result, $json_data );

            if ( !fclose( $result ) ) {
                throw new \Decimus_file_close_exception( 'Writing data to file failed.' );
            }
            $success_message = '<a href="' . $download_link . '">' . __( 'Download table data in JSON',
                    'company-team' ) . '</a>';
            echo '<div><p>' . $success_message . '</p></div>';

        } catch ( \Decimus_file_open_exception|\Decimus_file_close_exception|\Exception $ex ) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '. </p></div>';
            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }
        }
    }


    /**
     * Save table data to csv file
     * @param  string  $filename
     * @param  array  $form_data
     * @param  string  $delimiter
     * @return void
     */
    public function save_to_csv(string $filename, array $form_data, string $delimiter = ';'): void
    {
        if ( self::LOGGING ) {
            global $dtm_log;
            $dtm_log->logInfo( "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
        }
        if ( self::DEBUG ) {
            $info_text = "Entering - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__;
            echo '<div class="notice notice-info is-dismissible">' . $info_text . '</p></div>';
        }


        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }

        // remove illegal characters
        $filename = sanitize_file_name( $filename );
        $download_link = plugin_dir_url( dirname( __FILE__, 2 ) ) . 'download/' . $filename . '.csv';
        $filepath = dirname( __FILE__, 3 ) . '/download/' . $filename . '.csv';


        // Write to file
        try {

            // binary safe mode
            $result = fopen( $filepath, 'w+' );

            if ( $result === false ) {
                throw new \Decimus_file_open_exception( 'Cannot open the file because it is currently not accessible.' );
            }

            // all prop names as comma separated values
            $csv_header = 'Id' . $delimiter .
                'Profilkép' . $delimiter .
                'Családnév' . $delimiter .
                'Keresztnév' . $delimiter .
                'Telefon' . $delimiter .
                'E-mail' . $delimiter .
                'Pozíció' . $delimiter .
                'Részleg' . $delimiter .
                'Kezdés dátuma';

            fwrite( $result, iconv( 'utf-8', 'utf-8', "$csv_header\r\n" ) );

            foreach ( $form_data as $row ) {
                $tmp_row = $row->id . $delimiter .
                    $row->profile_photo . $delimiter .
                    $row->last_name . $delimiter .
                    $row->first_name . $delimiter .
                    $row->phone . $delimiter .
                    $row->email . $delimiter .
                    $row->position . $delimiter .
                    $row->department . $delimiter .
                    $row->works_since;
                // The ISO-8859-2 encoding is specific to Hungarian language
                fwrite( $result, iconv( 'utf-8', 'utf-8', "$tmp_row\r\n" ) );
                // uncomment next line to use utf-8 encoding
                // fwrite($result, utf8_encode($tmp_row) . "\r\n");
            }

            if ( !fclose( $result ) ) {
                throw new \Decimus_file_close_exception( 'Writing data to file failed.' );
            }

            $success_message = '<a href="' . $download_link . '">'
                . __( 'Download table in CSV', 'company-team' ) . '</a>';

            echo '<div><p>' . $success_message . '</p></div>';

        } catch ( \Decimus_file_open_exception|\Decimus_file_close_exception|\Exception $ex ) {

            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '. </p></div>';
            if ( self::LOGGING ) {
                global $dtm_log;
                $dtm_log->logError(
                    $ex->getMessage() . " - " . __FILE__ . ":" . __METHOD__ . ":" . __LINE__ );
            }

        }
    }
}
