let headerLayouts = '';
let changeHeader = '';
let showMainEditor = '';

//hook into prepare to load after acf loads fields
acf.addAction('prepare', function () {

    //move header layout above editor
    headerLayouts = acf.getPostbox('acf-group_5a79fa1baf007').$el;
    let mainEditor = document.querySelector('#postdivrich');

    //move header section under title, above editor
    if (headerLayouts.length && mainEditor !== null) {
        let postBody = document.querySelector('#post-body-content');
        postBody.insertBefore(headerLayouts[0], mainEditor);
    }

    //when these checboxes are toggled scroll to the section shown
    showMainEditor = acf.getField('field_5c5f7e2dcf40f').$el;
    changeHeader = acf.getField('field_5c4b66a65ae2c').$el;

    if (showMainEditor.length) {
        if(document.querySelector('.block-editor__container') !== null){
            showMainEditor.hide(); //hide if block editor is showing
        }

        showMainEditor[0].addEventListener('change', highlight_section);
    }

    if (changeHeader.length) {
        changeHeader[0].addEventListener('change', highlight_section);
    }

});

//highlight and scroll to this section
function highlight_section(e) {

    let acfSelector = document.querySelector(e.target.dataset.ignClass);

    if(acfSelector !== null) {
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
document.addEventListener("DOMContentLoaded", function () {
    //get the data attribute
    let igndataattributes = document.querySelectorAll('[data-ign-class]');
    igndataattributes.forEach(acfinput => {
        changeIgnClasses(acfinput);
    });
});

//anytime this input changes, change the class
document.addEventListener("change", function (event) {

    if (event.target.matches('[data-ign-class]')) {
        changeIgnClasses(event.target);
    }
});

//the magic of ign data class
function changeIgnClasses(acfInput) {
    //to do anything there must be a value set
    let dataValue = acfInput.getAttribute('data-ign-class');


    //find the data attribute as a selector
    //first go up only to the nearest set of fields. if nothing is found query all the way up.
    let acfSelector = acfInput.closest(".acf-fields").querySelector(dataValue);
    if (acfSelector == null) {
        acfSelector = acfInput.closest(dataValue);
    }
    if (acfSelector == null) {
        acfSelector = document.querySelector(dataValue);
    }

    //cannot find then reutrn
    if(acfSelector == null){
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
            classes = acfInput.value.split(" "); //if there is more than one class
            classes = classes.filter(Boolean); //get rid of any spaces, they are not classes.
            acfSelector.classList.add(...classes);
            acfInput.setAttribute('data-last-value', acfInput.value);
        }

    }


}