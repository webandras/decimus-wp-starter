<?php

!defined( ABSPATH ) || die();

if ( !class_exists( 'Deci_database' ) ) {


    final class Deci_database
    {
        private const TABLENAME = 'deci_demo';


        // Create table to store logged-in user data
        public static function setup(): void
        {
            global $wpdb;
            $table_name = $wpdb->prefix . self::TABLENAME;
            $charset_collate = $wpdb->get_charset_collate();

            // use 2 spaces in between:
            //PRIMARY KEY  (id)
            $sql = "CREATE TABLE $table_name (
              id int(11) NOT NULL AUTO_INCREMENT,
              user_id int(11) NOT NULL,
              user_name varchar(150) NOT NULL,
              last_login datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              PRIMARY KEY  (id)
        ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }


        // Inserts the id, the name, and the time of login of the user
        public static function insert_record($user, $current_user): void
        {

            global $wpdb;
            $name = $current_user->user_login;
            $id = $current_user->ID;

            $table_name = $wpdb->prefix . self::TABLENAME;

            $wpdb->insert( $table_name, [
                'user_id' => $id,
                'user_name' => $name,
                'last_login' => current_time( 'mysql' ),
            ] );

        }

    }

}
