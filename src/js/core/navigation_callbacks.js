import { ignSlideDown, ignSlideUp } from 'src/js/core/setup'

//CHANGE THE FUNCTIONS BELOW TO CHANGE HOW YOUR MENUS OPEN AND CLOSE
//menuItem is an li that has a .sub-menu, you can decide however you want to open this
//css for this can be found in menus.scss and menu_layout.scss
//its better to override the layout file in menu.scss rather than touch that

//opens a top level item when the menu is horizontal
export function toggleTopLevelHorizontalMenu(menuItem, open = true) {
   if (open) {
      //change to whatever you want ie: ignSlideDown...
      menuItem.classList.add('toggled-on')
   } else {
      menuItem.classList.remove('toggled-on')
   }
}


//runs when a toplevel vertical menu item is hovered or clicked
export function toggleTopLevelVerticalMenu(menuItem, open = true) {
   const subMenu = menuItem.querySelector('.sub-menu')
   if (open) {
      //change to whatever you want ie: ignSlideDown...
      menuItem.classList.add('toggled-on')
      return ignSlideDown(subMenu)
   } else {
      menuItem.classList.remove('toggled-on')
      return ignSlideUp(subMenu)
   }
}


//non on all top level submenus for click and hover
export function toggleSubMenu(menuItem, open = true) {
   const subMenu = menuItem.querySelector('.sub-menu')
   if (open) {
      //change to whatever you want ie: ignSlideDown...
      menuItem.classList.add('toggled-on')
      return ignSlideDown(subMenu)
   } else {
      menuItem.classList.remove('toggled-on')
      return ignSlideUp(subMenu)
   }
}


//when a top level horizontal
export function fixOffScreenMenu(submenu, side = 'right'){
   if(side === 'right'){
      submenu.closest('.menu-item').classList.add('offscreen-right')
   }

   if(side === 'left'){
      submenu.closest('.menu-item').classList.add('offscreen-left')
   }

   if(side === 'none'){
      submenu.closest('.menu-item').classList.remove('offscreen-left', 'offscreen-right')
   }
}