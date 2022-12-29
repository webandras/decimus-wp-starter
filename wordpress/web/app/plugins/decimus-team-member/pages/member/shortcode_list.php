<ul class="team-member grid-list">
    <?php
    if ( $valid ) :
        foreach ($form_data as $row) :

            $profile_photo_field = $row->profile_photo;
            $last_name_field = $row->last_name;
            $first_name_field = $row->first_name;
            $phone_field = $row->phone;
            $email_field = $row->email;
            $position_field = $row->position;
            $department_field = $row->department;
            $works_since_field = $row->works_since;
            ?>
            <li class="mb1">
                <?php
                if ( $photo ) :
                    if ( !empty($profile_photo_field) ) : ?>
                        <img src="<?php echo esc_url($profile_photo_field) ?>"
                             alt="<?php echo ($first_name_first) ? (esc_html($first_name_field) . ' ' . esc_html($last_name_field)) : (esc_html($last_name_field) . ' ' . esc_html($first_name_field)); ?>"/>
                    <?php
                    else :
                        ?>
                        <img class="team-member-img" src="<?php echo plugin_dir_url(__FILE__) . '../../sample-image/profile-placeholder.png'; ?>"
                             alt="placeholder image"/>
                    <?php
                    endif;
                endif;
                ?>

                <div class="team-member list-heading">
                    <?php if ( $name ) : ?>
                        <h4>
                            <?php if ( $first_name_first ) : ?>
                                <?php echo esc_html($first_name_field) . ' ' . esc_html($last_name_field); ?>
                            <?php else :
                                echo esc_html($last_name_field) . ' ' . esc_html($first_name_field);
                            endif; ?>
                        </h4>
                    <?php endif; ?>

                    <?php if ( $position ) : ?>
                        <div><?php echo esc_html($position_field); ?></div>
                    <?php endif; ?>

                    <?php if ( $department ) : ?>
                        <div><?php echo esc_html($department_field); ?></div>
                    <?php endif; ?>

                    <?php if ( $works_since ) : ?>
                        <div><?php echo esc_html($works_since_field); ?></div>
                    <?php endif; ?>
                </div>

                <ul>
                    <?php if ( $phone ) : ?>
                        <li>
                            <a href="<?php echo 'tel:' . esc_attr(str_replace(' ', '', $phone_field)) ?>"><?php echo esc_html($phone_field); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                        <li>
                            <a href="<?php echo 'mailto:' . sanitize_email($email_field); ?>"><?php echo esc_html($email_field); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php
        endforeach;
    endif;
    ?>
</ul>
