import { ignSlideDown, ignSlidePropertyReset, debounce, throttle, ignSlideUp } from './setup'
import * as themeConfig from '../../../theme.config'


//change these functions so that the menus appear how you want them to. default is to slide open and closed
//adds toggled-on to menu li and to submenu if exists, also slides down submenu if theme config has this setting
export function openSubMenu(menuItem, subMenu = '') {
   if (!subMenu) {
      subMenu = menuItem.querySelector('.sub-menu')
   }

   menuItem.classList.add('toggled-on')

   if (subMenu) {
      subMenu.classList.add('toggled-on')
      //move top level submenu over if it would end up offscreen
      if (menuItem.classList.contains('top-level-item')) {
         fixOffScreenMenu(subMenu)
      }

      if (subMenu) {
         ignSlideDown(subMenu)
      }

   }
}

export function closeSubMenu(menuItem, subMenu = '') {
   if (!subMenu) {
      subMenu = menuItem.querySelector('.sub-menu')
   }

   menuItem.classList.remove('toggled-on')
   if (subMenu) {
      subMenu.classList.remove('toggled-on')
      if (subMenu) {
         ignSlideUp(subMenu)
      }
   }
}


/**
 * if hovered on, it opens, hover off closes - this is only for a mouse
 * if pen or touch, it requires a click
 */
export function createMenuListener(menuItem) {
   if (!menuItem.id) {
      menuItem.id = 'menu-item' + Date.now()
   }

   //click / mousehover event
   menuItem.addEventListener('pointerover', (e) => {
      //sometimes there are nested menus, we dont want them all firing
      e.stopPropagation()

      //if this is a touch or pen item, this will open and close the menu
      if (e.pointerType!=='mouse') {
         if (e.target.closest('.menu a[href="#"]')) {
            e.preventDefault()
         }
         //if its not a mouse its a click event and will toggle open and closed
         if (!menuItem.classList.contains('toggled-on')) {
            openSubMenu(menuItem)
         } else {
            setTimeout(function () {
               document.activeElement.blur()
               closeSubMenu(menuItem)
            }, 100)
         }
      } else {
         //mouseover event will open on hover always
         if (!menuItem.classList.contains('toggled-on')) {
            openSubMenu(menuItem)
         }
      }
   })

   //only for mouse, touch will return
   menuItem.addEventListener('pointerleave', (e) => {
      if (e.pointerType!=='mouse') {
         return
      }
      e.stopPropagation()


      if (!menuItem.classList.contains('toggled-on')) {
         return
      }

      //smart way to make sure pointer out only runs when its off the parent item
      let elementTo = e.toElement || e.relatedTarget //where we went out to
      //if its a child dont close it
      if (elementTo!==null && elementTo.closest('#' + menuItem.id)) {
         return
      }
      closeSubMenu(menuItem)
      setTimeout(function () {
         document.activeElement.blur()
      }, 300)
   })
}


document.addEventListener('DOMContentLoaded', function () {

   //adds menu events to all menus. more menus can be added later by passing it through createMenuListener
   let menus = document.querySelectorAll('.menu .menu-item-has-children')
   menus.forEach((menuItem, index) => {
      if (!menuItem.id) {
         menuItem.id = 'menu-item-' + index
      }
      createMenuListener(menuItem)
   })


   //on load if its a vertical menu, open the parent dropdown right away
   document.querySelectorAll('.menu .current-menu-item, .menu .current-menu-parent').forEach(menu => {
      //if its a vertical menu. we can know by the flex direction of menu
      if (getComputedStyle(menu.closest('.menu')).flexDirection==='column') {
         openSubMenu(menu)
      }

   })


   // FOCUS EVENTS
   document.body.addEventListener('focusin', e => {

      const focusInElement = e.target

      if (focusInElement.closest('.menu-item')) {
         //get this links {a} li element
         const liItem = focusInElement.closest('.menu-item')
         //checking if this was a keyboard tab
         window.addEventListener('keyup', function (e) {

            let code = (e.keyCode ? e.keyCode:e.which)
            if (code===9 || code===16) {

               if (liItem.classList.contains('menu-item-has-children')) {
                  if (!liItem.classList.contains('toggled-on')) {
                     openSubMenu(liItem)

                     if (code===16) {
                        const lastMenuItem = liItem.querySelectorAll('.sub-menu li:last-child a')
                        lastMenuItem[lastMenuItem.length - 1].focus()
                     }
                  }
               }
            }


         }, { once: true })
      }
   })

   let activeElement = '' //new active element
   document.body.addEventListener('focusout', e => {

      let focusOutElement = e.target

      //if clicked off the entire menu system
      if (focusOutElement.closest('.menu-item')) {

         const liItem = focusOutElement.closest('.top-level-item')

         setTimeout(function () {
            //if active element is not even a menu-item close menu
            activeElement = document.activeElement

            //close element if click off somewhere else
            if (!activeElement.closest('.top-level-item')) {
               closeSubMenu(liItem)
            }
            return 2
         }, 200)


         window.addEventListener('keyup', function (e) {

            let code = (e.keyCode ? e.keyCode:e.which)

            //if shift tabbing off a current li that has a submenu
            if (code===16) {
               if (liItem && liItem.classList.contains('toggled-on')) {
                  closeSubMenu(liItem)
               }
            }

            //closing the whole menu if we tab off last item in the submenu
            if (code===9) {
               if (liItem.closest('.sub-menu li:last-child') && ! liItem.classList.contains('menu-item-has-children')) {
                  let menuParent = liItem.closest('.top-level-item')

                  if (menuParent.classList.contains('toggled-on')) {
                     closeSubMenu(menuParent)
                  }
               }
            }

         }, {
            once: true
         })
      }
   })


})


/*------- move submenus if too close to edge on desktop --------*/
function fixOffScreenMenu(menu) {
   let display = window.getComputedStyle(menu).display
   if (display!=='block') {
      menu.style.display = 'block'
   }
   //make item visible so we can get left edge

   let rightEdge = menu.getBoundingClientRect().right
   let leftEdge = menu.getBoundingClientRect().right
   //set menu back

   if (display!=='block') {
      menu.style.removeProperty('display')
   }


   let viewport = document.documentElement.clientWidth

   //if the submenu is off the page, pull it back somewhat
   if (rightEdge > viewport) {
      menu.style.left = '40px'
   }

   if (leftEdge < 0) {
      menu.style.left = '60%'
   }
}


jQuery(function ($) {

   //move logo in middle of menu on desktop if logo is middle position
   if ($('.logo-in-middle').length) {
      let navigationLi = $('.site-navigation__nav-holder .menu li')
      let middle = Math.floor($(navigationLi).length / 2) - 1

      //add logo to the middle when page loads
      $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'))
      $('.site-logo').clone().appendTo('.li-logo-holder')
   }

})
