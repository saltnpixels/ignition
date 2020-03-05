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
function runScrollerAttributes(element) {
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
			reverse = element.dataset.reverse || true;
			scene = '';

		//if animation has word up or down, its probably an animation that moves it up or down,
		//so make sure trigger element
		if (-1 !== animationClass.toLowerCase().indexOf('up') || -1 !== animationClass.toLowerCase().indexOf('down')) {
			//get parent element and make that the trigger, but use an offset from current element
			if (triggerElement === element) {
				triggerElement = element.parentElement;
				offset = (element.offsetTop - triggerElement.offsetTop) + parseInt(offset);
			}
			triggerHook = 'onEnter';

		}

		//if fixed at top, wrap in div
		if (element.getAttribute('data-scrollanimation') === 'fixed-at-top') {
			let wrappedElement = wrap(element, document.createElement('div'));
			wrappedElement.classList.add('fixed-holder');
			triggerHook = 'onLeave';
			triggerElement = element.parentElement;
		}

		//if scrollscrub exists used tweenmax
		if (tween !== undefined) {
			if (!duration) {
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
				duration: duration,
				reverse: reverse

			}).setTween(tween).addTo(scrollMagicController)
			// .addIndicators()
			;
		} else {

			scene = new ScrollMagic.Scene({
				triggerElement: triggerElement,
				offset: offset,
				triggerHook: triggerHook,
				duration: duration,
				reverse: reverse

			}).on('enter leave', function () {
				//instead of using toggle class we can use these events of on enter and leave and toggle class at both times
				element.classList.toggle(animationClass);
				element.classList.toggle('active');

				//if fixed at top set height for spacer and width
				if(element.getAttribute('data-scrollanimation') === 'fixed-at-top'){
					//making fixed item have a set width matching parent
					element.style.width = element.parentElement.clientWidth + 'px';
					element.style.left = element.parentElement.offsetLeft + 'px';

				}
			}).addTo(scrollMagicController)
			//.setClassToggle(element, animationClass + ' active').addTo(scrollMagicController)
			// .addIndicators()
			;
		}

		//good for knowing when its been loaded
		document.body.classList.add('scrollmagic-loaded');

	}
}


document.addEventListener('DOMContentLoaded', function () {
	/*------- Scroll Magic Events Init --------*/
	if ('undefined' != typeof ScrollMagic) {
		scrollMagicController = new ScrollMagic.Controller();
		document.querySelectorAll('[data-scrollanimation]').forEach((element) => {
			runScrollerAttributes(element);
		});
	}
});
