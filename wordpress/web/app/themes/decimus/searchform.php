<!-- Search Button Outline Secondary Right -->
<form class="searchform input-group form-inline" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="sr-only screen-reader-text" for="s"><?php esc_html_e( 'Search', 'decimus' ); ?></label>
    <input type="text" name="s" class="form-control" placeholder="<?php _e( 'Search', 'decimus' ); ?>">
    <button type="submit" class="input-group-text btn-sm btn-secondary"><i class="fas fa-search"></i></button>
</form>
