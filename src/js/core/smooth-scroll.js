let scrollEvent = new Event('afterScroll', { bubbles: true }); //bubble allows for delegation on body

/**
 * runs when an anchor is clicked or the page loads with an anchor
 * the item we are scrolling to can have an offset
 * @param element
 */
function scrolltoHash (element) {
 if(element) {
    let offset = element.dataset.offset || 'start';

    //if the offset is a string 'start, center, or end'
    if (isNaN(parseInt(offset))) {
       element.scrollIntoView({ behavior: 'smooth', block: offset });
    } else {
       //from top scroll with offset
       let fromTop = window.pageYOffset + element.getBoundingClientRect().top + parseInt(offset);

       window.scroll({ behavior: 'smooth', top: fromTop });
    }

    //fire some more events
    setTimeout(function () {
       element.dispatchEvent(scrollEvent);
    }, 500);

 }

}

document.addEventListener('DOMContentLoaded', function () {

	if (location.hash) {
		scrolltoHash(document.querySelector(location.hash));
	}

	document.body.addEventListener('click', e => {
		let item = e.target.closest('a[href^="#"]');

		if (item) {
			let itemHash = item.getAttribute('href');

			if (itemHash !== '#' && itemHash !== '#0') {
				e.preventDefault();
				scrolltoHash(document.querySelector(itemHash));
			}
		}
	});

	document.addEventListener('afterScroll', function (e) {
		//run an event after scroll begins
	});

});





