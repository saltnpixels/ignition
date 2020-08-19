import {ignSlideDown, ignSlidePropertyReset, debounce, throttle} from "./setup"

/*--------------------------------------------------------------
# Adding some global events and functions users can use via data attributes
--------------------------------------------------------------*/

/**
 * resize menu buttons on load. also runs on resize.
 * menu button is not inside site-top for various reasons (we dont want x to be inside or when menu opens the ex is uinderneath.
 * so we use this function to match the site -top height and center it as if it was inside
 */

let menuButtons = '';

function placeMenuButtons() {
	let $siteTopHeight = document.querySelector('.site-top');

	if($siteTopHeight != null){
		$siteTopHeight = $siteTopHeight.clientHeight;
	}

	// let adminbar = document.querySelector('#wpadminbar');
	// let adminbarHeight = 0;
	//
	// if (adminbar !== null) {
	// 	adminbarHeight = adminbar.clientHeight;
	// }

	if (menuButtons.length) {
		menuButtons.forEach(button => {
			button.style.height = $siteTopHeight + 'px';
		});
	}
}

/*--------------------------------------------------------------
# IGN Events
--------------------------------------------------------------*/

document.addEventListener('DOMContentLoaded', function () {

	/*------- Add touch classes or not --------*/
	if (!("ontouchstart" in document.documentElement)) {
		document.documentElement.className += " no-touch-device";
	} else {
		document.documentElement.className += " touch-device";
	}

	/*------- menu buttons --------*/
	//if the menu button is outside site-top. get both buttons for centering both.
	if (!document.querySelector('.app-menu')) {
		menuButtons = document.querySelectorAll('.panel-left-toggle, .panel-right-toggle');
	} else {
		//otherwise the menu button does not need to be centered because its part of the app menu and moves. (moved in navigation.js)
		menuButtons = document.querySelectorAll('.panel-right-toggle');
	}
	//we run menu button function below in resize event


	/*------- Toggle Buttons --------*/

	//trigger optional afterToggle event
	//adding new custom event for after the element is toggled
	let toggleEvent = null;
	if (isIE11) {
		toggleEvent = document.createEvent('Event');

		// Define that the event name is 'build'.
		toggleEvent.initEvent('afterToggle', true, true);

	} else {
		toggleEvent = new Event('afterToggle', {bubbles: true}); //bubble allows for delegation on body
	}


	//add aria to buttons currently on page
	let buttons = document.querySelectorAll('[data-toggle]');
	buttons.forEach(button => {
		button.setAttribute('role', 'switch');
		button.setAttribute('aria-checked', button.classList.contains('toggled-on') ? 'true' : 'false');

	});


	//toggling the buttons with delegation click
	document.body.addEventListener('click', e => {

		let item = e.target.closest('[data-toggle]');

		if (item) {

			let $doDefault = item.getAttribute('data-default');
			//normally we prevent default unless someone add data-default
			if (null === $doDefault) {
				e.preventDefault();
				e.stopPropagation();
			}

			//if data-radio is found, only one can be selected at a time.
			// untoggles any other item with same radio value
			//radio items cannot be untoggled until another item is clicked
			let radioSelector = item.getAttribute('data-radio');


			if (radioSelector !== null) {
				let radioSelectors = document.querySelectorAll(`[data-radio="${radioSelector}"]`);

				radioSelectors.forEach(radioItem => {
					if (radioItem !== item && radioItem.classList.contains('toggled-on')) {
						toggleItem(radioItem); //toggle all other radio items off when this one is being turned on
					}
				});
			}

			//if item has data-switch it can only be turned on or off but not both by this button based on value of data-switch (its either on or off)
			let switchItem = item.getAttribute('data-switch');

			//finally toggle the clicked item. some types of items cannot be untoggled like radio or an on switch
			if (radioSelector !== null) {
				toggleItem(item, 'on'); //the item clicked on cannot be unclicked until another item is pressed
			} else if (switchItem !== null) {
				if (switchItem === 'on') {
					toggleItem(item, 'on');
				} else {
					toggleItem(item, 'off');
				}
			} else {
				toggleItem(item); //normal regular toggle can turn itself on or off
			}

		} //end if item found
	});

	//actual toggle of an item and add class toggled-on and any other classes needed. Also do a slide if necessary
	function toggleItem(item, forcedState = 'none') {

		//toggle item
		if (forcedState === 'on') {
			item.classList.add('toggled-on'); //radio or data-switch of on will always toggle-on
		} else if (forcedState === 'off') {
			item.classList.remove('toggled-on'); //data-switch of off will always toggle off
		} else {
			item.classList.toggle('toggled-on'); //basic data toggle item
		}

		//is item toggled? used for the rest of this function to toggle another target if needed.
		let isToggled = item.classList.contains('toggled-on');

		item.setAttribute('aria-expanded', isToggled ? 'true' : 'false');

		//get class to add to this item or another
		let $class = item.getAttribute('data-toggle'),
			$target = document.querySelectorAll(item.getAttribute('data-target'));

		if ($class === null || !$class) {
			$class = 'toggled-on'; //default class added is toggled-on
		}
		//special class added to another item
		if ($target.length) {
			$target.forEach(targetItem => {
				if (isToggled) {
					targetItem.classList.add($class);
				} else {
					targetItem.classList.remove($class);
				}

				targetItem.setAttribute('aria-expanded', isToggled ? 'true' : 'false');

				//data slide open or closed
				if (targetItem.dataset.slide !== undefined) {

					let slideTime = (targetItem.dataset.slide) ? parseFloat(targetItem.dataset.slide) : .5;

					if (isToggled) {
						ignSlideDown(targetItem, slideTime);
					} else {
						ignSlideUp(targetItem, slideTime);
					}
				}

				//allow event to happen after click for the targeted item
				targetItem.dispatchEvent(toggleEvent);
			});
		} else { //applies class to the clicked item, there is no target
			if ($class !== 'toggled-on') { //add class to clicked item if its not set to be toggled-on
				if (isToggled) {
					item.classList.toggle($class);
				} else {
					item.classList.remove($class);
				}
			}
		}

		//trigger optional afterToggle event. continue the click event for customized stuff
		item.dispatchEvent(toggleEvent);

	}


	/*------- Moving items Event as well as all resizing --------*/
	//on Window resize we can move items to and from divs with data-moveto="the destination"
	//it will move there when the site reaches smaller than a size defaulted to 1030 or set that with data-moveat
	//the whole div, including the data att moveto moves back and forth
	let movedId = 0;
   const moveEvent = new Event('afterResize', {bubbles: true}); //bubble allows for delegation on body
	function moveItems() {


		let windowWidth = window.innerWidth;
		let $moveItems = document.querySelectorAll('[data-moveto]');

		$moveItems.forEach(item => {
			let moveAt = item.getAttribute('data-moveat'),
				destination = document.querySelector(item.getAttribute('data-moveto')),
				source = item.getAttribute('data-movefrom');

			moveAt = moveAt ? moveAt : 1030;

			if (moveAt.startsWith('--')) {
				if (isIE11) {
					moveAt = 1030;
				} else {
					let cssVars = getComputedStyle(document.body); //get css variables
					moveAt = parseInt(cssVars.getPropertyValue(moveAt), 10);
				}
			}


			if (!destination) {
				return;
			}

			//if no data movefrom is found add one to parent so we can move items back in. now they go back and forth
			if (!source) {
				let sourceElem = item.parentElement.id;

				//if parent has no id attr, add one with a number so its unique
				if (!sourceElem) {
					item.parentElement.setAttribute('id', 'move-' + movedId);
					movedId++;
					sourceElem = item.parentElement.id;
				}

				item.setAttribute('data-movefrom', '#' + sourceElem);
			}

			source = document.querySelector(item.getAttribute('data-movefrom'));

			//if the screen is smaller than moveAt (1030), move to destination
			if (windowWidth < moveAt || moveAt == 0) {
				//no need to move if its already there...
				if (!destination.contains(item)) {
					if (item.hasAttribute('data-moveto-pos')) {
						destination.insertBefore(item, destination.children[item.getAttribute('data-moveto-pos')]);
					} else {
						destination.appendChild(item);
					}
				}
			} else {
				if (!source.contains(item)) {
					if (item.hasAttribute('data-movefrom-pos')) {
						source.insertBefore(item, source.children[item.getAttribute('data-movefrom-pos')]);
					} else {
						source.appendChild(item);
					}
				}
			}

			//show it
			item.classList.add('visible');
		});

		placeMenuButtons(); //running the moving of menu buttons here. nothing to do with moving items.

		//fix height of fixed holder fixed at top items
		document.querySelectorAll('.fixed-holder').forEach(fixed=>{
			fixed.style.height = fixed.firstElementChild.clientHeight + 'px';
		});

		document.dispatchEvent(moveEvent)

	}

	window.addEventListener('resize', throttle(moveItems, 400));
	moveItems();




	document.documentElement.classList.remove('dom-loading');


	//add finished loading ignition events
	let EventFinished = null;
	if (isIE11) {
		EventFinished = document.createEvent('Event');

		// Define that the event name is 'build'.
		EventFinished.initEvent('afterIgnEvents', true, true);

	} else {
		EventFinished = new Event('afterIgnEvents');
	}
	document.dispatchEvent(EventFinished);
});


/*------- Function for hi red background image swap --------*/

//check if device is retina
function isHighDensity() {
	return ((window.matchMedia && (window.matchMedia('(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)').matches)));
}

//check if file exists on server before using
function fileExists(image_url) {
	let http = new XMLHttpRequest();
	http.open('HEAD', image_url, true);
	http.send();
	return http.status != 404;
}


//Add inline retina image if found and on retina device. To use add data-high-res to an inline element with a background-image
if (isHighDensity()) {

	let retinaImage = document.querySelectorAll('[data-high-res]');
	retinaImage.forEach(item => {
		let image2x = '';
		//if a high res is provided use that, else use background image but add 2x at end.
		if (item.dataset.highRes) {
			image2x = item.dataset.highRes;
		} else {
			//get url for original image
			let image = item.style.backgroundImage.slice(4, -1).replace(/"/g, "");
			//add @2x to it if image exists.
			image2x = image.replace(/(\.[^.]+$)/, '@2x$1');
		}

		if (fileExists(image2x)) {
			item.style.backgroundImage = 'url("' + image2x + '")';
		}

	});
}


