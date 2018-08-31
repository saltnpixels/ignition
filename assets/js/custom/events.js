

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
let scrollMagicController = '';
jQuery(function ($) {
    if (typeof ScrollMagic != "undefined") {
        //fixed at top items can ruin layout.
        //surround with a div thats same height and is part of layout
        const fixedItems = $('[data-scrollanimation="fixed-at-top"]');
        fixedItems.wrap(`<div class="fixed-holder" style="height: ${fixedItems.css('height')};"></div>`);


        scrollMagicController = new ScrollMagic.Controller();

        //for simple animations
        $('[data-scrollanimation]').each(function () {
            //class to animate in
            let $this = $(this);


            let $class = $this.data('scrollanimation'),
                $triggerElem = $this;


            let $offset = $this.data('scrolloffset');
            if ($offset == null) {
                $offset = 0;
            }

            let $triggerHook = $this.data('scrollhook');
            if ($triggerHook == null) {
                $triggerHook = 'onEnter';
            }

            if ($class.indexOf('fixed-at-top') !== -1) {
                $triggerHook = 'onLeave';
                $triggerElem = $this.parent();
            }

            //scrolling animations will go haywire if the item moves vertically. the scroll will change where it starts and ends continuously!
            if ($class.indexOf('Up') !== -1 || $class.indexOf('Down') !== -1 ) {
                //get parent element and make that the trigger, but use an offset from that
                $triggerElem = $this.parent();
                $offset = ($this.offset().top - $triggerElem.offset().top) + $offset;
            }


            let $duration = $this.data('scrollduration');
            if ($duration == null) {
                $duration = 0;
            }

            if ($this.data('scrolltrigger') != null) {
                $triggerElem = $this.data('scrolltrigger');
            }


            //make triggerElement a dom node
            $triggerElem = $triggerElem[0];

            //add a tween if found
            let $tween = $this.data('scrollscrub');
            let scene = '';
            if ($tween != null) {

                if (!$duration) {
                    $duration = 100;
                }

                let tween = TweenMax.to($this[0], .65, {
                    className: '+=' + $class
                });

                //finally output the scene
                scene = new ScrollMagic.Scene({
                    triggerElement: $triggerElem,
                    offset: $offset,
                    triggerHook: $triggerHook,
                    duration: $duration

                }).setTween(tween).addTo(scrollMagicController)
                // .addIndicators()
                ;
            } else {

                scene = new ScrollMagic.Scene({
                    triggerElement: $triggerElem,
                    offset: $offset,
                    triggerHook: $triggerHook,
                    duration: $duration

                }).setClassToggle(this, $class).addTo(scrollMagicController)
                //.addIndicators()
                ;
            }


        });


        //good for knowing when its been loaded
        $('body').addClass('scrollmagic-loaded');

    } //end scrollanimation


    //TOGGLE BUTTONS
    //adding new custom event for after the element is toggled
    let ToggleEvent = new Event('afterToggle');

    //add aria to buttons currently on page
    let buttons = document.querySelectorAll('[data-toggle]');
    buttons.forEach(button => {
        button.setAttribute('role', 'switch');
        button.setAttribute('aria-checked', button.classList.contains('toggled-on') ? 'true' : 'false');

    });

    //toggling the buttons with delegation click
    document.body.addEventListener('click', e => {

        let item = e.target.closest('[data-toggle]');


        if (item) {
            e.preventDefault();
            e.stopPropagation();

            item.classList.toggle('toggled-on');
            item.setAttribute('aria-expanded', item.classList.contains('toggled-on') ? 'true' : 'false');

            let $class = item.getAttribute('data-toggle'),
                $target = document.querySelectorAll(item.getAttribute('data-target'));

            if( $class) {
                if ($target.length) {
                    $target.forEach(targetItem => {
                        targetItem.classList.toggle($class);

                    });
                } else {
                    item.classList.toggle($class);
                }
            }

            //trigger optional after toggle event
            item.dispatchEvent(ToggleEvent);


        }
    });


    //MOVING ITEMS
    //on Window resize we can move items to and from divs with data-moveto="the destination"
    //it will move there when the site reaches smaller than a size defaulted to 1030 or sett hat with data-moveat
    //the whole div, including the data att moveto moves back and forth
    let movedId = 0;

    function moveItems() {

        let windowWidth = window.innerWidth;
        let $moveItems = document.querySelectorAll('[data-moveto]');

        $moveItems.forEach(item => {
            let moveAt = item.getAttribute('data-moveat'),
                destination = document.querySelector(item.getAttribute('data-moveto')),
                source = item.getAttribute('data-movefrom');
            moveAt = moveAt ? moveAt : 1030;

            if( ! destination){
                return;
            }
            //if no data movefrom is found add one to parent so we can move items back in. now they go back and forth
            if (!source) {
                let sourceElem = item.parentElement.id;

                //if parent has no id attr, add one with a number so its unique
                if (!sourceElem) {
                    item.parentElement.setAttribute('id', 'move-' + movedId);
                    sourceElem = item.parentElement.id;
                }

                item.setAttribute('data-movefrom', '#' + sourceElem);
            }

            source = document.querySelector(item.getAttribute('data-movefrom'));

            //if the screen is smaller than moveAt (1030), move to destination
            if (windowWidth < moveAt) {
                if(item.hasAttribute('data-moveto-pos')){
                    destination.insertBefore(item, destination.children[item.getAttribute('data-moveto-pos')]);
                }else{
                    destination.appendChild(item);
                }
            } else {
                if(item.hasAttribute('data-movefrom-pos')){
                    source.insertBefore(item, source.children[item.getAttribute('data-movefrom-pos')]);
                }else{
                    source.appendChild(item);
                }
            }

            //show it
            item.classList.add('visible')
        });
    }

    window.addEventListener('resize', throttle(moveItems, 250));

    moveItems();



});
