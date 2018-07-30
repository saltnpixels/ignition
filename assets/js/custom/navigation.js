//when navigation should go mobile
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
        // Add dropdown arrow toggle button to submenus.
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
        let currentSubmenus = menus.find('.current-menu-ancestor > .sub-menu, .current-menu-parent > .sub-menu, .current_page_ancestor > .children, .current-menu-item > .sub-menu');
        console.log(currentSubmenus);
        if (currentSubmenus.css('display') === 'none' || currentSubmenus.find('#panel-left, #panel-right')) { //submenus are set to display none only in vertical menus which is what we want
            //add toggled on to the li and the button
            menus.find('.current-menu-ancestor > .menu-item-link button, .current-menu-parent, .current-menu-parent button, .current_page_ancestor > button, .current_page_parent, .current-menu-item button')
                .addClass('toggled-on')
                .attr('aria-expanded', 'true')
                .find('.screen-reader-text')
                .text(screenReaderText.collapse);

            currentSubmenus.addClass('toggled-on').slideDown();
        }


        //SUBMENU CLICK EVENT
        $('body').on('click', '.submenu-dropdown-toggle', function (e) {

            var _this = $(this),
                screenReaderSpan = _this.find('.screen-reader-text'),
                submenus = _this.closest('li').find('> .children, > .sub-menu');

            e.preventDefault();

            _this.toggleClass('toggled-on');
            //toggle li parent
            _this.closest('li').toggleClass('toggled-on');
            //toggle actual .sub-menu
            submenus.toggleClass('toggled-on').slideToggle();
            _this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');

            screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
    };

    submenuButtons();


    //NAVIGATION TOGGLE
    if (!menuToggle.length) {
        return;
    }

    //move button into site-top
    if (body.hasClass('app-menu')) {
        navigation.append(menuToggle);
    }


    menuToggle.on('click', function (e) {

        body.toggleClass('menu-open');

        menuToggle.attr('aria-expanded', body.hasClass('menu-open')).toggleClass('toggled-on');

        //if its open
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
            console.log('its closing');
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

        $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
        $('.site-logo').clone().appendTo('.li-logo-holder');
    }


    $('#btnCloseUpdateBrowser').on('click', () => {
        $('#outdated').hide()
    });


});
