"use strict";

/**
 * element can have these data attributes:
 * data-scrollanimation = a class to add to this element on scroll
 * data-scrollhook = onEnter, onLeave, defualt is center
 * data-scrolloffset = offset from scene start
 * data-scrolltrigger = the element that triggers the scene to start
 * data-scrollduration = how long it should last. if not set, 0  is used and that means it doesnt reset until you scroll up.
 * data-scrollscrub = tweens between two classes. tween expects a duration, else duration will be 100
 *
 */
var scrollMagicController = '';
jQuery(function ($) {
  if ('undefined' != typeof ScrollMagic) {
    //fixed at top items can ruin layout.
    //surround with a div thats same height and is part of layout
    var fixedItems = $('[data-scrollanimation="fixed-at-top"]');
    fixedItems.wrap("<div class=\"fixed-holder\" style=\"height: ".concat(fixedItems.css('height'), ";\"></div>"));
    scrollMagicController = new ScrollMagic.Controller(); //for simple animations

    $('[data-scrollanimation]').each(function () {
      //class to animate in
      var $this = $(this);
      var $class = $this.data('scrollanimation'),
          $triggerElem = $this;
      var $offset = $this.data('scrolloffset');

      if (null == $offset) {
        $offset = 0;
      }

      var $triggerHook = $this.data('scrollhook');

      if (null == $triggerHook) {
        $triggerHook = 'onEnter';
      }

      if (-1 !== $class.indexOf('fixed-at-top')) {
        $triggerHook = 'onLeave';
        $triggerElem = $this.parent();
      } //scrolling animations will go haywire if the item moves vertically. the scroll will change where it starts and ends continuously!


      if (-1 !== $class.indexOf('Up') || -1 !== $class.indexOf('Down')) {
        //get parent element and make that the trigger, but use an offset from that
        $triggerElem = $this.parent();
        $offset = $this.offset().top - $triggerElem.offset().top + $offset;
      }

      var $duration = $this.data('scrollduration');

      if (null == $duration) {
        $duration = 0;
      }

      if (null != $this.data('scrolltrigger')) {
        $triggerElem = $($this.data('scrolltrigger'));
      } //make triggerElement a dom node


      $triggerElem = $triggerElem[0]; //add a tween if found

      var $tween = $this.data('scrollscrub');
      var scene = '';

      if (null != $tween) {
        if (!$duration) {
          $duration = 100;
        }

        var tween = TweenMax.to($this[0], .65, {
          className: '+=' + $class
        }); //finally output the scene

        scene = new ScrollMagic.Scene({
          triggerElement: $triggerElem,
          offset: $offset,
          triggerHook: $triggerHook,
          duration: $duration
        }).setTween(tween).addTo(scrollMagicController) // .addIndicators()
        ;
      } else {
        scene = new ScrollMagic.Scene({
          triggerElement: $triggerElem,
          offset: $offset,
          triggerHook: $triggerHook,
          duration: $duration
        }).setClassToggle(this, $class).addTo(scrollMagicController) //.addIndicators()
        ;
      }
    }); //good for knowing when its been loaded

    $('body').addClass('scrollmagic-loaded');
  } //end scrollanimation
  //TOGGLE BUTTONS
  //adding new custom event for after the element is toggled


  var ToggleEvent = new Event('afterToggle'); //add aria to buttons currently on page

  var buttons = document.querySelectorAll('[data-toggle]');
  buttons.forEach(function (button) {
    button.setAttribute('role', 'switch');
    button.setAttribute('aria-checked', button.classList.contains('toggled-on') ? 'true' : 'false');
  }); //toggling the buttons with delegation click

  document.body.addEventListener('click', function (e) {
    var item = e.target.closest('[data-toggle]');

    if (item) {
      e.preventDefault();
      e.stopPropagation();
      item.classList.toggle('toggled-on');
      item.setAttribute('aria-expanded', item.classList.contains('toggled-on') ? 'true' : 'false');
      var $class = item.getAttribute('data-toggle'),
          $target = document.querySelectorAll(item.getAttribute('data-target'));

      if ($class) {
        if ($target.length) {
          $target.forEach(function (targetItem) {
            targetItem.classList.toggle($class);
          });
        } else {
          item.classList.toggle($class);
        }
      } //trigger optional afterToggle event


      item.dispatchEvent(ToggleEvent);
    }
  }); //MOVING ITEMS
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
  document.documentElement.classList.remove('dom-loading');
});
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
var iconAngleRight = "<span class='arrow'></span>"; //if using wordpress, icons may have already been localized. use those. otherwise add it

if (typeof icons !== 'undefined' && typeof icons.angleRight !== 'undefined') {
  iconAngleRight = icons.angleRight;
}

var screenReaderText = typeof screenReaderText == 'undefined' ? {} : screenReaderText;

if (screenReaderText == '') {
  screenReaderText.collapse = 'Collapse child menu';
  screenReaderText.expand = 'Expand child menu';
}

var submenuButtons = ''; //after page loads run

jQuery(function ($) {
  var navigation = $('.site-top'),
      menus = $('.menu'),
      menuToggle = $('.panel-left-toggle'),
      page = $('#page'),
      body = $('body');

  submenuButtons = function submenuButtons() {
    // Add dropdown arrow toggle button to all submenus.
    var dropdownToggle = $('<button />', {
      'class': 'submenu-dropdown-toggle',
      'aria-expanded': false
    }).append(iconAngleRight).append($('<span />', {
      'class': 'screen-reader-text',
      text: screenReaderText.expand
    })); //ADDING THE BUTTON TO PAGE SUBMENU ITEMS
    //I CANT FIND A PAGE WALKER HOOK TO DO IT

    menus.find('.page_item:not(.menu-item) a').wrap('<div class="menu-item-link"></div>');
    menus.find('.page_item_has_children .menu-item-link a').after(dropdownToggle); // Set the active submenu to be toggled on on mobile or not horizontal menus

    var currentSubmenus = menus.find('.current-menu-item > .sub-menu, .current_page_item > .sub-menu, .current_page_ancestor > .sub-menu, .current-menu-ancestor > .sub-menu');
    currentSubmenus.each(function () {
      if ($(this).css('display') === 'none' || $(this).parents('#panel-left, #panel-right').length) {
        //submenus are set to display none only in vertical menus which is what we want
        //add toggled on to the li and the button
        $(this).find('.current-menu-ancestor > .menu-item-link button, .current-menu-parent, .current-menu-parent button, .current_page_ancestor > button, .current_page_parent, .current-menu-item button').trigger('click');
      }
    }); //special after toggle event

    var dropdownButtons = document.querySelectorAll('.submenu-dropdown-toggle');
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
      var _loop = function _loop() {
        var dropdownButton = _step.value;
        dropdownButton.addEventListener('afterToggle', function (e) {
          //toggle the li. closest still best support with jquery
          $(dropdownButton).closest('li').toggleClass('toggled-on'); //toggle the sub menu

          var submenus = $(dropdownButton).closest('li').find('> .children, > .sub-menu');
          submenus.toggleClass('toggled-on').slideToggle();
          var screenReaderSpan = $(dropdownButton).find('.screen-reader-text');
          screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
      };

      for (var _iterator = dropdownButtons[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
        _loop();
      }
    } catch (err) {
      _didIteratorError = true;
      _iteratorError = err;
    } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator.return != null) {
          _iterator.return();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
    }
  };

  submenuButtons(); //NAVIGATION TOGGLE

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
    var middle = Math.floor($(navigationLi).length / 2) - 1; //add logo tot he middle when page loads

    $('<li class="menu-item li-logo-holder"><div class="menu-item-link"></div></li>').insertAfter(navigationLi.filter(':eq(' + middle + ')'));
    $('.site-logo').clone().appendTo('.li-logo-holder');
  }

  $('#btnCloseUpdateBrowser').on('click', function () {
    $('#outdated').hide();
  });
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

var debounce = function debounce(func, wait, immediate) {
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
};

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
} //create menu and sidebar button sizing
//the buttons need to sit outside site-top, otherwise they get covered by panels when they are open because site top is under panels.
//this makes sure the buttons are centered, but still  on top of site-top


document.addEventListener('DOMContentLoaded', function () {
  var $siteTopHeight = document.querySelector('.site-top').clientHeight;
  var menuButtons = ''; //if the menu button is outside site-top. get both buttons for centering both.

  if (!document.querySelector('.app-menu')) {
    menuButtons = document.querySelectorAll('.panel-left-toggle, .panel-right-toggle');
  } else {
    //otherwise the menu button does not need to be centered because its part of the app menu and moves.
    menuButtons = document.querySelectorAll('.panel-right-toggle');
    document.querySelector('.panel-left-toggle').classList.remove('hidden');
  }

  menuButtons.forEach(function (button) {
    button.style.height = $siteTopHeight + 'px';
    button.classList.remove('hidden'); //now they can be seen after height is set. But sidebar still might not show if there is no sidebar. css does that
  });
  window.addEventListener('resize', throttle(resizeMenuButtons, 500));

  function resizeMenuButtons() {
    $siteTopHeight = document.querySelector('.site-top').clientHeight;
    menuButtons.forEach(function (button) {
      //console.log(button);
      button.style.height = $siteTopHeight + 'px';
    });
  }
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
      $siteContent.prepend($('article .entry-header, .archive .entry-header, .page-header'));
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