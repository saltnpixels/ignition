//This file takes care of menus and navigation at the top
let iconAngleRight = '<span class=\'arrow\'></span>';

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
        // Add dropdown arrow toggle button to all submenus. Only needed for pages because there is no walker
        var dropdownToggle = $('<button />', {
            'class': 'submenu-dropdown-toggle',
            'aria-expanded': false
        })
            .append(iconAngleRight)
            .append($('<span />', {
                'class': 'screen-reader-text',
                text: screenReaderText.expand
            }));

        //ADDING THE BUTTON TO PAGE SUBMENU ITEMS. Regular sub menu items have a walker that adds the button
        //I CANT FIND A PAGE WALKER HOOK TO DO IT
        menus.find('.page_item:not(.menu-item) a').wrap('<div class="menu-item-link" tabindex="0"></div>');
        menus.find('.page_item_has_children .menu-item-link a').after(dropdownToggle);

        // Set the active submenu to be toggled on on mobile or not horizontal menus
        let currentSubmenus = menus.find('.current-menu-item > .sub-menu, .current_page_item > .sub-menu, .current_page_ancestor > .sub-menu, .current-menu-ancestor > .sub-menu');

        currentSubmenus.each(function () {
            if ($(this).css('display') === 'none' || $(this).parents('#panel-left, #panel-right').length) { //submenus are set to display none only in vertical menus which is what we want
                //if this menu is inside a vertical menu, add toggled on
                $(this).find('.current-menu-ancestor > .menu-item-link button, .current-menu-parent, .current-menu-parent button, .current_page_ancestor > button, .current_page_parent, .current-menu-item button')
                    .trigger('click');

            }
        });


        //special after toggle event for menu dropdowns
        let dropdownButtons = document.querySelectorAll('.submenu-dropdown-toggle');
        for (const dropdownButton of dropdownButtons) {
            dropdownButton.addEventListener('afterToggle', e => {

                //toggle the li. closest still best support with jquery
                $(dropdownButton).closest('li').toggleClass('toggled-on');
                //toggle the sub menu
                let submenus = $(dropdownButton).closest('li').find('> .children, > .sub-menu');

                //check if submenu is offscreen on desktop
                fixOffScreenMenu(submenus);
                submenus.toggleClass('toggled-on');

                //desktop
                if(submenus.hasClass('toggled-on')){
                    submenus.slideDown();
                }else{
                    submenus.slideUp();
                }


                let screenReaderSpan = $(dropdownButton).find('.screen-reader-text');
                screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
            });
        }

        //allow for tabbing the anchors and setting focus on the menu item link. focus-within might be able to replace
        let menuItems = document.querySelectorAll('.menu-item-link a');
        for (const menuItemLink of menuItems) {
            menuItemLink.addEventListener('focus', e => {
                //if(e.code == '9' && menuItemLink)

                console.log(document.activeElement);
                menuItemLink.parentElement.classList.add('focus'); //add focus to menu-item-link for styling
                //if this element has a dropdown near it, toggle it now
                if (menuItemLink.nextElementSibling !== null) {
                    menuItemLink.nextElementSibling.click(); //click the button to open the sub-menu
                }

            });

            menuItemLink.addEventListener('blur', e => {
                menuItemLink.parentElement.classList.remove('focus');
                const subMenu = menuItemLink.closest('.sub-menu');

                //if it is inside a submenu
                if (subMenu !== null) {

                    const subMenuParent = menuItemLink.closest('.sub-menu').closest('li.menu-item');
                    const parentMenu = menuItemLink.closest('li.menu-item');
                    //if it is the last element in the sub-menu and it has no children, close the sub-menu
                    if (parentMenu.nextElementSibling == null && menuItemLink.nextElementSibling == null) {
                        //close parent li
                        subMenuParent.querySelector('.submenu-dropdown-toggle').click();
                        //if parent li is the last of its sub-menu close that one
                        if (subMenuParent.nextElementSibling == null) {
                            subMenuParent.parentElement.closest('li.menu-item').querySelector('.submenu-dropdown-toggle').click();
                        }
                    }

                }
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

    menuToggle[0].addEventListener('afterToggle', e => {
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
    if ($('.logo-in-middle').length) {
        let navigationLi = $('.site-navigation__nav-holder .menu li');
        let middle = Math.floor($(navigationLi).length / 2) - 1;

        //add logo to the middle when page loads
        $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
        $('.site-logo').clone().appendTo('.li-logo-holder');
    }

    $('#btnCloseUpdateBrowser').on('click', () => {
        $('#outdated').hide();
    });


    //move submenus if too close to edge on desktop
    function fixOffScreenMenu(menu) {

        let edge = menu[0].getBoundingClientRect().right;
        let viewport = document.documentElement.clientWidth;

        //if the submenu is off the page, pull it back somewhat
        if (edge > viewport) {
            menu[0].style.left = '30px';
        }
    }


});
