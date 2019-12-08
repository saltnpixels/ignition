/*------- Core Functions --------*/

//wrap function
function wrap(el, wrapper) {
	if (wrapper === undefined) {
		wrapper = document.createElement('div');
	}
	el.parentNode.insertBefore(wrapper, el);
	wrapper.appendChild(el);
	return wrapper;
}

//debounce to slow down an event that users window size or the like
//debounce will wait till the window is resized and then run
function debounce(func, wait, immediate) {
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
function throttle(fn, threshhold, scope) {
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


