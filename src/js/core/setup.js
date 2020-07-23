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
let ignSlideTimer = {}

//remove inline styling if any found except display
export function ignSlidePropertyReset(target) {
   //clear these properties
   target.style.removeProperty('padding-top')
   target.style.removeProperty('padding-bottom')
   target.style.removeProperty('margin-top')
   target.style.removeProperty('margin-bottom')
   target.style.removeProperty('overflow')
}


export function ignSlideUp(target, duration = .5) {

   return new Promise(function (resolve) {
      // stop slideDown from continuing
      if (ignSlideTimer[target]) {
         clearTimeout(ignSlideTimer[target])
      }
      //no sense sliding up if its hidden
      if (window.getComputedStyle(target).display==='none') {
         return
      }

      //set height just in case there is none. cannot be nothing or auto
      target.style.height = `${target.scrollHeight}px`


      target.style.transitionProperty = 'height, margin, padding'
      target.style.transitionDuration = duration + 's'

      //setting styles to 0
      target.style.overflow = 'hidden'

      //then closing the height from wherever it is currently
      setTimeout(() => {
         target.style.height = 0 //closing item
         target.style.paddingTop = 0
         target.style.paddingBottom = 0
         target.style.marginBottom = 0
         target.style.marginTop = 0
      }, 100)


      //after duration, set to hidden and reset touched properties. all that's left now is display:none;
      ignSlideTimer[target] = setTimeout(() => {
         target.style.display = 'none'
         ignSlidePropertyReset(target) //remove all margin,  paddings added once display is none
         target.style.removeProperty('transition-duration')
         target.style.removeProperty('transition-property')
         target.style.removeProperty('height')
         resolve()
      }, duration * 1000)
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
   return new Promise(function (resolve, reject) {

      // stop slideUp from continuing
      if (ignSlideTimer[target]) {
         clearTimeout(ignSlideTimer[target])
      }

      //open item by getting its padding margin and height.
      // //if its display none, then we can assume we are opening from a height of 0.

      //save original margins, and padding
      let height = window.getComputedStyle(target).height //might be open... or have a set height
      let display = window.getComputedStyle(target).display
      let paddingTop = window.getComputedStyle(target).paddingTop || 0
      let paddingBottom = window.getComputedStyle(target).paddingBottom || 0
      let marginBottom = window.getComputedStyle(target).marginBottom || 0
      let marginTop = window.getComputedStyle(target).marginTop || 0

      //cant animate from auto
      if (height==='auto') {
         target.style.height = 0
      }

      //if its hidden, it currently takes up no room
      // we will show from a height of 0 with no paddings or margins. those will animate in too so its a smooth height and margin
      if (display==='none') {
         display = 'block' //we will be setting this to show
         paddingBottom = paddingTop = marginBottom = marginTop = 0
         target.style.height = 0
      }

      //if its not display none, it may have space on the page (ie margin still might be showing even with height of 0)
      //in that case we dont want to touch those

      //set up transitions
      target.style.transitionProperty = 'height, margin, padding'
      target.style.transitionDuration = duration + 's'
      //make sure its overflow is set
      target.style.overflow = 'hidden'

      //finally set the display to show if not showing. happens fast
      target.style.display = display


      //with item showing and transitions enabled, we can slide it open. this runs after transitions set via a settimeout
      setTimeout(() => {
         //animate properties to open and normal
         target.style.paddingTop = paddingTop
         target.style.paddingBottom = paddingBottom
         target.style.height = `${target.scrollHeight}px`
         target.style.marginTop = marginTop
         target.style.marginBottom = marginBottom

      }, 0)

      //after it slides open remove certain properties
      ignSlideTimer[target] = setTimeout(() => {
         ignSlidePropertyReset(target)
         target.style.removeProperty('transition-duration')
         target.style.removeProperty('transition-property')
         target.style.removeProperty('height')
         resolve()
      }, duration * 1000)

   })
}

export function ignSlideToggle(target, duration = .5) {
   if (window.getComputedStyle(target).display==='none') {
      return ignSlideDown(target, duration)
   } else {
      return ignSlideUp(target, duration)
   }
}

