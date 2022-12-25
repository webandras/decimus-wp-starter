jQuery(document).ready(function ($) {
    // Send AJAX request
    if ($("#decimus-team-member-table").length > 0) {
        // noinspection JSUnresolvedVariable
        var data = {
            action: "decimus_team_member_action",
            security: DecimusTeamMemberData.security,
            args: {
                'type': 'table',
                'name': 1,
                'first_name_first': 0,
                'photo': 1,
                'phone': 0,
                'email': 1,
                'position': 1,
                'department': 0,
                'works_since': 0
            }
        };

        // noinspection JSUnresolvedVariable
        $.ajax({
            type: "POST",
            url: DecimusTeamMemberData.ajaxurl,
            data: data,
        })
            .done(function ($response) {
                console.log("Decimus Team Member AJAX - OK response.");
                $("#decimus-team-member-table").html($response.data);
            })
            .fail(function () {
                console.log("Decimus Team Member AJAX response error.");
                $("#decimus-team-member-table").html(
                    "Decimus Team Member AJAX response error."
                );
            })
            .always(function () {
                console.log("Decimus Team Member AJAX finished.");
            });
    }
});
