let headerLayouts = '';
let changeHeader = '';
let showMainEditor = '';
let sectionLayouts = '';
let mainEditor = '';

//hook into prepare to load after acf loads fields
// acf.addAction('prepare', function () {

	//auto collapse all sections if found
	// sectionLayouts = acf.getPostbox('acf-group_5aa6a924b02ff');
	// 	// if (typeof sectionLayouts !== 'undefined') {
	// 	// 	sectionLayouts = sectionLayouts.$el;
	// 	// 	sectionLayouts.find('.values .layout').addClass('-collapsed');
	// 	// }

	//move header layout above editor if found
	// headerLayouts = acf.getPostbox('acf-group_5a79fa1baf007');
	// if (typeof headerLayouts !== 'undefined') {
	// 	mainEditor = document.querySelector('#postdivrich');
	//
	// 	//move header section under title, above editor
	// 	if (headerLayouts.length && mainEditor !== null) {
	// 		let postBody = document.querySelector('#post-body-content');
	// 		postBody.insertBefore(headerLayouts[0], mainEditor);
	// 	}
	// }

	//when these checboxes are toggled scroll to the section shown
	// showMainEditor = acf.getField('field_5c5f7e2dcf40f').$el;
	// changeHeader = acf.getField('field_5c4b66a65ae2c').$el;
	//
	// if (showMainEditor.length) {
	// 	if (document.querySelector('.block-editor__container') !== null) {
	// 		showMainEditor.hide(); //hide if block editor is showing
	// 	}
	//
	// 	showMainEditor[0].addEventListener('change', highlight_section);
	// }
	//
	// if (changeHeader.length) {
	// 	changeHeader[0].addEventListener('change', highlight_section);
	// }

// });

//highlight and scroll to this section
function highlight_section (e) {

	let acfSelector = document.querySelector(e.target.dataset.ignClass);

	if (acfSelector !== null) {
		if (e.target.checked) {
			acfSelector.classList.add('highlight');
			$('html, body').animate({
				scrollTop: $(e.target.dataset.ignClass).offset().top - 50
			}, 200);
		} else {
			acfSelector.classList.remove('highlight');
		}
	}
}

//TOGGLE CLASSES BASED ON DATA-IGN-CLASSES
//must be text input or true/false checkbox

//on load set the classes
document.addEventListener('DOMContentLoaded', function () {
	//get the data attribute
	let igndataattributes = document.querySelectorAll('[data-ign-class]');
	igndataattributes.forEach(acfinput => {
		changeIgnClasses(acfinput);
	});

	addDraggableGrid();

});

//anytime this input changes, change the class
document.addEventListener('change', function (event) {
	if (event.target.matches('[data-ign-class]')) {
		changeIgnClasses(event.target);
	}
});

//the magic of ign data class
function changeIgnClasses (acfInput) {
	//to do anything there must be a value set
	let dataValue = acfInput.getAttribute('data-ign-class');

	//find the data attribute as a selector
	//first go up only to the nearest set of fields. if nothing is found query all the way up.
	let acfSelector = acfInput.closest('.acf-fields').querySelector(dataValue);
	if (acfSelector == null) {
		acfSelector = acfInput.closest(dataValue);
	}
	if (acfSelector == null) {
		acfSelector = document.querySelector(dataValue);
	}

	//cannot find then reutrn
	if (acfSelector == null) {
		return;
	}

	//if found selector, remove previous values if any
	if (acfInput.getAttribute('data-last-value')) {
		let lastValues = acfInput.getAttribute('data-last-value').split(' ');
		lastValues = lastValues.filter(Boolean); //remove any empty strings
		acfSelector.classList.remove(...lastValues);
	}

	//set class on the queried selector if there is a value or a check if checkbox
	if (acfInput.value !== ' ' && acfInput.value) {
		let classes = '';

		//tru/false type.
		if (acfInput.type === 'checkbox') {
			classes = acfInput.checked ? 'checked' : 'unchecked';
			acfSelector.classList.add(classes);
			acfInput.setAttribute('data-last-value', classes);
		} else {
			//text type
			classes = acfInput.value.split(' '); //if there is more than one class
			classes = classes.filter(Boolean); //get rid of any spaces, they are not classes.
			acfSelector.classList.add(...classes);
			acfInput.setAttribute('data-last-value', acfInput.value);
		}

	}

}

//draggable grid!! Now users can drag grid boxes in admin area instead of adding classes for it
//this function added when dom is loaded using above listener
function addDraggableGrid () {
	//get top most acf-fields which holds everything
	let acfFields = document.querySelector('#poststuff');

	//start dragger at 0
	let pageX = 0;

	//selectors based on what is dragged
	let acfSelector = '';
	let mceIframes = document.querySelectorAll('.mce-container iframe');

	//when anything inside fields is clicked and matches the handle-remove and then is dragged
	acfFields.addEventListener('mousedown', function (e) {
		if (e.target.matches('.grid-class .acf-row-handle.remove')) {
			//console.log('mouse down');
			//set coordinates
			pageX = e.pageX;
			//get this field item
			acfSelector = e.target.previousElementSibling.closest('.acf-row');

			//on drag
			//frames needed for fix so when you drag over it doesnt break.
			mceIframes = document.querySelectorAll('.mce-container iframe');
			window.addEventListener('mousemove', dragGrid, true);
		}
	});

	//anywhere where mouse is lifted
	window.addEventListener('mouseup', function () {
		//remove drag
		window.removeEventListener('mousemove', dragGrid, true);
		//fix iframes
		mceIframes.forEach(item => {
			item.style.pointerEvents = 'auto';
		});

		acfFields.style.cursor = 'auto';
	});

	//allow dragging!
	function dragGrid (e) {
		e.preventDefault();
		e.stopPropagation();

		//remove iframe issues by disabling click for a while
		mceIframes.forEach(item => {
			item.style.pointerEvents = 'none';
		});

		//make mouse look like resizing
		acfFields.style.cursor = 'ew-resize';

		//set card-grid to grid automatically
		$gridType = acfSelector.closest('.acf-fields').querySelector('[data-ign-class=".grid-class"]');
		if ($gridType.value.includes('card-grid')) {
			$gridType.value = $gridType.value.replace('card-grid', 'grid');
			changeIgnClasses($gridType);
		}

		if (!$gridType.value.includes('grid')) {
			$gridType.value += ' grid';
			$gridType.value = $gridType.value.trim();
			changeIgnClasses($gridType);
		}

		//set the grid values and update
		let gridInput = acfSelector.querySelector('[data-ign-class]');
		let oldValue = gridInput.value;
		let span = 6;

		if (oldValue.includes('span')) {
			span = /span-(\d+)/g.exec(oldValue)[1];
			span = parseInt(span);
			oldValue = oldValue.replace(/span-\d+/g, ''); //remove span from value
		}

		if (pageX !== 0 && e.pageX > pageX + 100) {
			//bigger span
			if (span < 12) {
				span++;
			}
			pageX += 100;
		}

		if (pageX !== 0 && e.pageX < pageX - 100) {
			//smaller span
			if (span > 1) {
				span--;
			}
			pageX -= 100;
		}

		//set actual value and update
		let newValue = 'span-' + span + ' ' + oldValue;
		newValue = newValue.trim();
		gridInput.value = newValue;
		changeIgnClasses(gridInput);
	}
}
