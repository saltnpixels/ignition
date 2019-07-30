"use strict";

/*--------------------------------------------------------------
# Adding some global events users can use via data attributes
--------------------------------------------------------------*/
//test if this is a touchscreen add class
if (!("ontouchstart" in document.documentElement)) {
  document.documentElement.className += " no-touch-device";
} else {
  document.documentElement.className += " touch-device";
}

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
      }

      offset = element.offsetTop - triggerElement.offsetTop + offset;
    } //if fixed at top, wrap in div


    if (element.getAttribute('data-scrollanimation') === 'fixed-at-top') {
      var wrappedElement = wrap(element, document.createElement('div'));
      wrappedElement.classList.add('fixed-holder');
      wrappedElement.style.height = element.offsetHeight + 'px';
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
      }).setClassToggle(element, animationClass).addTo(scrollMagicController) //.addIndicators()
      ;
    } //good for knowing when its been loaded


    document.body.classList.add('scrollmagic-loaded');
  }
}
/**
 * Slide any element global function
 * @param item
 * @param slideTime
 * @param direction
 */


function ign_slide_element(item) {
  var slideTime = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : .5;
  var direction = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'toggle';

  if (direction === 'open') {
    TweenMax.set(item, {
      display: 'block',
      height: 'auto'
    });
    TweenMax.from(item, slideTime, {
      height: 0,
      display: 'none'
    });
  } else if (direction === 'close') {
    TweenMax.to(item, slideTime, {
      height: 0,
      display: 'none'
    });
  } else {
    if (item.offsetHeight === 0 || item.style.display == 'none') {
      //open
      TweenMax.set(item, {
        display: 'block',
        height: 'auto'
      }); //set it quickly to show if its not already

      TweenMax.from(item, slideTime, {
        height: 0,
        display: 'none'
      }); //go from 0 height
    } else {
      //close
      TweenMax.to(item, slideTime, {
        height: 'auto',
        display: 'block'
      });
    }
  }
} //LOAD IGNITION EVENTS


document.addEventListener('DOMContentLoaded', function () {
  scrollMagicController = new ScrollMagic.Controller();
  document.querySelectorAll('[data-scrollanimation]').forEach(function (element) {
    runScrollerAttributes(element);
  }); //TOGGLE BUTTONS
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
      // untoggle any other item with same radio value
      //radio items cannot be untoggled until another item is clicked
      //if item has data-switch it can only be turned on or off but not both by this button


      var radioSelector = item.getAttribute('data-radio');
      var switchItem = item.getAttribute('data-switch');

      if (radioSelector !== null) {
        var radioSelectors = document.querySelectorAll("[data-radio=\"".concat(radioSelector, "\""));
        radioSelectors.forEach(function (radioItem) {
          if (radioItem !== item && radioItem.classList.contains('toggled-on')) {
            toggleItem(radioItem); //toggle all other radio items off when this one is being turned on
          }
        });
      } //finally toggle the clicked item. some types of items cannot be untoggled like radio or an on switch


      if (radioSelector !== null) {
        toggleItem(item, 'on'); //the item cannot be unclicked
      } else if (switchItem !== null) {
        if (switchItem === 'on') {
          toggleItem(item, 'on');
        } else {
          toggleItem(item, 'off');
        }
      } else {
        toggleItem(item); //normal regular toggle
      }
    } //end if item found

  }); //toggle an item and add class toggled-on and any other classes needed.

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
        } //data slide open or closed


        if (targetItem.dataset.slide !== undefined) {
          var slideTime = item.dataset.slide === '' ? .5 : parseInt(item.dataset.slide);

          if (isToggled) {
            ign_slide_element(targetItem, slideTime, 'open');
          } else {
            ign_slide_element(targetItem, slideTime, 'close');
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
  } //MOVING ITEMS
  //on Window resize we can move items to and from divs with data-moveto="the destination"
  //it will move there when the site reaches smaller than a size defaulted to 1030 or sett hat with data-moveat
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
        var cssVars = getComputedStyle(document.body); //get css variables

        moveAt = parseInt(cssVars.getPropertyValue(moveAt), 10);
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

      if (windowWidth < moveAt) {
        if (item.hasAttribute('data-moveto-pos')) {
          destination.insertBefore(item, destination.children[item.getAttribute('data-moveto-pos')]);
        } else {
          destination.appendChild(item);
        }
      } else {
        if (item.hasAttribute('data-movefrom-pos')) {
          source.insertBefore(item, source.children[item.getAttribute('data-movefrom-pos')]);
        } else {
          source.appendChild(item);
        }
      } //show it


      item.classList.add('visible');
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
}); //Add inline retina image if found and on retina device. To use add data-high-res to an inline element with a background-image

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
} //check if device is retina


function isHighDensity() {
  return window.matchMedia && window.matchMedia('(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)').matches;
} //check if file exists on server before using


function fileExists(image_url) {
  var http = new XMLHttpRequest();
  http.open('HEAD', image_url, true);
  http.send();
  return http.status != 404;
}
"use strict";

//turn icons into svg if using the icons that come with theme folder
var swapIconToSvg = function swapIconToSvg() {
  $('.svg-icon').each(function (index) {
    var iconClass = $(this).prop("class").replace("svg-icon", "").trim();
    $(this).replaceWith('<svg role="img" class="icon ' + iconClass + '"><use href="#' + iconClass + '" xlink:href="#' + iconClass + '"></use></svg>');
  });
};

jQuery(function ($) {
  swapIconToSvg();
});
"use strict";

//This file takes care of menus and navigation at the top
var iconAngleRight = '<span class="arrow"></span>'; //if using wordpress, icons may have already been localized. use those. otherwise add it

if (typeof icons !== 'undefined' && typeof icons.angleRight !== 'undefined') {
  iconAngleRight = icons.angleRight;
}

var screenReaderText = typeof screenReaderText == 'undefined' ? {} : screenReaderText;

if (screenReaderText == '') {
  screenReaderText.collapse = 'Collapse child menu';
  screenReaderText.expand = 'Expand child menu';
}

var submenuButtons = '';
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
        fixOffScreenMenu(subMenu);
      }

      ign_slide_element(subMenu, .5, isToggled);
    }
  });
  /*------- Tabbing through the menu --------*/

  var menuItems = document.querySelectorAll('.menu-item-link a');
  var lastTabbedItem = '';
  menuItems.forEach(function (menuItemLink) {
    //focus
    menuItemLink.addEventListener('focus', function (e) {
      menuItemLink.parentElement.classList.add('focus'); //add focus to .menu-item-link
      //if this element has a dropdown near it, toggle it now

      if (menuItemLink.nextElementSibling !== null) {
        menuItemLink.nextElementSibling.click(); //click the button to open the sub-menu
      } //if there is an item focused before


      if (lastTabbedItem) {
        //check if last item had a sub menu and we are not inside it now
        if (lastTabbedItem.nextElementSibling !== null && !lastTabbedItem.closest('li').contains(menuItemLink)) {
          lastTabbedItem.nextElementSibling.click();
        }
      }
    }); //blur

    menuItemLink.addEventListener('blur', function (e) {
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
    });
  });
}); //fix wp_page_menu to be like wp_nav_menu

jQuery(function ($) {
  var navigation = $('.site-top'),
      menuToggle = $('.panel-left-toggle'),
      page = $('#page'),
      body = $('body'); //NAVIGATION TOGGLE

  if (!menuToggle.length) {
    return;
  } //move button into site-top if app-menu type so the button shrinks too


  if (body.hasClass('app-menu')) {
    navigation.append(menuToggle);
  }

  menuToggle[0].addEventListener('afterToggle', function (e) {
    //if button is set to open
    if (menuToggle.hasClass('toggled-on')) {
      //if its an app-menu, add the menu-lock onto body
      if (body.hasClass('app-menu')) {
        body.addClass('menu-lock');
      } //clicking anywhere outside the menu will close it


      $('.site-content').one('click.Menu', function () {
        e.preventDefault();
        menuToggle.trigger('click'); //recursively calls click and closes
      });
    } else {
      $('.site-content').off('click.Menu');
      page.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function () {
        body.removeClass('menu-lock'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
      });
    }
  }); //move logo in middle of menu on desktop if logo is middle position

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

jQuery(function ($) {
  //wrap all youtube videos so they can be responsive.
  $('iframe[src*="youtube.com"], iframe[data-src*="youtube.com"]').each(function () {
    $(this).wrap('<div class="videowrapper"></div>');
  });
});
"use strict";

jQuery(function ($) {
  var $siteContent = $('.site-content');

  if ($('#secondary').length) {
    //clean it of whitespaces or :empty wont hide it in css
    var secondary = document.querySelector('#secondary');
    secondary.innerHTML = secondary.innerHTML.trim();

    if ($('.sidebar-template').hasClass('header-above')) {
      //move header out of article so its above sidebar and article and add class active which shows sidebar once header is moved
      $siteContent.prepend($('article .entry-header, .archive .entry-header, .page-header, .blog .entry-header'));
      $('.sidebar-template').addClass('active');
    }
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
      var offset = $(this).data('anchor-offset');

      if (!offset) {
        offset = 0;
      } // Does a scroll target exist?


      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top - offset
        }, 1000, function () {// Callback after animation
          // Must change focus!
          // var $target = $(target);
          // $target.focus();
          // if ($target.is(":focus")) { // Checking if the target was focused
          //     return false;
          // } else {
          //     $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
          //     $target.focus(); // Set focus again
          // }
        });
      }
    }
  });
});