//This file takes care of menus and navigation at the top
let iconAngleRight = '<span class="arrow"></span>';

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

/*------- move submenus if too close to edge on desktop --------*/
function fixOffScreenMenu(menu) {

	//make item visible so we can get left edge
	menu.style.display = 'block';
	menu.style.opacity = '0';
	let rightEdge = menu.getBoundingClientRect().right;
	let leftEdge = menu.getBoundingClientRect().right;
	//set menu back
	menu.style.display = '';
	menu.style.opacity = '';

	let viewport = document.documentElement.clientWidth;

	//if the submenu is off the page, pull it back somewhat
	if (rightEdge > viewport) {
		menu.style.left = '40px';
	}

	if (leftEdge < 0) {
		menu.style.left = '60%';
	}
}


document.addEventListener('DOMContentLoaded', function () {

	/*------- slide sub menus open and closed when a dropdown button is clicked --------*/
	document.body.addEventListener('afterToggle', evt => {
		//for every dropdown menu button, when clicked toggle the li parent and open the sub-menu with slide
		if (evt.target.closest('.submenu-dropdown-toggle')) {
			let menuItem = evt.target.closest('li');
			let isToggled = evt.target.classList.contains('toggled-on') ? 'open' : 'close';

			let subMenu = menuItem.querySelector('.sub-menu');

			if (isToggled === 'open') {
				fixOffScreenMenu(subMenu);
			}

			ign_slide_element(subMenu, .5, isToggled);
		}
	});


	/*------- Tabbing through the menu --------*/

	let menuItems = document.querySelectorAll('.menu-item-link a');
	let lastTabbedItem = '';
	menuItems.forEach(menuItemLink => {

		//focus
		menuItemLink.addEventListener('focus', e => {
			menuItemLink.parentElement.classList.add('focus'); //add focus to .menu-item-link
			//if this element has a dropdown near it, toggle it now
			if (menuItemLink.nextElementSibling !== null) {
				menuItemLink.nextElementSibling.click(); //click the button to open the sub-menu
			}

			//if there is an item focused before
			if (lastTabbedItem) {
				//check if last item had a sub menu and we are not inside it now
				if (lastTabbedItem.nextElementSibling !== null && !lastTabbedItem.closest('li').contains(menuItemLink)) {
					lastTabbedItem.nextElementSibling.click();
				}

			}

		});

		//blur
		menuItemLink.addEventListener('blur', e => {
			//blur current tabbed item, but dont close it if its a sub-menu
			menuItemLink.parentElement.classList.remove('focus');
			lastTabbedItem = menuItemLink;
			const subMenu = menuItemLink.closest('.sub-menu');


			//if we blurred an item in a sub-menu
			if (subMenu !== null) {
				console.log('blurred item inside sub-menu');
				const menuItem = menuItemLink.closest('.menu-item');
				//if its the last item in the submenu and it does not have a sub-menu itself
				if (menuItem.nextElementSibling == null && menuItem.querySelector('.sub-menu') == null) {
					menuItem.parentElement.closest('.menu-item').querySelector('.submenu-dropdown-toggle').click();
				}
			}


		});

	});

});

//fix wp_page_menu to be like wp_nav_menu
jQuery(function ($) {

	const navigation = $('.site-top'),
		menuToggle = $('.panel-left-toggle'),
		page = $('#page'),
		body = $('body');

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

});
