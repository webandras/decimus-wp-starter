<h1><?php _e('Add new member', self::TEXT_DOMAIN); ?></h1>

<div class="card bg-light">
    <div class="card-header">

        <h3 class="card-title">
            <?php _e('Add member details', self::TEXT_DOMAIN); ?>
        </h3>
    </div>
    <div class="card-body">
        <div>
            <form action="#" method="post" enctype="multipart/form-data">

                <?php wp_nonce_field('decimus_team_member_insert', 'decimus_team_member_insert_security'); ?>

                <div class="form-group mb-half">
                    <label for="last_name"><?php _e('Last name', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control" name="last_name" value=""/>
                </div>

                <div class="form-group mb-half">
                    <label for="first_name"><?php _e('First name', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control" name="first_name" value=""/>
                </div>

                <div class="form-group mb-half">
                    <label for="picture"><?php _e('Profile Photo', self::TEXT_DOMAIN); ?></label>
                    <div class="company-team mt-half">
                        <input type="file" name="picture" id="picture" aria-describedby="picture">
                    </div>
                    <span class="italic"></span>
                </div>

                <div class="form-group mb-half">
                    <label for="phone"><?php _e('Phone number', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="tel" class="form-control regular-text" name="phone" value=""
                           aria-placeholder="Phone number should start with the country code like +36 for Hungary, phone number should not contain separator characters like '-' or '/'"/>
                </div>

                <div class="form-group mb-half">
                    <label for="email"><?php _e('Email address', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="email" class="form-control regular-text" name="email" value=""/>
                </div>

                <div class="form-group mb-half">
                    <label for="position"><?php _e('Position at the company', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control regular-text" name="position" value=""/>
                </div>

                <div class="form-group mb-half">
                    <label for="department"><?php _e('Department', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="text" class="form-control regular-text" name="department" value=""/>
                </div>

                <div class="form-group mb-half">
                    <label for="works_since"><?php _e('Works Since', self::TEXT_DOMAIN); ?></label><br/>
                    <input type="date" class="form-control regular-text" name="works_since" value=""/>
                </div>


                <div class="mt1">
                    <button type="submit" name="action" value="handle_insert"
                            class="button-primary"><?php _e('Save member', self::TEXT_DOMAIN); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
