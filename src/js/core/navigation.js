import { ignSlideDown, ignSlidePropertyReset, debounce, throttle, ignSlideUp } from './setup'
import {
   fixOffScreenMenu,
   toggleSubMenu,
   toggleTopLevelVerticalMenu,
   toggleTopLevelHorizontalMenu
} from './navigation_callbacks'
import * as themeConfig from '../../../theme.config'


//toggle logic functionality that calls the above functions
//use this one to run the opening and closing of a menu item. dont call above functions directly
function toggleMenuItem(menuItem, toggleState = true,) {

   let topLevel = isTopLevel(menuItem)
   let horizontalMenu = isHorizontalMenu(menuItem)

   //toplevel horizontal on tablet
   if (topLevel && horizontalMenu) {
      //also check if menu is offscreen and give it a class
      //checkOffScreenMenu(menuItem.querySelector('.sub-menu'))
      toggleTopLevelHorizontalMenu(menuItem, toggleState)
      return
   }
   //toplevel vertical on tablet
   if (topLevel && !horizontalMenu) {
      toggleTopLevelVerticalMenu(menuItem, toggleState)
      return
   }
   toggleSubMenu(menuItem, toggleState)
}


//MAIN MENU EVENT. CAN BE CALLED ON ANY MENU ITEM WITH CHILDREN
let menuClickEvent = false //make only one click event once a click is used
function createMenuListener(menuItem) {
   menuItem.addEventListener('pointerover', function (e) {
      e.stopPropagation()

      let toggleState = true //always open unless touch event which changes this below

      //TOUCH CLICK EVENT
      if (e.pointerType!=='mouse') {
         //clicking a real link opens it
         if (!e.target.closest(`a[href^="#"]`) && !e.target.closest('.submenu-dropdown-toggle')) {
            return
         }
         if (menuItem.classList.contains('toggled-on')) {
            toggleState = false
         }
         //if were opening a top level on horizontal with a click, we need a way to close another that may be opened
         if (isTopLevel(menuItem) && !menuItem.classList.contains('toggled-on') && isHorizontalMenu(menuItem)) {
            closeAllTopMenus()
         }
      }//touch device

      //open close for hover and device touch
      toggleMenuItem(menuItem, toggleState)


   }) //pointerover

   menuItem.addEventListener('pointerleave', function (e) {
      e.stopPropagation()

      //simply close for hover
      if (e.pointerType==='mouse') {
         toggleMenuItem(menuItem, false)
      }

      //triggers when the lcick on is removed...too fast so we need to add another event for clicking off
      if (e.pointerType!=='mouse') {
         //clicked up on touch now we want that fi they click elsewhere to close everything
         if (!menuClickEvent) {
            menuClickEvent = true
            document.addEventListener('click', (e) => {
               //if were not clicking a menu, close any menus opened
               if (!e.target.closest('.menu')) {
                  closeAllTopMenus()
               }
            })
         }
      }

   })
}

//close all top level menus
function closeAllTopMenus() {
   let otherMenuItems = document.querySelectorAll('.top-level-item.toggled-on')
   if (otherMenuItems) {
      otherMenuItems.forEach((item) => {
         toggleMenuItem(item, false)
      })
   }
}


function isTopLevel(menuItem) {
   return menuItem.classList.contains('top-level-item')
}

//if the item is inside a submenu inside another submenu
function isNestedSubMenu(menuItem) {
   return menuItem.classList.contains('nested-menu-item')
}

function isHorizontalMenu(menuItem) {
   return getComputedStyle(menuItem.closest('.menu')).flexDirection!=='column'
}


//fix and reset on resize
document.addEventListener('afterResize', function () {
   document.querySelectorAll('.top-level-item.menu-item-has-children').forEach((item) => {
      toggleMenuItem(item, false)
      item.querySelector('.sub-menu').style.removeProperty('display')

      if (isHorizontalMenu(item)) {
         checkOffScreenMenu(item.querySelector('.sub-menu'))
      }

   })
})


document.addEventListener('DOMContentLoaded', function () {

   //adds menu events to all menus. more menus can be added later by passing it through createMenuListener
   let menus = document.querySelectorAll('.menu-item')
   menus.forEach((menuItem, index) => {
      createMenuListener(menuItem)
   })

   //on load if its a vertical menu, open the parent dropdown right away
   document.querySelectorAll('.menu .current-menu-item.menu-item-has-children, .menu .current-menu-parent').forEach(menu => {
      //if its a vertical menu. we can know by the flex direction of menu
      if (getComputedStyle(menu.closest('.menu')).flexDirection==='column') {
         toggleMenuItem(menu)
      }
   })

})


// FOCUS EVENTS - only for keyboard
let menuMightBeOpen = false
document.body.addEventListener('focusin', e => {
   const menuItem = e.target.closest('.menu-item')

   if (menuItem && menuItem.classList.contains('menu-item-has-children')) {
      window.addEventListener('keyup', function (e) {
         let code = (e.keyCode ? e.keyCode:e.which)
         if (code===9 || code===16) {
            menuMightBeOpen = true

            //close other top menus when this one is turned on
            if (isTopLevel(menuItem)) {
               closeAllTopMenus()
            }

            toggleMenuItem(menuItem, true)
         }
      }, { once: true })
   }
   if (menuMightBeOpen) {
      closeAllTopMenus()
      menuMightBeOpen = false
   }

})


/*------- move submenus if too close to edge on desktop --------*/
function checkOffScreenMenu(submenu) {
   let display = window.getComputedStyle(submenu).display
   if (display!=='block') {
      submenu.style.display = 'block'
   }
   //make item visible so we can get left edge

   let rightEdge = submenu.getBoundingClientRect().right
   let leftEdge = submenu.getBoundingClientRect().left
   //set menu back

   if (display!=='block') {
      submenu.style.removeProperty('display')
   }


   let viewport = document.documentElement.clientWidth

   //if the submenu is off the page, pull it back somewhat
   if (rightEdge > viewport) {
      fixOffScreenMenu(submenu, 'right')
      return
   }

   if (leftEdge < 0) {
      fixOffScreenMenu(submenu, 'left')
   } else {
      fixOffScreenMenu(submenu, 'none')
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
