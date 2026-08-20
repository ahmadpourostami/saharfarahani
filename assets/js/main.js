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

		function setupSlider(trackSelector, step) {
			document.querySelectorAll(trackSelector).forEach(function (track) {
				const wrapper = track.parentElement;
				const prev = wrapper.querySelector('.sf-slider-arrow--prev');
				const next = wrapper.querySelector('.sf-slider-arrow--next');
				const scroll = function (direction) {
					track.scrollBy({ left: direction * step(), behavior: 'smooth' });
				};
				if (prev) prev.addEventListener('click', function () { scroll(-1); });
				if (next) next.addEventListener('click', function () { scroll(1); });
			});
		}
		setupSlider('.sf-course-track', function () { return Math.max(260, document.querySelector('.sf-course-card')?.getBoundingClientRect().width || 280); });
		setupSlider('.sf-path-track', function () { return 560; });
	});
}());
