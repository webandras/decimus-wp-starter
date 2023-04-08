;jQuery(document).ready(function ($) {
    var fileInput = '';

    // Load media upload on click
    $('#tag-image').on('click', null,
        function () {
            fileInput = $('#tag-image');
            tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
            return false;
        });

    window.original_send_to_editor = window.send_to_editor;

    window.send_to_editor = function (html) {
        if (fileInput) {
            var fileurl = $('img', html).attr('src');
            if (!fileurl) {
                fileurl = $(html).attr('src');
            }
            $(fileInput).val(fileurl);

            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };

});
