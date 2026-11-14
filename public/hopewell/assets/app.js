/* Script ringan untuk layout pasien (Hopewell). Menggantikan script.js bawaan template yang khusus landing page. */
document.addEventListener('DOMContentLoaded', function () {
    if (window.AOS) AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });

    var preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', function () {
            setTimeout(function () { preloader.classList.add('hide'); }, 250);
        });
        setTimeout(function () { preloader.classList.add('hide'); }, 2000);
    }

    var nav = document.getElementById('mainNav');
    var btnTop = document.getElementById('scrollTop');
    var ring = btnTop ? btnTop.querySelector('.progress') : null;
    var circ = 2 * Math.PI * 23;
    if (ring) { ring.style.strokeDasharray = circ; ring.style.strokeDashoffset = circ; }

    function onScroll() {
        if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);
        if (btnTop) {
            var h = document.documentElement.scrollHeight - window.innerHeight;
            var pct = h > 0 ? window.scrollY / h : 0;
            if (ring) ring.style.strokeDashoffset = circ - pct * circ;
            btnTop.classList.toggle('show', window.scrollY > 400);
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    if (btnTop) btnTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

    /* counter animasi pada .counter[data-target] */
    var counted = new WeakSet();
    function animateCounter(el) {
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        var start = null;
        function step(ts) {
            if (!start) start = ts;
            var p = Math.min((ts - start) / 1500, 1);
            el.textContent = Math.floor((1 - Math.pow(1 - p, 3)) * target).toLocaleString('id-ID');
            if (p < 1) requestAnimationFrame(step); else el.textContent = target.toLocaleString('id-ID');
        }
        requestAnimationFrame(step);
    }
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting && !counted.has(e.target)) { counted.add(e.target); animateCounter(e.target); }
            });
        }, { threshold: 0.5 });
        document.querySelectorAll('.counter').forEach(function (c) { obs.observe(c); });
    } else {
        document.querySelectorAll('.counter').forEach(function (c) { c.textContent = c.getAttribute('data-target'); });
    }

    /* tutup menu mobile setelah klik tautan anchor */
    document.querySelectorAll('#navMenu .nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            var el = document.getElementById('navMenu');
            var inst = window.bootstrap && bootstrap.Collapse.getInstance(el);
            if (inst) inst.hide();
        });
    });

    document.querySelectorAll('.cause-progress-fill[data-progress]').forEach(function (el) {
        el.style.width = el.getAttribute('data-progress') + '%';
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!confirm(f.getAttribute('data-confirm'))) e.preventDefault();
        });
    });
});
