<?php

if ( current_user_can('manage_options') ) {
    // edit form for simple member
    global $wpdb;
    $valid = true;

    /** @noinspection SqlNoDataSourceInspection */
    $sql = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . self::TABLE_NAME . " WHERE id = %d", $id);

    $row = $wpdb->get_row($sql);

    // get the values for the current member record
    $last_name = $row->last_name;
    $first_name = $row->first_name;
    $phone = $row->phone;
    $email = $row->email;
    $position = $row->position;
    $department = $row->department;
    $works_since = $row->works_since;
    $profile_photo = $row->profile_photo;
    // print_r($formData);

    if ( !$row ) {
        $valid = false;
        // echo $sql . '- This form is invalid.';
    }
} else {
    wp_die('You are not authorized to perform this action.');
}
?>
<h1><?php echo __('Edit member', self::TEXT_DOMAIN); ?></h1>


<div class="card bg-light">

    <div class="card-header">
        <h3 class="card-title">
            <?php _e('Member details', 'company-team'); ?>
        </h3>
    </div>

    <div class="card-body">
        <div>
            <form action="#" method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo intval($id); ?>">

                <?php wp_nonce_field('decimus_team_member_edit', 'decimus_team_member_edit_security'); ?>

                <div class="form-group mb-half">
                    <label for="last_name"><?php _e('Last name', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control" name="last_name"
                           value="<?php echo esc_html($last_name); ?>"/>
                </div>

                <div class="form-group mb-half">
                    <label for="first_name"><?php _e('First name', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control" name="first_name"
                           value="<?php echo esc_html($first_name); ?>"/>
                </div>

                <div class="form-group mb-half">
                    <label for="phone"><?php _e('Phone number', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="tel" class="form-control regular-text" name="phone"
                           value="<?php echo esc_html($phone); ?>"
                           aria-placeholder="Phone number should start with the country code like +36 for Hungary, phone number should not contain separator characters like '-' or '/'"/>
                </div>

                <div class="form-group mb-half">
                    <?php if ( $profile_photo ) { ?>
                        <img class="team-member small-img mt1" src="<?php echo sanitize_url($profile_photo); ?>"
                             alt="<?php echo esc_html($first_name) . ' ' . esc_html($last_name); ?>">
                        <br>
                    <?php } ?>
                    <label for="picture"><?php _e('Profile Photo', self::TEXT_DOMAIN); ?></label>
                    <div class="team-member mt-half">
                        <input class="custom-file-label button-secondary" type="file" name="picture"
                               size="25" id="picture" aria-describedby="picture">
                    </div>
                    <span class="italic"></span>
                </div>

                <div class="form-group mb-half">
                    <label for="email"><?php _e('Email address', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="email" class="form-control regular-text" name="email"
                           value="<?php echo sanitize_email($email); ?>"/>
                </div>

                <div class="form-group mb-half">
                    <label for="position"><?php _e('Position at the company', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control regular-text" name="position"
                           value="<?php echo esc_html($position); ?>"/>
                </div>

                <div class="form-group mb-half">
                    <label for="department"><?php _e('Department', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control regular-text" name="department"
                           value="<?php echo esc_html($department); ?>"/>
                </div>

                <div class="form-group mb-half">
                    <label for="works_since"><?php _e('Works Since', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="date" class="form-control regular-text" name="works_since"
                           value="<?php echo esc_html($works_since); ?>"/>
                </div>


                <div class="button-group mt1">
                    <button type="submit" name="action" value="handle_update"
                            class="button-primary"><?php _e('Update', self::TEXT_DOMAIN); ?></button>
                    <button type="submit" name="action" value="list"
                            class="button-secondary"><?php _e('Cancel', self::TEXT_DOMAIN); ?></button>
                    <button type="submit" name="action" value="handle_delete"
                            class="team-member button-secondary button-danger"
                            onclick="return confirm('Are you sure you want to delete this member?'); "><?php _e('Delete', self::TEXT_DOMAIN); ?></button>
                </div>

            </form>
        </div>
    </div>
</div>
