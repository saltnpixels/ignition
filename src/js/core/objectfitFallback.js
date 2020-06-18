jQuery(function($) {
    'use strict';

    // the css selector for the container that the image should be attached to as a background-image
    var imgContainer = '.background-image, .cover-image';

    function getCurrentSrc(element, cb)
    {
        var getSrc;
        if (!window.HTMLPictureElement) {
            if (window.respimage) {
                respimage({
                    elements : [element]
                });
            }
            else if (window.picturefill) {
                picturefill({
                    elements : [element]
                });
            }
            cb(element.src);
            return;
        }

        getSrc = function()
        {
            element.removeEventListener('load', getSrc);
            element.removeEventListener('error', getSrc);
            cb(element.currentSrc);
        };

        element.addEventListener('load', getSrc);
        element.addEventListener('error', getSrc);
        if (element.complete) {
            getSrc();
        }
    }

    function setBgImage() {
        $(imgContainer).each(function()
        {
            var $this = $(this), img = $this.find('img').get(0);

            getCurrentSrc(img, function(elementSource)
            {
                $this.css('background-image', 'url(' + elementSource + ')');
            });
        });
    }

    if ('objectFit' in document.documentElement.style === false) {

        $('html').addClass('no-objectfit');
        $(window).resize(function()
        {
            setBgImage();
        });

        setBgImage();
    }

});