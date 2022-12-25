<?php

namespace Gulacsi\TeamMember\File;

defined('ABSPATH') or die();

use Gulacsi\TeamMember\Exception\File\{FileCloseException, FileOpenException};
use Gulacsi\TeamMember\Interface\MemberInterface;
use Gulacsi\TeamMember\Log\Logger;

final class DataSaver implements MemberInterface
{
    use Logger;

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
     * @param string $filename
     * @param string $json_data
     * @return void
     */
    public function save_to_json(string $filename, string $json_data): void
    {
        if ( !current_user_can('manage_options') ) {
            return;
        }
        $this->logger(self::DEBUG, self::LOGGING);

        // remove illegal characters
        $filename = sanitize_file_name($filename);
        $download_link = plugin_dir_url(__FILE__) . 'download/' . $filename . '.json';
        $filepath = plugin_dir_path(__FILE__) . 'download/' . $filename . '.json';

        // Write to file
        try {
            $result = fopen($filepath, 'w+');

            if ( $result === false ) {
                throw new FileOpenException('Cannot open the file because it is currently not accessible.');
            }

            fwrite($result, $json_data);

            if ( !fclose($result) ) {
                throw new FileCloseException('Writing data to file failed.');
            }
            $success_message = '<a href="' . $download_link . '">' . __('Download table data in JSON', 'company-team') . '</a>';
            echo '<div><p>' . $success_message . '</p></div>';

        } catch (FileOpenException|FileCloseException|\Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '. </p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }
    }


    /**
     * Save table data to csv file
     * @param string $filename
     * @param array $form_data
     * @param string $delimiter
     * @return void
     */
    public function save_to_csv(string $filename, array $form_data, string $delimiter = ';'): void
    {
        if ( !current_user_can('manage_options') ) {
            return;
        }
        $this->logger(self::DEBUG, self::LOGGING);

        // remove illegal characters
        $filename = sanitize_file_name($filename);
        $download_link = plugin_dir_url(__FILE__) . 'download/' . $filename . '.csv';
        $filepath = plugin_dir_path(__FILE__) . 'download/' . $filename . '.csv';


        // Write to file
        try {
            // binary safe mode
            $result = fopen($filepath, 'w+');

            if ( $result === false ) {
                throw new FileOpenException('Cannot open the file because it is currently not accessible.');
            }

            // all prop names as comma separated values
            $csv_header = $form_data[0]->id . $delimiter .
                $form_data[0]->profile_photo . $delimiter .
                $form_data[0]->last_name . $delimiter .
                $form_data[0]->first_name . $delimiter .
                $form_data[0]->phone . $delimiter .
                $form_data[0]->email . $delimiter .
                $form_data[0]->position . $delimiter .
                $form_data[0]->department . $delimiter .
                $form_data[0]->works_since;


            // The ISO-8859-2 encoding is specific to Hungarian language
            fwrite($result, iconv('utf-8', 'ISO-8859-2', "$csv_header\r\n"));
            // uncomment next line to use utf-8 encoding
            // fwrite($result, utf8_encode($csv_header) . "\r\n");

            foreach ($form_data as $row) {
                $tmp_row = $row->id . $delimiter .
                    $row->profile_photo . $delimiter .
                    $row->last_name . $delimiter .
                    $row->first_name . $delimiter .
                    $row->phone . $delimiter .
                    $row->email . $delimiter .
                    $row->position . $delimiter .
                    $row->department . $delimiter .
                    $row->works_since;
                fwrite($result, iconv('utf-8', 'ISO-8859-2', "$tmp_row\r\n"));
                // fwrite($result, utf8_encode($tmp_row) . "\r\n");
            }

            if ( !fclose($result) ) {
                throw new FileCloseException('Writing data to file failed.');
            }

            $success_message = '<a href="' . $download_link . '">'
                . __('Download table in CSV', 'company-team') . '</a>';

            echo '<div><p>' . $success_message . '</p></div>';
        } catch (FileOpenException|FileCloseException|\Exception $ex) {
            echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '. </p></div>';
            $this->exception_logger(self::LOGGING, $ex);
        }
    }
}
