document.addEventListener('DOMContentLoaded', function () {


	//move the header above the article when header-above is found
	const headerAbove = document.querySelector('.header-above');
	if (headerAbove!==null) {
		document.querySelectorAll('.entry-header, .page-header').forEach(header => {
			headerAbove.parentElement.prepend(header);
			header.classList.add('header-moved'); //might be useful for someone
		});
	}

	//when a secondary is used, a sidebar is shown, on load we do a few things to smooth the transition of the header
	let sidebar = document.querySelector('#secondary');
	if (sidebar!==null) {
		sidebar.innerHTML = sidebar.innerHTML.trim(); //if moving stuff in and out its good to remove extra space so :empty works
		let sidebarTemplate = document.querySelector('.sidebar-template');
		sidebarTemplate.classList.add('active');
	}

});




