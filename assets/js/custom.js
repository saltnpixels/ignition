"use strict";

/*--------------------------------------------------------------
# Adding some global events and functions users can use via data attributes
--------------------------------------------------------------*/

/**
 * resize menu buttons on load. also runs on resize.
 * menu button is not inside site-top for various reasons (we dont want x to be inside or when menu opens the ex is uinderneath.
 * so we use this function to match the site -top height and center it as if it was inside
 */
var menuButtons = '';

function placeMenuButtons() {
  var $siteTopHeight = document.querySelector('.site-top');

  if ($siteTopHeight != null) {
    $siteTopHeight = $siteTopHeight.clientHeight;
  } // let adminbar = document.querySelector('#wpadminbar');
  // let adminbarHeight = 0;
  //
  // if (adminbar !== null) {
  // 	adminbarHeight = adminbar.clientHeight;
  // }


  if (menuButtons.length) {
    menuButtons.forEach(function (button) {
      button.style.height = $siteTopHeight + 'px';
    });
  }
}
/*--------------------------------------------------------------
# IGN Events
--------------------------------------------------------------*/


document.addEventListener('DOMContentLoaded', function () {
  /*------- Add touch classes or not --------*/
  if (!("ontouchstart" in document.documentElement)) {
    document.documentElement.className += " no-touch-device";
  } else {
    document.documentElement.className += " touch-device";
  }
  /*------- menu buttons --------*/
  //if the menu button is outside site-top. get both buttons for centering both.


  if (!document.querySelector('.app-menu')) {
    menuButtons = document.querySelectorAll('.panel-left-toggle, .panel-right-toggle');
  } else {
    //otherwise the menu button does not need to be centered because its part of the app menu and moves. (moved in navigation.js)
    menuButtons = document.querySelectorAll('.panel-right-toggle');
  } //we run menu button function below in resize event

  /*------- Toggle Buttons --------*/
  //trigger optional afterToggle event
  //adding new custom event for after the element is toggled


  var toggleEvent = null;

  if (isIE11) {
    toggleEvent = document.createEvent('Event'); // Define that the event name is 'build'.

    toggleEvent.initEvent('afterToggle', true, true);
  } else {
    toggleEvent = new Event('afterToggle', {
      bubbles: true
    }); //bubble allows for delegation on body
  } //add aria to buttons currently on page


  var buttons = document.querySelectorAll('[data-toggle]');
  buttons.forEach(function (button) {
    button.setAttribute('role', 'switch');
    button.setAttribute('aria-checked', button.classList.contains('toggled-on') ? 'true' : 'false');
  }); //toggling the buttons with delegation click

  document.body.addEventListener('click', function (e) {
    var item = e.target.closest('[data-toggle]');

    if (item) {
      var $doDefault = item.getAttribute('data-default'); //normally we prevent default unless someone add data-default

      if (null === $doDefault) {
        e.preventDefault();
        e.stopPropagation();
      } //if data-radio is found, only one can be selected at a time.
      // untoggles any other item with same radio value
      //radio items cannot be untoggled until another item is clicked


      var radioSelector = item.getAttribute('data-radio');

      if (radioSelector !== null) {
        var radioSelectors = document.querySelectorAll("[data-radio=\"".concat(radioSelector, "\"]"));
        radioSelectors.forEach(function (radioItem) {
          if (radioItem !== item && radioItem.classList.contains('toggled-on')) {
            toggleItem(radioItem); //toggle all other radio items off when this one is being turned on
          }
        });
      } //if item has data-switch it can only be turned on or off but not both by this button based on value of data-switch (its either on or off)


      var switchItem = item.getAttribute('data-switch'); //finally toggle the clicked item. some types of items cannot be untoggled like radio or an on switch

      if (radioSelector !== null) {
        toggleItem(item, 'on'); //the item clicked on cannot be unclicked until another item is pressed
      } else if (switchItem !== null) {
        if (switchItem === 'on') {
          toggleItem(item, 'on');
        } else {
          toggleItem(item, 'off');
        }
      } else {
        toggleItem(item); //normal regular toggle can turn itself on or off
      }
    } //end if item found

  }); //actual toggle of an item and add class toggled-on and any other classes needed. Also do a slide if necessary

  function toggleItem(item) {
    var forcedState = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'none';

    //toggle item
    if (forcedState === 'on') {
      item.classList.add('toggled-on'); //radio or data-switch of on will always toggle-on
    } else if (forcedState === 'off') {
      item.classList.remove('toggled-on'); //data-switch of off will always toggle off
    } else {
      item.classList.toggle('toggled-on'); //basic data toggle item
    } //is item toggled? used for the rest of this function to toggle another target if needed.


    var isToggled = item.classList.contains('toggled-on');
    item.setAttribute('aria-expanded', isToggled ? 'true' : 'false'); //get class to add to this item or another

    var $class = item.getAttribute('data-toggle'),
        $target = document.querySelectorAll(item.getAttribute('data-target'));

    if ($class === null || !$class) {
      $class = 'toggled-on'; //default class added is toggled-on
    } //special class added to another item


    if ($target.length) {
      $target.forEach(function (targetItem) {
        if (isToggled) {
          targetItem.classList.add($class);
        } else {
          targetItem.classList.remove($class);
        }

        targetItem.setAttribute('aria-expanded', isToggled ? 'true' : 'false'); //data slide open or closed

        if (targetItem.dataset.slide !== undefined) {
          var slideTime = targetItem.dataset.slide ? parseFloat(targetItem.dataset.slide) : .5;

          if (isToggled) {
            ignSlideDown(targetItem, slideTime);
          } else {
            ignSlideUp(targetItem, slideTime);
          }
        } //allow event to happen after click for the targeted item


        targetItem.dispatchEvent(toggleEvent);
      });
    } else {
      //applies class to the clicked item, there is no target
      if ($class !== 'toggled-on') {
        //add class to clicked item if its not set to be toggled-on
        if (isToggled) {
          item.classList.toggle($class);
        } else {
          item.classList.remove($class);
        }
      }
    } //trigger optional afterToggle event. continue the click event for customized stuff


    item.dispatchEvent(toggleEvent);
  }
  /*------- Moving items Event as well as all resizing --------*/
  //on Window resize we can move items to and from divs with data-moveto="the destination"
  //it will move there when the site reaches smaller than a size defaulted to 1030 or set that with data-moveat
  //the whole div, including the data att moveto moves back and forth


  var movedId = 0;

  function moveItems() {
    var windowWidth = window.innerWidth;
    var $moveItems = document.querySelectorAll('[data-moveto]');
    $moveItems.forEach(function (item) {
      var moveAt = item.getAttribute('data-moveat'),
          destination = document.querySelector(item.getAttribute('data-moveto')),
          source = item.getAttribute('data-movefrom');
      moveAt = moveAt ? moveAt : 1030;

      if (moveAt.startsWith('--')) {
        if (isIE11) {
          moveAt = 1030;
        } else {
          var cssVars = getComputedStyle(document.body); //get css variables

          moveAt = parseInt(cssVars.getPropertyValue(moveAt), 10);
        }
      }

      if (!destination) {
        return;
      } //if no data movefrom is found add one to parent so we can move items back in. now they go back and forth


      if (!source) {
        var sourceElem = item.parentElement.id; //if parent has no id attr, add one with a number so its unique

        if (!sourceElem) {
          item.parentElement.setAttribute('id', 'move-' + movedId);
          movedId++;
          sourceElem = item.parentElement.id;
        }

        item.setAttribute('data-movefrom', '#' + sourceElem);
      }

      source = document.querySelector(item.getAttribute('data-movefrom')); //if the screen is smaller than moveAt (1030), move to destination

      if (windowWidth < moveAt || moveAt == 0) {
        //no need to move if its already there...
        if (!destination.contains(item)) {
          if (item.hasAttribute('data-moveto-pos')) {
            destination.insertBefore(item, destination.children[item.getAttribute('data-moveto-pos')]);
          } else {
            destination.appendChild(item);
          }
        }
      } else {
        if (!source.contains(item)) {
          if (item.hasAttribute('data-movefrom-pos')) {
            source.insertBefore(item, source.children[item.getAttribute('data-movefrom-pos')]);
          } else {
            source.appendChild(item);
          }
        }
      } //show it


      item.classList.add('visible');
    });
    placeMenuButtons(); //running the moving of menu buttons here. nothing to do with moving items.
    //fix height of fixed holder fixed at top items

    document.querySelectorAll('.fixed-holder').forEach(function (fixed) {
      fixed.style.height = fixed.firstElementChild.clientHeight + 'px';
    });
  }

  window.addEventListener('resize', throttle(moveItems, 250));
  moveItems();
  document.documentElement.classList.remove('dom-loading'); //add finished loading ignition events

  var EventFinished = null;

  if (isIE11) {
    EventFinished = document.createEvent('Event'); // Define that the event name is 'build'.

    EventFinished.initEvent('afterIgnEvents', true, true);
  } else {
    EventFinished = new Event('afterIgnEvents');
  }

  document.dispatchEvent(EventFinished);
});
/*------- Function for hi red background image swap --------*/
//check if device is retina

function isHighDensity() {
  return window.matchMedia && window.matchMedia('(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)').matches;
} //check if file exists on server before using


function fileExists(image_url) {
  var http = new XMLHttpRequest();
  http.open('HEAD', image_url, true);
  http.send();
  return http.status != 404;
} //Add inline retina image if found and on retina device. To use add data-high-res to an inline element with a background-image


if (isHighDensity()) {
  var retinaImage = document.querySelectorAll('[data-high-res]');
  retinaImage.forEach(function (item) {
    var image2x = ''; //if a high res is provided use that, else use background image but add 2x at end.

    if (item.dataset.highRes) {
      image2x = item.dataset.highRes;
    } else {
      //get url for original image
      var image = item.style.backgroundImage.slice(4, -1).replace(/"/g, ""); //add @2x to it if image exists.

      image2x = image.replace(/(\.[^.]+$)/, '@2x$1');
    }

    if (fileExists(image2x)) {
      item.style.backgroundImage = 'url("' + image2x + '")';
    }
  });
}
"use strict";

//turn icons into svg if using the icons that come with theme folder
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.svg-icon').forEach(function (icon) {
    icon.classList.remove('svg-icon'); //classlist.value does not wokr in ie11. use getAttrbiute

    var iconClass = icon.getAttribute('class'); //ie11 does not work well with nodes. needed to add as string. no createelementNS

    var iconString = "<svg class=\"icon ".concat(iconClass, "\" role=\"img\"><use href=\"#").concat(iconClass, "\" xlink:href=\"#").concat(iconClass, "\"></use></svg>"); // let iconsvg = document.createElementNS('http://www.w3.org/2000/svg','svg');
    // iconsvg.setAttribute('class', 'icon ' + iconClass);
    // iconsvg.setAttribute('role', 'img');
    //iconsvg.innerHTML = `<use href="#${iconClass}" xlink:href="#${iconClass}"></use>`;

    icon.insertAdjacentHTML('afterend', iconString);
    icon.remove();
  });
});
"use strict";

/*------- move submenus if too close to edge on desktop --------*/
function fixOffScreenMenu(menu) {
  //make item visible so we can get left edge
  menu.style.display = 'block';
  menu.style.opacity = '0';
  var rightEdge = menu.getBoundingClientRect().right;
  var leftEdge = menu.getBoundingClientRect().right; //set menu back

  menu.style.display = '';
  menu.style.opacity = '';
  var viewport = document.documentElement.clientWidth; //if the submenu is off the page, pull it back somewhat

  if (rightEdge > viewport) {
    menu.style.left = '40px';
  }

  if (leftEdge < 0) {
    menu.style.left = '60%';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  /*------- slide sub menus open and closed when a dropdown button is clicked --------*/
  document.body.addEventListener('afterToggle', function (evt) {
    //for every dropdown menu button, when clicked toggle the li parent and open the sub-menu with slide
    if (evt.target.closest('.submenu-dropdown-toggle')) {
      var menuItem = evt.target.closest('li');
      var isToggled = evt.target.classList.contains('toggled-on') ? 'open' : 'close';
      var subMenu = menuItem.querySelector('.sub-menu');

      if (isToggled === 'open') {
        fixOffScreenMenu(subMenu); //add class toggled-on to li. cant do it via data-target cause menu might be showing twice on page

        evt.target.closest('li').classList.add('toggled-on');
        ignSlideDown(subMenu);
      } else {
        evt.target.closest('li').classList.remove('toggled-on');
        ignSlideUp(subMenu);
      }
    }
  });
  /*------- Open any current menu items in vertical menus --------*/
  //if a vertical menu has a current item it is set to display block. We can target that and use it to set the click to open

  document.querySelectorAll('.menu .current-menu-item .sub-menu, .menu .current-menu-parent .sub-menu').forEach(function (subMenu) {
    //if its a vertical menu
    if (getComputedStyle(subMenu.closest('.menu')).flexDirection === 'column') {
      subMenu.style.display = 'block';
      subMenu.style.height = 'auto';
      subMenu.closest('.menu-item').classList.add('toggled-on');
      subMenu.closest('.menu-item').querySelector('.submenu-dropdown-toggle').classList.add('toggled-on');
    }
  });
  /*------- Tabbing through the menu for ADA compliance --------*/

  var lastTabbedItem = ''; //focus

  document.body.addEventListener('focusin', function (e) {
    if (e.target.closest('.menu-item-link a')) {
      var menuItemLink = e.target.closest('.menu-item-link a');
      window.addEventListener('keyup', function (e) {
        var code = e.keyCode ? e.keyCode : e.which; //tab or shift tab

        if (code === 9 || code === 16) {
          menuItemLink.parentElement.classList.add('focus'); //add focus to .menu-item-link
          //if this element has a dropdown near it, toggle it now

          if (menuItemLink.nextElementSibling !== null && !menuItemLink.closest('li').classList.contains('toggled-on')) {
            menuItemLink.nextElementSibling.click(); //click the button to open the sub-menu
          } //if there is an item focused before


          if (lastTabbedItem) {
            //check if last item had a sub menu and we are not inside it now
            if (lastTabbedItem.nextElementSibling !== null && !lastTabbedItem.closest('li').contains(menuItemLink)) {
              lastTabbedItem.nextElementSibling.click();
            }
          }
        }
      }, {
        once: true
      });
    }
  }); //blur

  document.body.addEventListener('focusout', function (e) {
    if (e.target.closest('.menu-item-link a')) {
      var menuItemLink = e.target.closest('.menu-item-link a');
      window.addEventListener('keyup', function (e) {
        var code = e.keyCode ? e.keyCode : e.which;
        console.log(code);

        if (code === 9 || code === 16) {
          //blur current tabbed item, but dont close it if its a sub-menu
          menuItemLink.parentElement.classList.remove('focus');
          lastTabbedItem = menuItemLink;
          var subMenu = menuItemLink.closest('.sub-menu'); //if we blurred an item in a sub-menu

          if (subMenu !== null) {
            console.log('blurred item inside sub-menu');
            var menuItem = menuItemLink.closest('.menu-item'); //if its the last item in the submenu and it does not have a sub-menu itself

            if (menuItem.nextElementSibling == null && menuItem.querySelector('.sub-menu') == null) {
              menuItem.parentElement.closest('.menu-item').querySelector('.submenu-dropdown-toggle').click();
            }
          }
        }
      }, {
        once: true
      });
    }
  }); //app-menu ability for the top menu

  var body = document.body;
  var menuToggle = document.querySelector('.panel-left-toggle');
  var topNav = document.querySelector('.site-top');
  var page = document.querySelector('#page'); //first move the button into site-top if app-menu is being used cause we dont want it on the outside

  if (body.classList.contains('app-menu')) {
    topNav.append(menuToggle);
  }

  function closeAppMenu(e) {
    e.preventDefault();
    menuToggle.click();
  } //when button is opened we will lock the body so there is no scrolling and then open the page


  if (menuToggle) {
    menuToggle.addEventListener('afterToggle', function (e) {
      //if button has been toggled on
      if (menuToggle.classList.contains('toggled-on')) {
        body.classList.add('body-lock'); //clicking anywhere outside the menu will close it

        document.querySelector('.site-content').addEventListener('click', closeAppMenu, {
          once: true
        });
      } else {
        document.querySelector('.site-content').removeEventListener('click', closeAppMenu);

        if (body.classList.contains('app-menu')) {
          page.addEventListener('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
            body.classList.remove('body-lock'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
          }, {
            once: true
          });
        } else {
          body.classList.remove('body-lock');
        }
      }
    });
  }
}); //end ready

jQuery(function ($) {
  //move logo in middle of menu on desktop if logo is middle position
  if ($('.logo-in-middle').length) {
    var navigationLi = $('.site-navigation__nav-holder .menu li');
    var middle = Math.floor($(navigationLi).length / 2) - 1; //add logo to the middle when page loads

    $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
    $('.site-logo').clone().appendTo('.li-logo-holder');
  }
});
"use strict";

jQuery(function ($) {
  'use strict'; // the css selector for the container that the image should be attached to as a background-image

  var imgContainer = '.background-image, .cover-image';

  function getCurrentSrc(element, cb) {
    var _getSrc;

    if (!window.HTMLPictureElement) {
      if (window.respimage) {
        respimage({
          elements: [element]
        });
      } else if (window.picturefill) {
        picturefill({
          elements: [element]
        });
      }

      cb(element.src);
      return;
    }

    _getSrc = function getSrc() {
      element.removeEventListener('load', _getSrc);
      element.removeEventListener('error', _getSrc);
      cb(element.currentSrc);
    };

    element.addEventListener('load', _getSrc);
    element.addEventListener('error', _getSrc);

    if (element.complete) {
      _getSrc();
    }
  }

  function setBgImage() {
    $(imgContainer).each(function () {
      var $this = $(this),
          img = $this.find('img').get(0);
      getCurrentSrc(img, function (elementSource) {
        $this.css('background-image', 'url(' + elementSource + ')');
      });
    });
  }

  if ('objectFit' in document.documentElement.style === false) {
    $('html').addClass('no-objectfit');
    $(window).resize(function () {
      setBgImage();
    });
    setBgImage();
  }
});
"use strict";

//make iframe videos responsive
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('iframe[src*="youtube.com"], iframe[data-src*="youtube.com"], iframe[src*="vimeo.com"], iframe[data-src*="vimeo.com"]').forEach(function (iframe) {
    if (!iframe.parentElement.classList.contains('videowrapper')) {
      wrap(iframe).classList.add('videowrapper');
    }
  });
});
"use strict";

/*------- Core Functions --------*/
//wrap function
function wrap(el, wrapper) {
  if (wrapper === undefined) {
    wrapper = document.createElement('div');
  }

  el.parentNode.insertBefore(wrapper, el);
  wrapper.appendChild(el);
  return wrapper;
} //debounce to slow down an event that users window size or the like
//debounce will wait till the window is resized and then run


function debounce(func, wait, immediate) {
  var timeout;
  return function () {
    var context = this,
        args = arguments;

    var later = function later() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };

    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
} //throttle will run every few milliseconds as opposed to every millisecond


function throttle(fn, threshhold, scope) {
  threshhold || (threshhold = 250);
  var last, deferTimer;
  return function () {
    var context = scope || this;
    var now = +new Date(),
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
} //slide elements


var ignSlideTimer;

function ignSlidePropertyReset(target) {
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

function ignSlideUp(target) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : .5;
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
  setTimeout(function () {
    target.style.height = 0;
  }, 100);
  ignSlideTimer = setTimeout(function () {
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


function ignSlideDown(target) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : .5;
  //remove any inline properties for display and padding and margins that might be there, may have pressed this while it was sliding down
  ignSlidePropertyReset(target); //save original margins, check whether we are setting to block or some other (flex, inline-block)...

  var display = window.getComputedStyle(target).display;
  var padding = window.getComputedStyle(target).padding;
  var margin = window.getComputedStyle(target).margin; //if its none make it a block element then grab its height quickly

  if (display === 'none') {
    display = 'block';
  }

  target.style.display = display; //might be inline-block...
  //show element for s milisecond and grab height

  target.style.height = 'auto';
  target.style.overflow = 'hidden';
  var height = target.offsetHeight; //grab height while auto
  //set any other problematic property to 0

  target.style.transitionProperty = 'none';
  target.style.height = '0px'; //set height back to 0

  target.style.paddingTop = '0px';
  target.style.paddingBottom = '0px';
  target.style.marginTop = '0px';
  target.style.marginBottom = '0px'; //set display to show, but padding and height to 0 right away

  setTimeout(function () {
    //turn on  transitions adn animate properties back to normal
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + 's';
    target.style.padding = padding;
    target.style.height = height + 'px';
    target.style.margin = margin;
  }, 100); //after it slides open remove properties

  ignSlideTimer = setTimeout(function () {
    ignSlidePropertyReset(target);
  }, duration * 1000);
}

function ignSlideToggle(target) {
  var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : .5;

  if (window.getComputedStyle(target).display === 'none') {
    return ignSlideDown(target, duration);
  } else {
    return ignSlideUp(target, duration);
  }
}
"use strict";

document.addEventListener('DOMContentLoaded', function () {
  //move the header above the article when header-above is found
  var headerAbove = document.querySelector('.header-above');

  if (headerAbove !== null) {
    document.querySelectorAll('.entry-header, .page-header').forEach(function (header) {
      headerAbove.prepend(header);
      header.classList.add('header-moved'); //might be useful for someone
    });
  } //when a secondary is used, a sidebar is shown, on load we do a few things to smooth the transition of the header


  var sidebar = document.querySelector('#secondary');

  if (sidebar !== null) {
    sidebar.innerHTML = sidebar.innerHTML.trim(); //if moving stuff in and out its good to remove extra space so :empty works

    var sidebarTemplate = document.querySelector('.sidebar-template');
    sidebarTemplate.classList.add('active');
  }
});
"use strict";

jQuery(function ($) {
  // Select all links with hashes
  $('a[href*="#"]') // Remove links that don't actually link to anything
  .not('[href="#"]').not('[href="#0"]').click(function (event) {
    // On-page links
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      var offset = $(this).data('anchor-offset') || 0; // Does a scroll target exist?

      if (target.length) {
        //console.log(target);
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top - offset
        }, 700, function () {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();

          if ($target.is(":focus")) {
            // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable

            $target.focus(); // Set focus again
          }
        });
      }
    }
  });
});
"use strict";

var scrollMagicController = ''; //setup scroller function

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
    var animationClass = element.dataset.scrollanimation,
        triggerHook = element.dataset.scrollhook || 'center',
        offset = element.dataset.offset || 0,
        triggerElement = element.dataset.scrolltrigger || element,
        duration = element.dataset.duration || 0,
        tween = element.dataset.scrollscrub,
        scene = ''; //if animation has word up or down, its probably an animation that moves it up or down,
    //so make sure trigger element

    if (-1 !== animationClass.toLowerCase().indexOf('up') || -1 !== animationClass.toLowerCase().indexOf('down')) {
      //get parent element and make that the trigger, but use an offset from current element
      if (triggerElement === element) {
        triggerElement = element.parentElement;
        offset = element.offsetTop - triggerElement.offsetTop + parseInt(offset);
      }

      triggerHook = 'onEnter';
    } //if fixed at top, wrap in div


    if (element.getAttribute('data-scrollanimation') === 'fixed-at-top') {
      var wrappedElement = wrap(element, document.createElement('div'));
      wrappedElement.classList.add('fixed-holder');
      triggerHook = 'onLeave';
      triggerElement = element.parentElement;
    } //if scrollscrub exists used tweenmax


    if (tween !== undefined) {
      if (!duration) {
        duration = 100;
      }

      tween = TweenMax.to(element, .65, {
        className: '+=' + animationClass
      }); //finally output the scene

      scene = new ScrollMagic.Scene({
        triggerElement: triggerElement,
        offset: offset,
        triggerHook: triggerHook,
        duration: duration
      }).setTween(tween).addTo(scrollMagicController) // .addIndicators()
      ;
    } else {
      scene = new ScrollMagic.Scene({
        triggerElement: triggerElement,
        offset: offset,
        triggerHook: triggerHook,
        duration: duration
      }).on('enter leave', function () {
        //instead of using toggle class we can use these events of on enter and leave and toggle class at both times
        element.classList.toggle(animationClass);
        element.classList.toggle('active'); //if fixed at top set height for spacer and width

        if (element.getAttribute('data-scrollanimation') === 'fixed-at-top') {
          //making fixed item have a set width matching parent
          element.style.width = element.parentElement.clientWidth + 'px';
          element.style.left = element.parentElement.offsetLeft + 'px';
        }
      }).addTo(scrollMagicController) //.setClassToggle(element, animationClass + ' active').addTo(scrollMagicController)
      // .addIndicators()
      ;
    } //good for knowing when its been loaded


    document.body.classList.add('scrollmagic-loaded');
  }
}

document.addEventListener('DOMContentLoaded', function () {
  /*------- Scroll Magic Events Init --------*/
  if ('undefined' != typeof ScrollMagic) {
    scrollMagicController = new ScrollMagic.Controller();
    document.querySelectorAll('[data-scrollanimation]').forEach(function (element) {
      runScrollerAttributes(element);
    });
  }
});