//turn icons into svg if using the icons that come with theme folder
const swapIconToSvg = function(){
    $('.svg-icon').each( function(index) {

        let iconClass = $(this).prop("class").replace("svg-icon", "").trim();

        $(this).replaceWith('<svg role="img" class="icon ' + iconClass + '"><use href="#' + iconClass + '" xlink:href="#' + iconClass + '"></use></svg>');
    });
};


jQuery(function($){ 
    swapIconToSvg();
 });
 