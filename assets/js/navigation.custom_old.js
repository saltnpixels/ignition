//when navigation should go mobile
let navBreakpoint = 800;

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


//after page loads run
jQuery(function ($) {

    const navigation = $('.site-navigation'),
        menus = $('.menu'),
        menuToggle = $('.panel-left-toggle'),
        navHolder = $('.site-navigation__nav-holder'), //excludes logo or anything you dont want mobile
        page = $('.site-container'),
        body = $('body'),
        html = $('html');


    function addSubMenuToggles() {
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

        //ADDING THE BUTTON TO SUBMENU ITEMS
        menus.find('.menu-item-has-children > a, .page_item_has_children > a').after(dropdownToggle);

        // Set the active submenu initial state.
        menus.find('.current-menu-ancestor > button, .current-menu-parent, .current_page_ancestor > button, .current_page_parent')
            .addClass('toggled-on')
            .attr('aria-expanded', 'true')
            .find('.screen-reader-text')
            .text(screenReaderText.collapse);

        menus.find('.current-menu-ancestor > .sub-menu, .current_page_ancestor > .children').addClass('toggled-on').slideDown(); //added slidedown


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
    }

    addSubMenuToggles();


    

    //MENU TOGGLE
    (function () {

        // Return early if menuToggle is missing.
        if (!menuToggle.length) {
            return;
        }

        if (navigation.hasClass('site-navigation--app-menu')) {
            body.addClass('app-menu');
        }

        //MENU CLICK EVENT
        menuToggle.on('click', function (e) {

            //toggle on button and nav holder
            navHolder.toggleClass('toggled-on');
            menuToggle.attr('aria-expanded', navHolder.hasClass('toggled-on')).toggleClass('toggled-on');

            //added so we can fix the whole menu system if needed
            $('.site-top').toggleClass('toggled-on');
            $('html, body').toggleClass('menu-lock'); //lock page in place

            //if app-menu takes over to make sure things are done in transition order
            if (navigation.hasClass('site-navigation--app-menu')) {
                toggleCoolMenu();
                e.stopPropagation();
            }
            //else regular menu. check if open or closed
            else {
                //regular menu, close menu if they click on the page
                if (menuToggle.hasClass('toggled-on')) {

                    //clicking outside the menu will close it
                    $('.site-content').one('click', function () {
                        menuToggle.trigger('click'); //recursively calls click and closes
                    });
                } else {
                    //menuToggle.css('height', '100%');
                    $('.site-content').off();
                }
            }
        });
    })();

    /*------- App Like Menu --------*/
    //runs on click
    //app-menu is moved outside of page on load
    function toggleCoolMenu() {

        if (menuToggle.hasClass('toggled-on')) {

            body.addClass('mobile-menu-open');
            navHolder.addClass('open'); //shows the menu removes hidden and at end it waits till transition finishes
            page.addClass('page-holder');
            page.one('click', function (e) {
                e.preventDefault();
                menuToggle.trigger('click'); //calls toggleCoolMenu again
            });
        }

        //closing menu and unwrapping pageholder
        if (!menuToggle.hasClass('toggled-on')) {
            body.removeClass('mobile-menu-open'); //closes menu by removing this class and putting page holder back in place.

            page.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
                navHolder.removeClass('open'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
                page.removeClass('page-holder'); //$('.page-holder').children().unwrap('.page-holder');
            });
        }
    }


    //this code runs on resize and load and moves the app menu into place. It also is responsible for a logo in middle of menu if thats as chosen in customizer
    const mainNavigationLi = $('.site-navigation ul.menu>li, .site-navigation .menu>ul>li');

    function resizeMobileMenu() {

        if (window.innerWidth < navBreakpoint) {

            //if the body doesn't have the mobile popout already, put it there if its the app-menu type.
            if (!$('body>.site-navigation__nav-holder').length && navigation.hasClass('site-navigation--app-menu')) {
                navHolder.prependTo(body);
            }

            //if logo was put in middle, take it out and put it like it would be a left logo on mobile.
            if ($('.li-logo-holder').length) {
                $('.site-logo').prependTo('.site-navigation');
                $('.li-logo-holder').remove();
            }
        }
        //else its desktop size
        else {
            //move menu holder back inside site-navigation
            if ($('body>.site-navigation__nav-holder').length) {
                navHolder.appendTo('.site-navigation');

                //close menu
                if (menuToggle.hasClass('toggled-on')) {
                    menuToggle.trigger('click');
                    page.off('click');
                }
            }


            //move logo in middle of menu on desktop if logo is middle position
            if ($('.logo-in-middle').length && window.innerWidth > navBreakpoint && !$('.li-logo-holder').length) {

                var middle = Math.floor($(mainNavigationLi).length / 2) - 1;
                $('<li class="menu-item li-logo-holder"></li>').insertAfter(mainNavigationLi.filter(':eq(' + middle + ')')).prepend($('.site-logo'));
                //check if custom logo is there and hide site title if it is
                if ($('.site-top .custom-logo-link').is(':visible')) {
                    $('.site-top .site-name').hide();
                }
            }

        }

    }

    $(window).on('resize', throttle(resizeMobileMenu, 200)); //run on load and resize
    resizeMobileMenu();


    //allow opening menus with tab and focus
    $('.site-navigation').find('a').on('focus blur', function (e) {
        var menuItems = $(this).parent('.menu-item, .page_item');


        //if focusing on an item inside a submenu
        if (e.type === 'blur' && menuItems.hasClass('menu-item-has-children')) {
            console.log('is a top level blur');
            return;
        }

        if (e.type === 'blur' && menuItems.parent('.sub-menu') && menuItems.is(':last-of-type')) {
            console.log('is a submenu item being blurred');
            menuItems.parents('.menu-item, .page_item').removeClass('focus');
            menuItems.removeClass('focus');
        }
        //return
        else {
            menuItems.toggleClass('focus');
        }


    });

    $('#btnCloseUpdateBrowser').on('click', () => {
        $('#outdated').hide()
    });


});
