jQuery(function($){

    //wrap all youtube videos so they can be responsive.
    $('iframe[src*="youtube.com"], iframe[data-src*="youtube.com"]').each(
        function(){
            $(this).wrap('<div class="videowrapper"></div>');
        }
    )
});