(function () {
	'use strict';
	document.addEventListener('DOMContentLoaded', function () {
		const toggle = document.querySelector('.sf-menu-toggle');
		const nav = document.querySelector('.sf-nav');
		if (toggle && nav) {
			toggle.addEventListener('click', function () {
				const expanded = toggle.getAttribute('aria-expanded') === 'true';
				toggle.setAttribute('aria-expanded', String(!expanded));
				nav.classList.toggle('is-open', !expanded);
				document.body.classList.toggle('sf-menu-open', !expanded);
			});
		}
		document.querySelectorAll('.sf-nav a').forEach(function (link) {
			link.addEventListener('click', function () {
				if (toggle && nav && window.innerWidth < 960) {
					toggle.setAttribute('aria-expanded', 'false');
					nav.classList.remove('is-open');
					document.body.classList.remove('sf-menu-open');
				}
			});
		});
	});
}());
