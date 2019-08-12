let menuIcon = "";
//menuIcon experimental
if (typeof (tinyMCE) != "undefined") {
	tinymce.on("addeditor", ignIconEvent);


} else {
	document.addEventListener("DOMContentLoaded", ignIconEvent);
}

function ignIconEvent() {

	document.querySelectorAll('#wp-admin-bar-icons-button-default .ab-item').forEach(function (iconMenu) {
		iconMenu.addEventListener('mousedown', function (e) {

			e.preventDefault();

			menuIcon = e.target.querySelector('svg');
			//fix tinymce removing svg's
			menuIcon.setAttribute('name', 'svg');
			menuIcon = menuIcon.outerHTML;


			//place it in active tinymce editor

			if(document.activeElement.tagName == 'INPUT' || document.activeElement.tagName == 'TEXTAREA'){

				insertAtCursor(document.activeElement, menuIcon);
			}else if(document.activeElement.getAttribute('contenteditable')){
				let pos = getCaretPosition(document.activeElement);
				document.activeElement.innerHTML = [document.activeElement.innerHTML.slice(0, pos), menuIcon, document.activeElement.innerHTML.slice(pos)].join('')
			}
			else  {

				tinyMCE.activeEditor.execCommand('mceInsertRawHTML', false, menuIcon);
			}

		});
	});
}


function insertAtCursor(myField, myValue) {
	//IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
	}
	//MOZILLA and others
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
			+ myValue
			+ myField.value.substring(endPos, myField.value.length);
	} else {
		myField.value += myValue;
	}
}


function getCaretPosition(editableDiv) {
	var caretPos = 0,
		sel, range;
	if (window.getSelection) {
		sel = window.getSelection();
		if (sel.rangeCount) {
			range = sel.getRangeAt(0);
			if (range.commonAncestorContainer.parentNode == editableDiv) {
				caretPos = range.endOffset;
			}
		}
	} else if (document.selection && document.selection.createRange) {
		range = document.selection.createRange();
		if (range.parentElement() == editableDiv) {
			var tempEl = document.createElement("span");
			editableDiv.insertBefore(tempEl, editableDiv.firstChild);
			var tempRange = range.duplicate();
			tempRange.moveToElementText(tempEl);
			tempRange.setEndPoint("EndToEnd", range);
			caretPos = tempRange.text.length;
		}
	}
	return caretPos;
}
