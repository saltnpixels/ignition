
//turn icons into svg if using the icons that come with theme folder
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.svg-icon').forEach(icon =>{
		icon.classList.remove('svg-icon');

		//classlist.value does not wokr in ie11. use getAttrbiute
		let iconClass = icon.getAttribute('class');

		//ie11 does not work well with nodes. needed to add as string. no createelementNS
		let iconString = `<svg class="icon ${iconClass}" role="img"><use href="#${iconClass}" xlink:href="#${iconClass}"></use></svg>`;

		// let iconsvg = document.createElementNS('http://www.w3.org/2000/svg','svg');
		// iconsvg.setAttribute('class', 'icon ' + iconClass);
		// iconsvg.setAttribute('role', 'img');
		//iconsvg.innerHTML = `<use href="#${iconClass}" xlink:href="#${iconClass}"></use>`;


		icon.insertAdjacentHTML('afterend', iconString);
		icon.remove();

	});
});
