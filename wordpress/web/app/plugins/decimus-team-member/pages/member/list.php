<?php

use Gulacsi\TeamMember\File\DataSaver as DataSaver;

if ( current_user_can('manage_options') ) {
    // display member list in a table
    global $wpdb;
    $valid = true;

    /** @noinspection SqlNoDataSourceInspection */
    $sql = "SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME;

    $form_data = $wpdb->get_results($sql);
    // print_r($form_data);

    if ( !$form_data ) {
        $valid = false;
        // echo $sql . '- This form is invalid.';
    }

    $json_data = json_encode($form_data);
} else {
    $json_data = null;
    $valid = false;
    wp_die('You are not authorized to perform this action.');
}


// $current = get_current_screen(  );
// print_r($current);

?>
<div style="margin-left: 15px;">
    <h1 class="mt-1 mb-1"><?php echo __('Team Members', self::TEXT_DOMAIN); ?></h1>
    <form action="" method="post" class="mb-1">
        <input type="hidden" name="action" value="insert">
        <button type="submit" class="button-primary"><span
                    class="team-member dashicons dashicons-plus"></span><?php _e('Add new member', self::TEXT_DOMAIN); ?>
        </button>
    </form>

    <div class="team-member-wrapper">
        <table class="team-member widefat table table-striped">
            <thead>
            <tr>
                <!-- <th scope="col">#</th> -->
                <th scope="col"><?php _e('Action', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Profile img', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Last name', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('First name', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Phone number', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Email', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Position', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Department', self::TEXT_DOMAIN); ?></th>
                <th scope="col"><?php _e('Works since', self::TEXT_DOMAIN); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ( $valid ) :
                foreach ($form_data as $row) :
                    $id = $row->id;
                    $profile_photo = $row->profile_photo;
                    $last_name = $row->last_name;
                    $first_name = $row->first_name;
                    $phone = $row->phone;
                    $email = $row->email;
                    $position = $row->position;
                    $department = $row->department;
                    $works_since = $row->works_since;
                    ?>
                    <tr>
                        <form action="" method="post" class="decimus-team-member-row">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?php echo intval($id) ?>">
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="submit" class="button-secondary"><span
                                                class="team-member dashicons dashicons-edit"></span><?php _e('Edit', 'company-team'); ?>
                                    </button>
                                </div>
                            </td>
                            <td class="small-col">
                                <?php
                                if ( !empty($profile_photo) ) : ?>
                                    <img class="small-image" src="<?php echo esc_url($profile_photo) ?>"
                                         alt="<?php echo esc_html($last_name . ' ' . $first_name); ?>"/>
                                <?php
                                else :
                                    ?>
                                    <img class="small-image"
                                         src="<?php echo plugin_dir_url(__FILE__) . '../../sample-image/profile-placeholder.png'; ?>"
                                         alt="placeholder image"/>
                                <?php
                                endif;
                                ?>
                            </td>
                            <td class="small-col"><?php echo esc_html($last_name); ?></td>
                            <td class="small-col"><?php echo esc_html($first_name); ?></td>
                            <td class="medium-col"><?php echo esc_html($phone); ?></td>
                            <td class="medium-col"><?php echo esc_html($email); ?></td>
                            <td class="medium-col"><?php echo esc_html($position); ?></td>
                            <td class="medium-col"><?php echo esc_html($department); ?></td>
                            <td class="medium-col"><?php echo esc_html($works_since); ?></td>
                        </form>
                        </td>
                    </tr>
                <?php
                endforeach;
            endif;
            ?>
            </tbody>
        </table>
    </div>
    <?php
/*    if ( current_user_can('manage_options') ) {
        $saver = new DataSaver();

        if (!get_option(self::EXPORT_FILENAME)) {
            $filename = wp_hash_password(generate_unique_filename(15));
            add_option(self::EXPORT_FILENAME, $filename);
        } else {
            $filename = get_option(self::EXPORT_FILENAME);
        }

        $saver->save_to_json($filename, $json_data);
        $saver->save_to_csv($filename, $form_data);
    }*/
    ?>
</div>

<br>
<br>
<pre>
    # Decimus Team Members plugin for WordPress

    The plugin handles team members in a custom table, CRUD functionality implemented.

    ## You can paste in the table or list of the members using the shortcode

    - [decimus_team_members]

    The default view is list, but you can change it with the `type` attribute:

    - [decimus_team_members type="table"], or
    - [decimus_team_members type="list"]

    You can also enable or disable specific fields to be shown:

    [decimus_team_members type="list" email="false" works_since="true"]


    The full list of available options with their default values:

    ````
    'type'              => 'list',
    'name'              => true,
    'first_name_first'  => false,
    'photo'             => true,
    'phone'             => true,
    'email'             => true,
    'position'          => true,
    'department'        => false,
    'works_since'       => false,
    ````

    The `first_name_first` attribute determines whether the first name will come before the last name, or the other way around.
    In some languages the last name (family name) is written before the first name.

    TODO list:
    - create a widget to be used in sidebars
    - improve form validation in admin menu
    </pre>
</div>
