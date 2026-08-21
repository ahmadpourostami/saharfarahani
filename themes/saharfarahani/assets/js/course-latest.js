(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        if (!document.body.classList.contains('sf-front-page') || typeof sfLatestCourses === 'undefined') {
            return;
        }

        var empty = document.querySelector('.sf-latest .sf-empty');
        if (!empty) return;

        var count = parseInt(sfLatestCourses.count, 10) || 5;
        var form = new FormData();
        form.append('action', 'sf_latest_courses');
        form.append('nonce', sfLatestCourses.nonce);
        form.append('count', String(count));

        fetch(sfLatestCourses.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: form
        })
        .then(function (response) { return response.json(); })
        .then(function (result) {
            if (!result || !result.success || !result.data || !result.data.html) return;

            var slider = document.createElement('div');
            slider.className = 'sf-course-slider';
            slider.innerHTML = '<button class="sf-slider-arrow sf-slider-arrow--prev" type="button" aria-label="قبلی">‹</button>' +
                '<div class="sf-course-track">' + result.data.html + '</div>' +
                '<button class="sf-slider-arrow sf-slider-arrow--next" type="button" aria-label="بعدی">›</button>';

            empty.replaceWith(slider);

            var track = slider.querySelector('.sf-course-track');
            var prev = slider.querySelector('.sf-slider-arrow--prev');
            var next = slider.querySelector('.sf-slider-arrow--next');
            var card = slider.querySelector('.sf-course-card');
            var step = Math.max(260, card ? card.getBoundingClientRect().width : 280);

            if (prev) prev.addEventListener('click', function () {
                track.scrollBy({ left: -step, behavior: 'smooth' });
            });
            if (next) next.addEventListener('click', function () {
                track.scrollBy({ left: step, behavior: 'smooth' });
            });
        })
        .catch(function () {
            // Keep the server-rendered empty state if the fallback request fails.
        });
    });
}());
