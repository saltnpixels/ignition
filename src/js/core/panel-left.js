document.addEventListener('DOMContentLoaded', function () {

   //app-menu ability for the top menu
   let body = document.body;
   let menuToggle = document.querySelector('.panel-left-toggle');
   let topNav = document.querySelector('.site-top');
   let page = document.querySelector('#page');

   //first move the button into site-top if app-menu is being used cause we dont want it on the outside
   if (body.classList.contains('app-menu')) {
      topNav.append(menuToggle);
   }

   function closeAppMenu (e) {
      e.preventDefault();
      menuToggle.click();
   }

   //when button is opened we will lock the body so there is no scrolling and then open the page
   if (menuToggle) {
      menuToggle.addEventListener('afterToggle', e => {
         //if button has been toggled on
         if (menuToggle.classList.contains('toggled-on')) {
            if (body.classList.contains('app-menu')) {
               body.classList.add('mobile-menu-body-lock');
            }

            //clicking anywhere outside the menu will close it
            document.querySelector('.site-content').addEventListener('click', closeAppMenu, { once: true });

         } else {

            document.querySelector('.site-content').removeEventListener('click', closeAppMenu);

            if (body.classList.contains('app-menu')) {
               page.addEventListener('transitionend', function () {
                  body.classList.remove('mobile-menu-body-lock'); //only remove toggle and hide menu once page holder finishes its transition to cover it.
               }, { once: true });
            } else {
               //body.classList.remove('mobile-menu-body-lock');
            }
         }

      });
   }

})