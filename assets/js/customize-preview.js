/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

//not using in end but leaving here for now
function swapImgToSvg(selector) {
    var $img = $(selector),
        imgURL = $img.attr('src'),
        imgID = $img.attr('id');

    $.get(imgURL, function (data) {
        // Get the SVG tag, ignore the rest
        var $svg = $(data).find('svg');
        // Add replaced image's ID to the new SVG
        if (typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }

        $svg = $svg.removeAttr('xmlns:a');
        $img.replaceWith($svg);
    }, 'xml');

};
swapImgToSvg('.custom-logo-link img');


(function ($) {

    //check if custom logo is there and hide site title if it is
    if ($('.custom-logo-link').is(':visible')) {
        $('.site-name').hide();
    }


    wp.customize('custom_logo', function (value) {

        value.bind(function (to) {
            if (to != '') {
                $('.site-name').hide();
            }
            else {
                $('.site-name').show();
            }
        });
    });



    // Site title and description.
    wp.customize( 'blogname', function( value ) {

        // If logo isn't set then bind site-title for live update.
        if ( ! parent.wp.customize( 'custom_logo' )() ) {
            value.bind( function( to ) {
                $( '.site-title a' ).text( to );
            } );
        }
    } );

    wp.customize('blogdescription', function (value) {

        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });


    wp.customize('site_top_contained', function (value) {
        value.bind(function (to) {
            $('.site-top-container').removeClass('container');
            $('.site-top-container').removeClass('container-fluid');
            $('.site-top-container').addClass(to);
        });
    });


    // Switch logo side by adding class to site-top
    wp.customize('site_top_layout', function (value) {
        //console.log( value );
        value.bind(function (to) {

            $('.site-top').removeClass('no-logo logo-left logo-right logo-center logo-in-middle logo-center-under');
            $('.site-top').addClass(to);

            //if logo was min middle move it out on logo position change or move it in if it becomes middle
            if (to == 'logo-in-middle') {
                let navigationLi = $('.site-navigation__nav-holder .menu li');
                let middle = Math.floor($(navigationLi).length / 2) - 1;

                $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
                $('.site-logo').clone().appendTo('.li-logo-holder');
            }

            //if its not in the middle but it was. move it out
            if (to != 'logo-in-middle' && $('.li-logo-holder').length) {
                $('.li-logo-holder').remove();
            }

        });
    });


//todo remove?
    // Whether a header image is available.
    function hasHeaderImage() {
        var image = wp.customize('header_image')();
        return '' !== image && 'remove-header' !== image;
    }

    // Whether a header video is available.
    function hasHeaderVideo() {
        var externalVideo = wp.customize('external_header_video')(),
            video = wp.customize('header_video')();

        return '' !== externalVideo || ( 0 !== video && '' !== video );
    }

    // Toggle a body class if a custom header exists.
    $.each(['external_header_video', 'header_image', 'header_video'], function (index, settingId) {
        wp.customize(settingId, function (setting) {
            setting.bind(function () {
                if (hasHeaderImage()) {
                    $(document.body).addClass('has-header-image');
                } else {
                    $(document.body).removeClass('has-header-image');
                }

                if (!hasHeaderVideo()) {
                    $(document.body).removeClass('has-header-video');
                }
            });
        });
    });

})(jQuery);
