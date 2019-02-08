//Set up some global stuff
//foreach on ie11. babel doesnt seem to fix so this works
if ('NodeList' in window && !NodeList.prototype.forEach) {
	console.info('polyfill for IE11');
	NodeList.prototype.forEach = function (callback, thisArg) {
		thisArg = thisArg || window;
		for (var i = 0; i < this.length; i++) {
			callback.call(thisArg, this[i], i, this);
		}
	};
}

function wrap(el, wrapper) {
	el.parentNode.insertBefore(wrapper, el);
	wrapper.appendChild(el);
	return wrapper;
}


let debounce = function (func, wait, immediate) {
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
};

function throttle (fn, threshhold, scope) {
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

//create menu and sidebar button sizing
//the buttons need to sit outside site-top, otherwise they get covered by panels when they are open because site top is under panels.
//this makes sure the buttons are centered, but still  on top of site-top
document.addEventListener('DOMContentLoaded', function () {
	let $siteTopHeight = document.querySelector('.site-top').clientHeight;
	let menuButtons = '';

	//if the menu button is outside site-top. get both buttons for centering both.
	if (!document.querySelector('.app-menu')) {
		menuButtons = document.querySelectorAll('.panel-left-toggle, .panel-right-toggle');
	}
	else {
		//otherwise the menu button does not need to be centered because its part of the app menu and moves.
		menuButtons = document.querySelectorAll('.panel-right-toggle');
		document.querySelector('.panel-left-toggle').classList.remove('hidden');
	}

	menuButtons.forEach(button => {
		button.style.height = $siteTopHeight + 'px';
		button.classList.remove('hidden'); //now they can be seen after height is set. But sidebar still might not show if there is no sidebar. css does that
	});

	window.addEventListener('resize', throttle(resizeMenuButtons, 500));

	function resizeMenuButtons () {
		$siteTopHeight = document.querySelector('.site-top').clientHeight;

		menuButtons.forEach(button => {
			//console.log(button);
			button.style.height = $siteTopHeight + 'px';
		});

	}

});


