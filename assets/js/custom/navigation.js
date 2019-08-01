
/*------- move submenus if too close to edge on desktop --------*/
function fixOffScreenMenu (menu) {

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
				//add class toggled-on to li. cant do it via data-target cause menu might be showing twice on page
				evt.target.closest('li').classList.add('toggled-on');
			} else {
				evt.target.closest('li').classList.remove('toggled-on');
			}

			ign_slide_element(subMenu, .5, isToggled);
		}
	});

	/*------- Tabbing through the menu for ADA compliance --------*/

	let lastTabbedItem = '';

	//focus
	document.body.addEventListener('focusin', e => {
		console.log(e.target);
		if (e.target.closest('.menu-item-link a')) {
			let menuItemLink = e.target.closest('.menu-item-link a');

			window.addEventListener('keyup', function (e) {
				let code = (e.keyCode ? e.keyCode : e.which);
				//tab or shift tab
				if (code === 9 || code === 16) {
					menuItemLink.parentElement.classList.add('focus'); //add focus to .menu-item-link
					//if this element has a dropdown near it, toggle it now
					if (menuItemLink.nextElementSibling !== null && !menuItemLink.closest('li').classList.contains('toggled-on')) {
						menuItemLink.nextElementSibling.click(); //click the button to open the sub-menu
					}

					//if there is an item focused before
					if (lastTabbedItem) {
						//check if last item had a sub menu and we are not inside it now
						if (lastTabbedItem.nextElementSibling !== null && !lastTabbedItem.closest('li').contains(menuItemLink)) {
							lastTabbedItem.nextElementSibling.click();
						}
					}

				}

			}, {once: true});
		}
	});

//blur
	document.body.addEventListener('focusout', e => {

		if (e.target.closest('.menu-item-link a')) {
			let menuItemLink = e.target.closest('.menu-item-link a');
			window.addEventListener('keyup', function (e) {
				let code = (e.keyCode ? e.keyCode : e.which);
				console.log(code);
				if (code === 9 || code === 16) {
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


				}
			}, {once: true});

		}
	});


	//app-menu ability for the top menu
	let body = document.body;
	let menuToggle = document.querySelector('.panel-left-toggle');
	let topNav = document.querySelector('.site-top');
	let page = document.querySelector('#page');

	//first move the button into site-top if app-menu is being used cause we dont want it on the outside
	if (body.classList.contains('app-menu')) {
		topNav.append(menuToggle);
	}


	function closeAppMenu(e){
		e.preventDefault();
		menuToggle.click();
	}


	//when button is opened we will lock the body so there is no scrolling and then open the page
	menuToggle.addEventListener('afterToggle', e => {
		//if button has been toggled on
		if (menuToggle.classList.contains('toggled-on')) {
				body.classList.add('body-lock');

			//clicking anywhere outside the menu will close it
			document.querySelector('.site-content').addEventListener('click', closeAppMenu, {once: true});

		} else {

			document.querySelector('.site-content').removeEventListener('click', closeAppMenu);

			if (body.classList.contains('app-menu')) {
				page.addEventListener('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
					body.classList.remove('body-lock'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
				}, {once: true});
			}else{
				body.classList.remove('body-lock');
			}
		}

	});

}); //end ready


jQuery(function ($) {

	//move logo in middle of menu on desktop if logo is middle position
	if ($('.logo-in-middle').length) {
		let navigationLi = $('.site-navigation__nav-holder .menu li');
		let middle = Math.floor($(navigationLi).length / 2) - 1;

		//add logo to the middle when page loads
		$('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
		$('.site-logo').clone().appendTo('.li-logo-holder');
	}

});
