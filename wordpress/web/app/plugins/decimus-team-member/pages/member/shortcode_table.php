<table class="team-member table table-striped">
    <thead>
    <tr>
        <?php if ( $photo ) : ?>
            <th scope="col"></th>
        <?php endif; ?>
        <?php if ( $name ) : ?>
            <th scope="col"><?php _e('Name', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
        <?php if ( $position ) : ?>
            <th scope="col"><?php _e('Position', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
        <?php if ( $department ) : ?>
            <th scope="col"><?php _e('Department', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
        <?php if ( $works_since ) : ?>
            <th scope="col"><?php _e('Works since', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
        <?php if ( $phone ) : ?>
            <th scope="col"><?php _e('Phone', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
        <?php if ( $email ) : ?>
            <th scope="col"><?php _e('Email', self::TEXT_DOMAIN); ?></th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php if ( $valid ) {
        foreach ($form_data as $row) {
            $profile_photo_field = $row->profile_photo;
            $last_name_field = $row->last_name;
            $first_name_field = $row->first_name;
            $phone_field = $row->phone;
            $email_field = $row->email;
            $position_field = $row->position;
            $department_field = $row->department;
            $works_since_field = $row->works_since;
            ?>
            <tr>
                <td class="image-table">
                    <?php if ($photo) {
                    if (!empty($profile_photo_field)) { ?>
                        <img src="<?php echo esc_url($profile_photo_field) ?>"
                             alt="<?php echo esc_html(($first_name_first) ? ($first_name_field . ' ' . $last_name_field) : ($last_name_field . ' ' . $first_name_field)); ?>"/>
                        <?php
                    } else {
                    ?>
                    <img src="<?php echo plugin_dir_url(__FILE__) . '../../sample-image/profile-placeholder.png'; ?>"
                         alt="<?php esc_attr_e('placeholder image', self::TEXT_DOMAIN); ?> />
                    <?php
                         } ?>
                <?php } ?>
                </td>
          <?php if ( $name ) { ?>
            <td>
              <?php echo esc_html($first_name_first ? ($first_name_field . ' ' . $last_name_field) : ($last_name_field . ' ' . $first_name_field)) ?>
            </td>
          <?php } ?>

          <?php if ( $position ) { ?>
            <td><?php echo esc_html($position_field); ?></td>
          <?php } ?>

          <?php if ( $department ) { ?>
            <td><?php echo esc_html($department_field); ?></td>
          <?php } ?>

          <?php if ( $works_since ) { ?>
            <td><?php echo esc_html($works_since_field); ?></td>
          <?php } ?>

          <?php if ($phone) { ?>
            <td>
                <a href="<?php echo esc_html('tel:'. $phone_field); ?>">
                    <?php echo esc_html($phone_field); ?>
                </a>
            </td>
          <?php } ?>
          <?php if ( $email ) { ?>
              <td>
                  <a href="<?php echo 'mailto:' . sanitize_email($email_field); ?>"><?php echo esc_html($email_field); ?></a>
              </td>
          <?php } ?>
        </tr>
        <?php
        }
    }
    ?>
    </tbody>
</table>

<div id="decimus-team-member-table">

</div>
