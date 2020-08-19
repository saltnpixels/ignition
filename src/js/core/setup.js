/*------- Core Functions --------*/

//wrap function. use in scrollmagic and more
export function wrap(el, wrapper) {
   if (wrapper===undefined) {
      wrapper = document.createElement('div')
   }
   el.parentNode.insertBefore(wrapper, el)
   wrapper.appendChild(el)
   return wrapper
}

//debounce to slow down an event that users window size or the like
//debounce will wait till the window is resized and then run
export function debounce(func, wait, immediate) {
   var timeout
   return function () {
      var context = this, args = arguments
      var later = function () {
         timeout = null
         if (!immediate) func.apply(context, args)
      }
      var callNow = immediate && !timeout
      clearTimeout(timeout)
      timeout = setTimeout(later, wait)
      if (callNow) func.apply(context, args)
   }
}

//throttle will run every few milliseconds as opposed to every millisecond
export function throttle(fn, threshhold, scope) {
   threshhold || (threshhold = 250)
   var last,
       deferTimer
   return function () {
      var context = scope || this

      var now = +new Date,
          args = arguments
      if (last && now < last + threshhold) {
         // hold on to it
         clearTimeout(deferTimer)
         deferTimer = setTimeout(function () {
            last = now
            fn.apply(context, args)
         }, threshhold)
      } else {
         last = now
         fn.apply(context, args)
      }
   }
}


///slide elements
let ignSlideTimer = Array //{} //turn into array nad ad a data-sliding wirth a number use that number as index to clear it

//remove inline styling if any found except display
export function ignSlidePropertyReset(target, direction) {


   if (direction==='up') {
      target.style.display = 'none'
   }

   //clear these properties
   target.style.removeProperty('transition-duration')
   target.style.removeProperty('transition-property')
   target.style.removeProperty('height')

   target.style.removeProperty('padding-top')
   target.style.removeProperty('padding-bottom')
   target.style.removeProperty('margin-top')
   target.style.removeProperty('margin-bottom')
   target.style.removeProperty('overflow')
   target.removeAttribute('slideTimer')

}


export function ignSlideUp(target, duration = .5) {
   return ignSlide('up', target, duration)
}


export function ignSlide(direction = 'up', target, duration = .5) {
   return new Promise(function (resolve, reject) {

      if (target.dataset.slideTimer) {
         clearTimeout(parseInt(target.dataset.slideTimer))
         target.removeAttribute('slide-timer')
      }


      const slideTimer = setTimeout(function () {
         ignSlidePropertyReset(target, direction)
         resolve()
      }, duration * 1000)
      target.dataset.slideTimer = slideTimer + ''



      //set transitions and overflow
      target.style.transitionProperty = 'height, margin, padding'
      target.style.transitionDuration = duration + 's'


      if (direction==='up') {
         target.style.overflow = 'hidden'
         //no point sliding up if its been set to hidden via css
         if (window.getComputedStyle(target).display==='none') {
            return
         }

         //set height just in case there is none. cannot be nothing or auto
         target.style.height = `${target.scrollHeight}px`


         //1 split second after: closing the height from wherever it is currently
         setTimeout(() => {
            target.style.height = 0 //closing item now
            target.style.paddingTop = 0
            target.style.paddingBottom = 0
            target.style.marginBottom = 0
            target.style.marginTop = 0
         }, 100)

      } else {
         //sliding down

         // save original margins, and padding, no the inline ones
         let height = window.getComputedStyle(target).height //might be open... or have a set height
         let display = window.getComputedStyle(target).display
         let paddingTop = window.getComputedStyle(target).paddingTop || 0
         let paddingBottom = window.getComputedStyle(target).paddingBottom || 0
         let marginBottom = window.getComputedStyle(target).marginBottom || 0
         let marginTop = window.getComputedStyle(target).marginTop || 0
         target.style.removeProperty('overflow')


         //cant animate from auto
         if (height==='auto') {
            target.style.height = 0
         }

         //if its not showing now, we will show from 0 on everything
         if (display==='none') {
            display = 'block' //we will be setting this to show
            paddingBottom = paddingTop = marginBottom = marginTop = 0 //animating from 0
            target.style.height = 0
         }

         //display must be set before transitioning below
         target.style.display = display

         //actual transitions
         setTimeout(() => {
            //animate properties to open and normal
            target.style.height = `${target.scrollHeight}px`

            //also animating the padding and margins
            target.style.paddingTop = paddingTop
            target.style.paddingBottom = paddingBottom
            target.style.marginTop = marginTop
            target.style.marginBottom = marginBottom

         }, 0)


      }
   })
}


/**
 *
 * @param target
 * @param duration
 *
 * Style element as it should show then set it to display none (or have it get display none from slide up or something else)
 */
export function ignSlideDown(target, duration = .5) {
   return ignSlide('down', target, duration)
}

export function ignSlideToggle(target, duration = .5) {
   if (window.getComputedStyle(target).display==='none') {
      return ignSlideDown(target, duration)
   } else {
      return ignSlideUp(target, duration)
   }
}

