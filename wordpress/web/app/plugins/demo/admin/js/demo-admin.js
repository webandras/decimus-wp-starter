;jQuery(document).ready(function ($) {

    // Received this data via wp_localize_script, security is a nonce value
    var data = {
        action: 'deci_admin_settings_action',
        security: deciAdminData.security
    };

    $('#hide-admin').on('click', function (event) {

        // Sends ajax request to save the plugin settings
        $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',

            data: data,

            success: function (response) {
                alert(response);
            },
            error: function (response) {
                alert(response);
            },

        });


    });

});
