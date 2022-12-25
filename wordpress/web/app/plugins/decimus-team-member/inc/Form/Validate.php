<?php

namespace Gulacsi\TeamMember\Form;

use const ABSPATH;
use Exception;
use Gulacsi\TeamMember\Exception\Form\{ImageInputException, NoImageUploadException};

trait Validate
{
    /**
     * Get form input, sanitize values
     * @return array associative
     */
    public function get_form_input_field_values(): array
    {
        // store escaped user input field values
        $form_values = array();

        if ( $_FILES['picture']['name'] != null && !empty($_FILES['picture']) ) {
            // echo '<pre>';
            // print_r($_FILES['picture']);
            // echo '</pre>';
            try {
                // get error code from file input object
                $error_code = intval($_FILES['picture']['error']);
                echo $error_code;

                $profile_picture = $_FILES['picture'];

                // POST image error
                if ( $error_code > 0 ) {
                    /**
                     * Error code explanations
                     * @see https://www.php.net/manual/en/features.file-upload.errors.php
                     */
                    throw match ($error_code) {
                        1 => new ImageInputException(
                            'The uploaded file exceeds the upload_max_filesize directive in php.ini.'
                        ),
                        2 => new ImageInputException(
                            'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'
                        ),
                        3 => new ImageInputException(
                            'The uploaded file was only partially uploaded.'
                        ),
                        4 => new NoImageUploadException(
                            'No profile image was uploaded. The existing image will be used, or if no image exists, a placeholder image will be used.'
                        ),
                        6 => new ImageInputException(
                            'Missing a temporary folder.'
                        ),
                        7 => new ImageInputException(
                            'Failed to write file to disk.'
                        ),
                        8 => new ImageInputException(
                            'A PHP extension stopped the file upload.'
                        ),
                        default => new ImageInputException(
                            'An unspecified PHP error occurred.'
                        ),
                    };
                }
                $new_file_url = $this->add_profile_photo($profile_picture);
                $form_values['new_file_url'] = $this->sanitize_input($new_file_url, 'url');

            } catch (NoImageUploadException $ex) {
                echo '<div class="notice notice-warning is-dismissable"><p>' . $ex->getMessage() . '</p></div>';

                $form_values['new_file_url'] = '';
            } catch (ImageInputException $ex) {
                echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '. </p></div>';

                $form_values['new_file_url'] = '';
            } catch (Exception $ex) {
                echo '<div class="notice notice-error"><p>' . $ex->getMessage() . '</p></div>';

                $form_values['new_file_url'] = '';
            }
        } else {
            $form_values['new_file_url'] = '';
        }

        if ( $_POST['id'] ?? 0 ) {
            $id = $this->sanitize_input($_POST['id'], 'integer');
            $form_values['id'] = absint($id);
        }

        if ( $_POST['last_name'] ?? 0 ) {
            $last_name = $this->sanitize_input($_POST['last_name']);
            $form_values['last_name'] = $last_name;
        } else {
            $form_values['last_name'] = '';
        }

        if ( $_POST['first_name'] ?? 0 ) {
            $first_name = $this->sanitize_input($_POST['first_name']);
            $form_values['first_name'] = $first_name;
        } else {
            $form_values['first_name'] = '';
        }

        if ( $_POST['phone'] ?? 0 ) {
            $phone = $this->sanitize_input($_POST['phone']);
            $form_values['phone'] = $phone;
        } else {
            $form_values['phone'] = '';
        }

        if ( $_POST['email'] ?? 0 ) {
            $email = $this->sanitize_input($_POST['email'], 'email');
            $form_values['email'] = $email;
        } else {
            $form_values['email'] = '';
        }

        if ( $_POST['position'] ?? 0 ) {
            $position = $this->sanitize_input($_POST['position']);
            $form_values['position'] = $position;
        } else {
            $form_values['position'] = '';
        }

        if ( $_POST['department'] ?? 0 ) {
            $department = $this->sanitize_input($_POST['department']);
            $form_values['department'] = $department;
        } else {
            $form_values['department'] = '';
        }

        if ( $_POST['works_since'] ?? 0 ) {
            $works_since = $this->sanitize_input($_POST['works_since']);
            $form_values['works_since'] = $works_since;
        } else {
            $form_values['works_since'] = date('Y-m-d', time());
        }


        return $form_values;
    }

    /**
     * Sanitizes input values
     *
     * @param string $input
     * @param string $type
     * @return string
     */
    public function sanitize_input(string $input, string $type = ''): string
    {
        $value = trim($input);
        return match ($type) {
            'email' => sanitize_email($value),
            'url' => sanitize_url($value),
            'integer' => intval($value),
            default => wp_strip_all_tags($value),
        };

    }


    /**
     * Add profile photo, save file in media folder
     * @return string the image url
     */
    public function add_profile_photo($profile_picture): string
    {

        // upload profile image
        // @see
        // https://rudrastyh.com/wordpress/how-to-add-images-to-media-library-from-uploaded-files-programmatically.html
        // wp media folder
        $wordpress_upload_dir = wp_upload_dir();
        // $wordpress_upload_dir['path'] is the full server path to wp-content/uploads/2017/05,
        // for multisite works good as well
        // $wordpress_upload_dir['url'] the absolute URL to the same folder, actually
        // we do not need it, just to show the link to file

        // store file object
        // $profile_picture = $_FILES['$profile_picture'];

        // new path for the image in media folder
        $timestamp = time();
        $new_file_path = $wordpress_upload_dir['path'] . '/' . $profile_picture['name'] . '-' . $timestamp;
        $new_file_url = $wordpress_upload_dir['url'] . '/' . $profile_picture['name'] . '-' . $timestamp;

        if ( empty($profile_picture) ) {
            wp_die('File is not selected.');
        }

        if ( $profile_picture['error'] ) {
            wp_die($profile_picture['error']);
        }

        if ( $profile_picture['size'] > wp_max_upload_size() ) {
            wp_die('It is too large than expected.');
        }

        // get mime type
        $new_file_mime = mime_content_type($profile_picture['tmp_name']);

        if ( !in_array($new_file_mime, get_allowed_mime_types()) ) {
            wp_die('WordPress does not allow this type of uploads.');
        }

        // move file from temp to media folder
        if ( move_uploaded_file($profile_picture['tmp_name'], $new_file_path) ) {
            // Insert an attachment
            $upload_id = wp_insert_attachment(array(
                'guid' => $new_file_url, // use the url here, not the path
                'post_mime_type' => $new_file_mime,
                'post_title' => preg_replace('/\.[^.]+$/', '', $profile_picture['name']),
                'post_content' => '',
                'post_status' => 'inherit'
            ), $new_file_url);
        } else {
            wp_die('Moving file to media folder failed.');
        }

        // wp_generate_attachment_metadata() won't work if you do not include this file
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Generate and save the attachment metas into the database
        wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

        // Show the uploaded file in browser, not needed
        // wp_redirect($wordpress_upload_dir['url'] . '/' . basename($new_file_path));

        // return image url to store it in db table
        return $new_file_url;
    }
}
