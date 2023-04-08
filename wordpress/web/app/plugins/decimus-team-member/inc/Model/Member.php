<?php

namespace Decimus\Team_member\Model;

defined('ABSPATH') or die();

use Decimus\Team_member\Interface\Member_interface;


class Member extends Model implements Member_interface
{
    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    protected function list(): void
    {
    }

    protected function insert(array $data): bool
    {
        global $wpdb;

        // prepare query
        return $wpdb->insert(
            $wpdb->prefix . self::TABLE_NAME,
            array(
                'profile_photo' => $data['new_file_url'],
                'last_name' => $data['last_name'],
                'first_name' => $data['first_name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'position' => $data['position'],
                'department' => $data['department'],
                'works_since' => $data['works_since']
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') // data format
        );
    }

    protected function update(array $data, bool $has_photo = false): bool
    {
        global $wpdb;

        $new_data = [
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'position' => $data['position'],
            'department' => $data['department'],
            'works_since' => $data['works_since']
        ];


        if ( $has_photo ) {
            // prepare query, update table
            $res = $wpdb->update(
                $wpdb->prefix . self::TABLE_NAME,
                array_merge($new_data, ['profile_photo' => $data['new_file_url']]),
                // where clause
                array('id' => $data['id']),
                // data format
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
                // where format
                array('%d')
            );
        } else {
            // prepare query, update table
            $res = $wpdb->update(
                $wpdb->prefix . self::TABLE_NAME,
                $new_data,
                // where clause
                array('id' => $data['id']),
                // data format
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s'),
                // where format
                array('%d')
            );
        }
        return $res;
    }

    protected function delete(int $id): bool
    {
        global $wpdb;
        // prepare get statement protect against SQL inject attacks!
        $sql = $wpdb->prepare("DELETE FROM " . $wpdb->prefix . self::TABLE_NAME . " WHERE id = %d", $id);

        // perform query
        return $wpdb->query($sql);
    }

    // !!! verify edit nonce !!!
    protected function verify_nonce(string $action): void
    {
        if ( !isset($_POST['decimus_team_member_' . $action . '_security']) ||
            !wp_verify_nonce($_POST['decimus_team_member_' . $action . '_security'], 'decimus_team_member_' . $action)
        ) {
            wp_die('Sorry, your nonce did not verify.');
        }
    }
}
