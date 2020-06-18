import {wrap} from "./setup"

//make iframe videos responsive
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('iframe[src*="youtube.com"], iframe[data-src*="youtube.com"], iframe[src*="vimeo.com"], iframe[data-src*="vimeo.com"]').forEach(iframe =>{
		if(! iframe.parentElement.classList.contains('videowrapper')){
			wrap(iframe).classList.add('videowrapper');
		}
	});
});
