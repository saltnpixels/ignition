//This file takes care of menus and navigation at the top
let iconAngleRight = "<span class='arrow'></span>";

//if using wordpress, icons may have already been localized. use those. otherwise add it
if (typeof icons !== 'undefined' && typeof icons.angleRight !== 'undefined') {
    iconAngleRight = icons.angleRight;
}

let screenReaderText = typeof screenReaderText == 'undefined' ? {} : screenReaderText;
if (screenReaderText == '') {
    screenReaderText.collapse = 'Collapse child menu';
    screenReaderText.expand = 'Expand child menu';
}

let submenuButtons = '';

//after page loads run
jQuery(function ($) {

    const navigation = $('.site-top'),
        menus = $('.menu'),
        menuToggle = $('.panel-left-toggle'),
        page = $('#page'),
        body = $('body');


    submenuButtons = function () {
        // Add dropdown arrow toggle button to all submenus.
        var dropdownToggle = $('<button />', {
            'class': 'submenu-dropdown-toggle',
            'aria-expanded': false
        })
            .append(iconAngleRight)
            .append($('<span />', {
                'class': 'screen-reader-text',
                text: screenReaderText.expand
            }));

        //ADDING THE BUTTON TO PAGE SUBMENU ITEMS
        //I CANT FIND A PAGE WALKER HOOK TO DO IT
        menus.find('.page_item:not(.menu-item) a').wrap('<div class="menu-item-link"></div>');
        menus.find('.page_item_has_children .menu-item-link a').after(dropdownToggle);


        // Set the active submenu to be toggled on on mobile or not horizontal menus
        let currentSubmenus = menus.find('.current-menu-item > .sub-menu, .current_page_item > .sub-menu, .current_page_ancestor > .sub-menu, .current-menu-ancestor > .sub-menu');

    currentSubmenus.each( function() {
	    if ($(this).css('display') === 'none' || $(this).parents('#panel-left, #panel-right').length) { //submenus are set to display none only in vertical menus which is what we want
		    //add toggled on to the li and the button
		    $(this).find('.current-menu-ancestor > .menu-item-link button, .current-menu-parent, .current-menu-parent button, .current_page_ancestor > button, .current_page_parent, .current-menu-item button')
          .trigger('click');

	    }
    });


    //special after toggle event
      let dropdownButtons = document.querySelectorAll('.submenu-dropdown-toggle');
      for (const dropdownButton of dropdownButtons){
          dropdownButton.addEventListener('afterToggle', e => {

              //toggle the li. closest still best support with jquery
	          $(dropdownButton).closest('li').toggleClass('toggled-on');
	          //toggle the sub menu
	          let submenus = $(dropdownButton).closest('li').find('> .children, > .sub-menu');
	            submenus.toggleClass('toggled-on').slideToggle();

	            let screenReaderSpan = $(dropdownButton).find('.screen-reader-text');
	          screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
          });
      }

    };

    submenuButtons();


    //NAVIGATION TOGGLE
    if (!menuToggle.length) {
        return;
    }

    //move button into site-top if app-menu type so the button shrinks too
    if (body.hasClass('app-menu')) {
        navigation.append(menuToggle);
    }

    menuToggle[0].addEventListener('afterToggle', e =>{
	    //if button is set to open
	    if (menuToggle.hasClass('toggled-on')) {

	        //if its an app-menu, add the menu-lock onto body
		    if (body.hasClass('app-menu')) {
			    body.addClass('menu-lock');
		    }

		    //clicking anywhere outside the menu will close it
		    $('.site-content').one('click.Menu', function () {
			    e.preventDefault();
			    menuToggle.trigger('click'); //recursively calls click and closes
		    });
	    } else {

		    $('.site-content').off('click.Menu');

		    page.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
			    body.removeClass('menu-lock'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
		    });
	    }

    });




    //move logo in middle of menu on desktop if logo is middle position
    if ($('.logo-in-middle').length ) {
    let navigationLi = $('.site-navigation__nav-holder .menu li');
        let middle = Math.floor($(navigationLi).length / 2) - 1;

        //add logo tot he middle when page loads
        $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
        $('.site-logo').clone().appendTo('.li-logo-holder');
    }


    $('#btnCloseUpdateBrowser').on('click', () => {
        $('#outdated').hide()
    });


});
