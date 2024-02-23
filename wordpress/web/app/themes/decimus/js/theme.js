/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
1.  Header
2.  Theme
--------------------------------------------------------------*/

/*--------------------------------------------------------------
1. Header
--------------------------------------------------------------*/

jQuery(document).ready(function ($) {
    // Hide offcanvas menu in navbar and enable body scroll on resize through the breakpoints
    $(window).on("resize", function () {
        $(".navbar .offcanvas").offcanvas("hide");
    });

    // Close offcanvas on click a, keep .dropdown-menu open
    $(
        ".offcanvas a:not(.dropdown-toggle):not(a.remove_from_cart_button), a.dropdown-item"
    ).on("click", function () {
        $(".offcanvas").offcanvas("hide");
    });

    // Dropdown menu animation
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $(".dropdown").on("show.bs.dropdown", function () {
        $(this).find(".dropdown-menu").first().stop(true, true).slideDown();
    });

    // Add slideUp animation to Bootstrap dropdown when collapsing.
    $(".dropdown").on("hide.bs.dropdown", function () {
        $(this).find(".dropdown-menu").first().stop(true, true).hide();
    });

    // Search collapse button hide if empty
    if ($("#collapse-search .searchform").length !== 1) {
        $(".top-nav-search-md, .top-nav-search-lg").remove();
    }

    // Searchform focus
    $("#collapse-search").on("shown.bs.collapse", function () {
        $(".top-nav-search .searchform .form-control").focus();
    });
}); // jQuery End

/*--------------------------------------------------------------
2. Theme
--------------------------------------------------------------*/

jQuery(document).ready(function ($) {
    // Smooth Scroll. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
    $(function () {
        $(
            'a[href*="#"]:not([href="#"]):not(a.comment-reply-link):not([href="#tab-reviews"]):not([href="#tab-additional_information"]):not([href="#tab-description"]):not([href="#reviews"]):not([href="#tab-video_tab"]):not([href="#carouselExampleIndicators"]):not([data-smoothscroll="false"])'
        ).click(function () {
            if (
                location.pathname.replace(/^\//, "") ==
                this.pathname.replace(/^\//, "") &&
                location.hostname == this.hostname
            ) {
                var target = $(this.hash);
                target = target.length
                    ? target
                    : $("[name=" + this.hash.slice(1) + "]");
                if (target.length) {
                    $("html, body").animate(
                        {
                            // Change your offset according to your navbar height
                            scrollTop: target.offset().top - 55,
                        },
                        1000
                    );
                    return !1;
                }
            }
        });
    });

    // Scroll to ID from external url. Will be removed when Safari supports scroll-behaviour: smooth (Bootstrap 5).
    if (window.location.hash) scroll(0, 0);
    setTimeout(function () {
        scroll(0, 0);
    }, 1);
    $(function () {
        $(".scroll").on("click", function (e) {
            e.preventDefault();
            $("html, body").animate(
                {
                    // Change your offset according to your navbar height
                    scrollTop: $($(this).attr("href")).offset().top - 55,
                },
                1000,
                "swing"
            );
        });
        if (window.location.hash) {
            $("html, body").animate(
                {
                    // Change your offset according to your navbar height
                    scrollTop: $(window.location.hash).offset().top - 55,
                },
                1000,
                "swing"
            );
        }
    });

    // Scroll to top Button
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 500) {
            $(".top-button").addClass("visible");
        } else {
            $(".top-button").removeClass("visible");
        }
    });

    // div height, add class to your content
    $(".height-50").css("height", 0.5 * $(window).height());
    $(".height-75").css("height", 0.75 * $(window).height());
    $(".height-85").css("height", 0.85 * $(window).height());
    $(".height-100").css("height", 1.0 * $(window).height());

    // Forms
    $("select, #billing_state").addClass("form-select");

    // Alert links
    $(".alert a").addClass("alert-link");
}); // jQuery End

/*
* Decimus popup modal when adding item to the cart
* */
jQuery(document).ready(function ($) {
    var modalElem = document.getElementById("registerToEvent");
    if (modalElem) {
        // instantiate registration form modal
        var registrationModal = new bootstrap.Modal(
            modalElem,
            {keyboard: false}
        );

        // Used to fill up registration form fields automatically
        function updateEventsDataForModal() {
            var eventName = $("#offcanvas-cart .item-name > strong > a").text().trim();
            var eventDetails = $(
                "#offcanvas-cart .item-name .item-quantity > .quantity"
            ).text();

            // product variation description that serves as a custom text to be included in the registration emails
            var eventNotice = $('.single_variation_wrap > .woocommerce-variation.single_variation > .woocommerce-variation-description > p');
            var eventNoticeText = '';
            if (eventNotice) {
                eventNoticeText = eventNotice.text().trim();
            }


            $('form.wpcf7-form input[name="event-name"]').val(eventName);
            $('form.wpcf7-form input[name="event-details"]').val(eventDetails);

            // only overwrite event-notice field value if a variation description exists / for variable products only!
            if (eventNoticeText !== '') {
                $('form.wpcf7-form input[name="event-notice"]').val(eventNoticeText);
            }
        }

        // Show modal on click, fill fields with a delay
        $("#register-form-button").on("click", function () {
            setTimeout(updateEventsDataForModal, 4000);
            registrationModal.show();
        });


        /* Insert values from badges to form field values (see CF7 forms) */
        var appointmentField = $("#registerToEvent input[name='your-appointment']");

        function addBadgeValueToAppointmentField(val) {
            appointmentField.val(val)
        }

        // for each badge
        $("#registerToEvent .your-appointment-selection .badge").each(function () {
            $(this).on('click', function () {
                addBadgeValueToAppointmentField($(this).text());
            });
        });

        /* Insert values from badges to form filed values (see CF7 forms) */
        var dayField = $("#registerToEvent input[name='your-day']");

        function addBadgeValueToDayField(val) {
            dayField.val(val)
        }

        // for each badge
        $("#registerToEvent .your-day-selection .badge").each(function () {
            $(this).on('click', function () {
                addBadgeValueToDayField($(this).text());
            });
        });

    }

}); // jQuery End
