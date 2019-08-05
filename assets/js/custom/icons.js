//turn icons into svg if using the icons that come with theme folder

document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.svg-icon').forEach(icon =>{
		icon.classList.remove('svg-icon');

		let iconsvg = document.createElementNS('http://www.w3.org/2000/svg','svg');
		iconsvg.classList.add('icon', ...icon.classList);
		iconsvg.setAttribute('role', 'img');

		iconsvg.innerHTML = `<use href="#icon-home" xlink:href="#icon-home"></use>`;
		icon.parentNode.replaceChild(iconsvg, icon);

	});
});
