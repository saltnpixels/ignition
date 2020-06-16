/*------- Core Functions --------*/

//wrap function. use in scrollmagic and more
export function wrap(el, wrapper) {
	if (wrapper === undefined) {
		wrapper = document.createElement('div');
	}
	el.parentNode.insertBefore(wrapper, el);
	wrapper.appendChild(el);
	return wrapper;
}

//debounce to slow down an event that users window size or the like
//debounce will wait till the window is resized and then run
export function debounce(func, wait, immediate) {
	var timeout;
	return function () {
		var context = this, args = arguments;
		var later = function () {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}

//throttle will run every few milliseconds as opposed to every millisecond
export function throttle(fn, threshhold, scope) {
	threshhold || (threshhold = 250);
	var last,
		deferTimer;
	return function () {
		var context = scope || this;

		var now = +new Date,
			args = arguments;
		if (last && now < last + threshhold) {
			// hold on to it
			clearTimeout(deferTimer);
			deferTimer = setTimeout(function () {
				last = now;
				fn.apply(context, args);
			}, threshhold);
		} else {
			last = now;
			fn.apply(context, args);
		}
	};
}


//slide elements
let ignSlideTimer;

export function ignSlidePropertyReset(target) {
	clearTimeout(ignSlideTimer);
	target.style.removeProperty('height');
	target.style.removeProperty('padding-top');
	target.style.removeProperty('padding-bottom');
	target.style.removeProperty('margin-top');
	target.style.removeProperty('margin-bottom');
	target.style.removeProperty('overflow');
	target.style.removeProperty('transition-duration');
	target.style.removeProperty('transition-property');

}


export function ignSlideUp(target, duration = .5) {
	//add transition and ready the properties
	ignSlidePropertyReset(target);
	target.style.height = target.offsetHeight + 'px';

	target.style.transitionProperty = 'height, margin, padding';
	target.style.transitionDuration = duration + 's';
	target.style.overflow = 'hidden';
	target.style.paddingTop = 0;
	target.style.paddingBottom = 0;
	target.style.marginBottom = 0;
	target.style.marginTop = 0;


	setTimeout(() => {
		target.style.height = 0;
	}, 100);

	ignSlideTimer = setTimeout(() => {
		target.style.display = 'none';
		ignSlidePropertyReset(target);

	}, duration * 1000);
}


/**
 *
 * @param target
 * @param duration
 *
 * Style element as it should show then set it to display none (or have it get display none from slide up or something else)
 */
export function ignSlideDown(target, duration = .5) {
	//remove any inline properties for display and padding and margins that might be there, may have pressed this while it was sliding down
	ignSlidePropertyReset(target);


	//save original margins, check whether we are setting to block or some other (flex, inline-block)...
	let display = window.getComputedStyle(target).display;
	const padding = window.getComputedStyle(target).padding;
	const margin = window.getComputedStyle(target).margin;


	//if its none make it a block element then grab its height quickly
	if (display === 'none') {
		display = 'block';
	}

	target.style.display = display; //might be inline-block...

	//show element for s milisecond and grab height
	target.style.height = 'auto';
	target.style.overflow = 'hidden';
	let height = target.offsetHeight; //grab height while auto

	//set any other problematic property to 0
	target.style.transitionProperty = 'none';
	target.style.height = '0px'; //set height back to 0
	target.style.paddingTop = '0px';
	target.style.paddingBottom = '0px';
	target.style.marginTop = '0px';
	target.style.marginBottom = '0px';

	//set display to show, but padding and height to 0 right away
	setTimeout(() => {
		//turn on  transitions adn animate properties back to normal
		target.style.transitionProperty = "height, margin, padding";
		target.style.transitionDuration = duration + 's';
		target.style.padding = padding;
		target.style.height = height + 'px';
		target.style.margin = margin;

	}, 100);

	//after it slides open remove properties
	ignSlideTimer = setTimeout(() => {
		ignSlidePropertyReset(target);
	}, duration * 1000);
}

export function ignSlideToggle(target, duration = .5) {
	if (window.getComputedStyle(target).display === 'none') {
		return ignSlideDown(target, duration);
	} else {
		return ignSlideUp(target, duration);
	}
}

