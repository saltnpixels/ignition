document.addEventListener('DOMContentLoaded', function () {

	let sidebar = document.querySelector('#secondary');
	if(sidebar !== null){
		sidebar.innerHTML = sidebar.innerHTML.trim(); //if moving stuff in and out its good to remove extra space so :empty works
		let sidebarTemplate = document.querySelector('.sidebar-template');
		if(sidebarTemplate !== null && sidebarTemplate.classList.contains('header-above')){

			document.querySelectorAll('.entry-header, .page-header').forEach(header=>{
				sidebarTemplate.parentElement.prepend(header);
			});

			sidebarTemplate.classList.add('active');
		}
	}

});




