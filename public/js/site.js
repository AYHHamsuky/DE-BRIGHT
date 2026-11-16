/* De Bright College: homepage slider, mobile menu, gallery lightbox */
(function () {
    'use strict';

    /* ---------- Sliding homepage banner ---------- */
    var slider = document.querySelector('[data-slider]');
    if (slider) {
        var track = slider.querySelector('.slides');
        var slides = Array.prototype.slice.call(track.children);
        var dotsBox = slider.querySelector('.slider-dots');
        var current = 0;
        var timer = null;
        var DELAY = 5000;
        var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (slides.length < 2) {
            slider.classList.add('single');
        } else {
            var dots = slides.map(function (_, i) {
                var b = document.createElement('button');
                b.type = 'button';
                b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                b.addEventListener('click', function () { go(i); restart(); });
                dotsBox.appendChild(b);
                return b;
            });

            // On phones, make the slider as tall as the current image so wide
            // banners are not lost in a tall box. Desktop keeps a fixed height.
            var phone = window.matchMedia('(max-width: 640px)');
            var fit = function () {
                var img = slides[current].querySelector('img');
                if (!phone.matches || !img || !img.naturalWidth) { track.style.height = ''; return; }
                var h = slider.clientWidth * img.naturalHeight / img.naturalWidth;
                track.style.height = Math.round(Math.min(Math.max(h, 180), 560)) + 'px';
            };
            slides.forEach(function (s, i) {
                var img = s.querySelector('img');
                if (img) { img.addEventListener('load', function () { if (i === current) { fit(); } }); }
            });
            window.addEventListener('resize', fit);

            var go = function (n) {
                current = (n + slides.length) % slides.length;
                fit();
                track.style.transform = 'translateX(-' + current * 100 + '%)';
                dots.forEach(function (d, i) { d.classList.toggle('is-active', i === current); });
                slides.forEach(function (s, i) { s.setAttribute('aria-hidden', i === current ? 'false' : 'true'); });
            };
            var next = function () { go(current + 1); };
            var prev = function () { go(current - 1); };
            var stop = function () { if (timer) { clearInterval(timer); timer = null; } };
            var start = function () { if (!reduceMotion && !timer) { timer = setInterval(next, DELAY); } };
            var restart = function () { stop(); start(); };

            slider.querySelector('.next').addEventListener('click', function () { next(); restart(); });
            slider.querySelector('.prev').addEventListener('click', function () { prev(); restart(); });

            slider.addEventListener('mouseenter', stop);
            slider.addEventListener('mouseleave', start);
            slider.addEventListener('focusin', stop);
            slider.addEventListener('focusout', start);
            slider.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowRight') { next(); restart(); }
                if (e.key === 'ArrowLeft') { prev(); restart(); }
            });

            // Swipe on phones
            var startX = null;
            slider.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; stop(); }, { passive: true });
            slider.addEventListener('touchend', function (e) {
                if (startX !== null) {
                    var dx = e.changedTouches[0].clientX - startX;
                    if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); }
                    startX = null;
                }
                start();
            }, { passive: true });

            // Pause when the tab is hidden
            document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });

            go(0);
            start();
        }
    }

    /* ---------- Mobile menu ---------- */
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('site-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    }

    /* ---------- Gallery lightbox ---------- */
    var links = document.querySelectorAll('[data-lightbox]');
    if (links.length) {
        var box = document.createElement('div');
        box.className = 'lightbox';
        box.innerHTML = '<img alt="">';
        document.body.appendChild(box);
        var big = box.querySelector('img');

        Array.prototype.forEach.call(links, function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                big.src = a.getAttribute('href');
                big.alt = a.getAttribute('data-title') || '';
                box.classList.add('open');
            });
        });
        box.addEventListener('click', function () { box.classList.remove('open'); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { box.classList.remove('open'); } });
    }
})();
