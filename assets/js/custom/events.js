/*--------------------------------------------------------------
# Adding some global events users can use via data attributes
--------------------------------------------------------------*/

//test if this is a touchscreen add class
if (!("ontouchstart" in document.documentElement)) {
	document.documentElement.className += " no-touch-device";
}else{
	document.documentElement.className += " touch-device";
}

let scrollMagicController = '';
//setup scroller function
/**
 * element can have these data attributes:
 * data-scrollanimation = a class to add to this element on scroll
 * data-scrolltrigger = the element that triggers the scene to start
 * data-scrollhook = onEnter, onLeave, default is center
 * data-scrolloffset = offset from scrollhook on trigger element
 * data-scrollduration = how long it should last. if not set, 0  is used and that means it doesnt reset until you scroll up.
 * data-scrollscrub = tweens between two classes as you scroll. tween expects a duration, else duration will be 100
 *
 */
function runScrollerAttributes (element) {
	//this function can be run on an alement even after load and they will be added to scrollMagicController
	//scrollmagic must be loaded
	if ('undefined' != typeof ScrollMagic && element.hasAttribute('data-scrollanimation')) {

		//scroll animation attributes
		let animationClass = element.dataset.scrollanimation,
			triggerHook = element.dataset.scrollhook || 'center',
			offset = element.dataset.offset || 0,
			triggerElement = element.dataset.scrolltrigger || element,
			duration = element.dataset.duration || 0,
			tween = element.dataset.scrollscrub,
			scene = '';

		//if animation has word up or down, its probably an animation that moves it up or down,
		//so make sure trigger element
		if (-1 !== animationClass.toLowerCase().indexOf('up') || -1 !== animationClass.toLowerCase().indexOf('down')) {
			//get parent element and make that the trigger, but use an offset from current element
			if (triggerElement === element) {
				triggerElement = element.parentElement;
			}
			offset = (element.offsetTop - triggerElement.offsetTop) + offset;
		}

		//if fixed at top, wrap in div
		if (element.getAttribute('data-scrollanimation') === 'fixed-at-top') {
			let wrappedElement = wrap(element, document.createElement('div'));
			wrappedElement.classList.add('fixed-holder');
			wrappedElement.style.height = element.offsetHeight + 'px';
			triggerHook = 'onLeave';
			triggerElement = element.parentElement;
		}

		//if scrollscrub exists used tweenmax
		if(tween !== undefined){
			if (! duration) {
				duration = 100;
			}

			tween = TweenMax.to(element, .65, {
				className: '+=' + animationClass
			});

			//finally output the scene
			scene = new ScrollMagic.Scene({
				triggerElement: triggerElement,
				offset: offset,
				triggerHook: triggerHook,
				duration: duration

			}).setTween(tween).addTo(scrollMagicController)
			// .addIndicators()
			;
		}else{
			scene = new ScrollMagic.Scene({
				triggerElement: triggerElement,
				offset: offset,
				triggerHook: triggerHook,
				duration: duration

			}).setClassToggle(element, animationClass).addTo(scrollMagicController)
			//.addIndicators()
			;
		}

		//good for knowing when its been loaded
		document.body.classList.add('scrollmagic-loaded');

	}
}

/**
 * Slide any element global function
 * @param item
 * @param slideTime
 * @param direction
 */
function ign_slide_element(item, slideTime = .5, direction = 'toggle'){

		if(direction === 'open'){
			TweenMax.set(item, {display: 'block', height: 'auto'});
			TweenMax.from(item, slideTime, {height: 0, display: 'none'});
		}

		else if(direction === 'close'){
			TweenMax.to(item, slideTime, {height: 0, display: 'none'});
		}

		else{

			if (item.offsetHeight === 0 || item.style.display == 'none') {
				//open
				TweenMax.set(item, {display: 'block', height: 'auto'}); //set it quickly to show if its not already
				TweenMax.from(item, slideTime, {height: 0, display: 'none'}); //go from 0 height
			} else {
				//close
				TweenMax.to(item, slideTime, {height: 'auto', display: 'block'});
			}
		}


}



//LOAD IGNITION EVENTS
document.addEventListener('DOMContentLoaded', function () {

	scrollMagicController = new ScrollMagic.Controller();
	document.querySelectorAll('[data-scrollanimation]').forEach( (element) => {
		runScrollerAttributes(element);
	});


	//TOGGLE BUTTONS
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
			// untoggle any other item with same radio value
			//radio items cannot be untoggled until another item is clicked

			//if item has data-switch it can only be turned on or off but not both by this button
			let radioSelector = item.getAttribute('data-radio');
			let switchItem = item.getAttribute('data-switch');
			if (radioSelector !== null) {
				let radioSelectors = document.querySelectorAll(`[data-radio="${radioSelector}"`);

				radioSelectors.forEach(radioItem => {
					if (radioItem !== item && radioItem.classList.contains('toggled-on')) {
						toggleItem(radioItem); //toggle all other radio items off when this one is being turned on
					}
				});
			}

			//finally toggle the clicked item. some types of items cannot be untoggled like radio or an on switch
			if (radioSelector !== null) {
				toggleItem(item, 'on'); //the item cannot be unclicked
			}
			else if(switchItem !== null){
				if(switchItem === 'on'){
					toggleItem(item, 'on');
				}
				else{
					toggleItem(item, 'off');
				}
			}
			else {
				toggleItem(item); //normal regular toggle
			}

		} //end if item found
	});

	//toggle an item and add class toggled-on and any other classes needed.
	function toggleItem (item, forcedState = 'none') {

		//toggle item
		if (forcedState === 'on') {
			item.classList.add('toggled-on'); //radio or data-switch of on will always toggle-on
		}
		else if(forcedState === 'off'){
			item.classList.remove('toggled-on'); //data-switch of off will always toggle off
		} else{
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

				//data slide open or closed
				if(targetItem.dataset.slide !== undefined){
					let slideTime = item.dataset.slide === '' ? .5 : parseInt(item.dataset.slide);
					if(isToggled){
						ign_slide_element(targetItem, slideTime, 'open');
					}else{
						ign_slide_element(targetItem, slideTime, 'close');
					}
				}

				//allow event to happen after click for the targeted item
				targetItem.dispatchEvent(toggleEvent);
			});
		} else { //applies class to the clicked item, there is no target
			if($class !== 'toggled-on'){ //add class to clicked item if its not set to be toggled-on
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



	//MOVING ITEMS
	//on Window resize we can move items to and from divs with data-moveto="the destination"
	//it will move there when the site reaches smaller than a size defaulted to 1030 or sett hat with data-moveat
	//the whole div, including the data att moveto moves back and forth
	let movedId = 0;

	function moveItems () {

		let windowWidth = window.innerWidth;
		let $moveItems = document.querySelectorAll('[data-moveto]');

		$moveItems.forEach(item => {
			let moveAt = item.getAttribute('data-moveat'),
				destination = document.querySelector(item.getAttribute('data-moveto')),
				source = item.getAttribute('data-movefrom');

			moveAt = moveAt ? moveAt : 1030;

			if (moveAt.startsWith('--')) {
				let cssVars = getComputedStyle(document.body); //get css variables
				moveAt = parseInt(cssVars.getPropertyValue(moveAt), 10);
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
			if (windowWidth < moveAt) {
				if (item.hasAttribute('data-moveto-pos')) {
					destination.insertBefore(item, destination.children[item.getAttribute('data-moveto-pos')]);
				} else {
					destination.appendChild(item);
				}
			} else {
				if (item.hasAttribute('data-movefrom-pos')) {
					source.insertBefore(item, source.children[item.getAttribute('data-movefrom-pos')]);
				} else {
					source.appendChild(item);
				}
			}

			//show it
			item.classList.add('visible');
		});
	}

	window.addEventListener('resize', throttle(moveItems, 250));
	moveItems();

	document.documentElement.classList.remove('dom-loading');







	//add finished loading ignition events
	let EventFinished = null;
	if ( isIE11 ) {
		EventFinished = document.createEvent( 'Event' );

		// Define that the event name is 'build'.
		EventFinished.initEvent( 'afterIgnEvents', true, true );

	} else {
		EventFinished = new Event( 'afterIgnEvents' );
	}
	document.dispatchEvent( EventFinished );
});




//Add inline retina image if found and on retina device. To use add data-high-res to an inline element with a background-image
if( isHighDensity() ) {

	let retinaImage = document.querySelectorAll('[data-high-res]');
	retinaImage.forEach(item => {
		let image2x = '';
		//if a high res is provided use that, else use background image but add 2x at end.
		if(item.dataset.highRes){
			image2x = item.dataset.highRes;
		}else{
			//get url for original image
			let image = item.style.backgroundImage.slice(4, -1).replace(/"/g, "");
			//add @2x to it if image exists.
			image2x = image.replace(/(\.[^.]+$)/, '@2x$1');
		}

		if(fileExists(image2x)){
			item.style.backgroundImage = 'url("' + image2x + '")';
		}

	});
}

//check if device is retina
function isHighDensity(){
	return ((window.matchMedia && (window.matchMedia('(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)').matches)));
}

//check if file exists on server before using
function fileExists(image_url){
	let http = new XMLHttpRequest();
	http.open('HEAD', image_url, true);
	http.send();
	return http.status != 404;
}

