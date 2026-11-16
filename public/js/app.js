document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('menuToggle');
    var nav = document.querySelector('.nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });
    }

    var slider = document.getElementById('heroSlider');
    if (slider) {
        var slides = slider.querySelectorAll('.hero-slide');
        var dots = slider.querySelectorAll('.hero-dot');
        var current = 0;
        var timer;

        function show(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = (index + slides.length) % slides.length;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function play() {
            timer = setInterval(function () { show(current + 1); }, 5000);
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                clearInterval(timer);
                show(parseInt(this.getAttribute('data-slide'), 10));
                play();
            });
        });

        play();
    }

    var playerTrack = document.getElementById('playerTrack');
    var playerPrev = document.getElementById('playerPrev');
    var playerNext = document.getElementById('playerNext');

    if (playerTrack && playerPrev && playerNext) {
        var step = 300;
        playerPrev.addEventListener('click', function () {
            playerTrack.scrollBy({ left: -step, behavior: 'smooth' });
        });
        playerNext.addEventListener('click', function () {
            playerTrack.scrollBy({ left: step, behavior: 'smooth' });
        });

        var autoScroll = null;

        function startAutoScroll() {
            if (autoScroll) return;
            autoScroll = setInterval(function () {
                if (playerTrack.scrollLeft + playerTrack.clientWidth >= playerTrack.scrollWidth - 4) {
                    playerTrack.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    playerTrack.scrollBy({ left: step, behavior: 'smooth' });
                }
            }, 4000);
        }

        function stopAutoScroll() {
            clearInterval(autoScroll);
            autoScroll = null;
        }

        startAutoScroll();

        playerTrack.addEventListener('mouseenter', stopAutoScroll);
        playerTrack.addEventListener('mouseleave', startAutoScroll);
        playerPrev.addEventListener('click', function () {
            stopAutoScroll();
            startAutoScroll();
        });
        playerNext.addEventListener('click', function () {
            stopAutoScroll();
            startAutoScroll();
        });
    }

    var backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 200) {
                backToTop.classList.remove('hidden');
            } else {
                backToTop.classList.add('hidden');
            }
        });

        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var tabButtons = document.querySelectorAll('.sb-tab-btn');
    if (tabButtons.length) {
        tabButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tabButtons.forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.sb-tab-content').forEach(function (c) { c.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('sb-tab-' + btn.dataset.tab);
                if (panel) { panel.classList.add('active'); }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var tprofTabs = document.querySelectorAll('.tprof-tab');
    if (tprofTabs.length) {
        tprofTabs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                tprofTabs.forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.tprof-panel').forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('tp-tab-' + btn.dataset.tab);
                if (panel) { panel.classList.add('active'); }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var searchToggle = document.getElementById('searchToggle');
    var mobileSearch = document.getElementById('mobileSearch');
    if (searchToggle && mobileSearch) {
        searchToggle.addEventListener('click', function () {
            mobileSearch.classList.toggle('open');
            if (mobileSearch.classList.contains('open')) {
                var input = mobileSearch.querySelector('input');
                if (input) { input.focus(); }
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var squadBtns = document.querySelectorAll('.squad-subtab-btn');
    if (squadBtns.length) {
        squadBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                squadBtns.forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.squad-content').forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('squad-' + btn.dataset.squad);
                if (panel) { panel.classList.add('active'); }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var subtabs = document.querySelectorAll('.score-subtab-btn');
    if (subtabs.length) {
        subtabs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                subtabs.forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.scorecard-innings').forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                var panel = document.getElementById('innings-' + btn.dataset.innings);
                if (panel) { panel.classList.add('active'); }
            });
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-href]').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('a')) { return; }
            window.location.href = card.getAttribute('data-href');
        });
    });
});
