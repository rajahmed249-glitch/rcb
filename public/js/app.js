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

document.addEventListener('DOMContentLoaded', function () {
    var inputs = document.querySelectorAll('.search-form input[name="q"]');

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s == null ? '' : String(s);
        return d.innerHTML;
    }

    inputs.forEach(function (input) {
        var wrap = input.closest('.search-wrap') || input.closest('.mobile-search');
        if (!wrap) { return; }
        var panel = wrap.querySelector('.live-results');
        if (!panel) { return; }

        var timer = null;

        function render(data) {
            var html = '';

            if (data.players.length) {
                html += '<div class="live-group">Players</div>';
                data.players.forEach(function (p) {
                    var img = p.photo
                        ? '<img src="' + p.photo + '" alt="">'
                        : '<span class="live-fallback">' + esc(p.name.charAt(0)) + '</span>';
                    html += '<a class="live-item" href="' + p.url + '">' + img + '<span>' + esc(p.name) + '</span><small>' + esc(p.role) + '</small></a>';
                });
            }

            if (data.matches.length) {
                html += '<div class="live-group">Matches</div>';
                data.matches.forEach(function (m) {
                    html += '<a class="live-item" href="' + m.url + '"><span class="live-fallback">M</span><span>' + esc(m.label) + '</span><small>' + esc(m.date) + '</small></a>';
                });
            }

            if (data.news.length) {
                html += '<div class="live-group">News</div>';
                data.news.forEach(function (n) {
                    html += '<a class="live-item" href="' + n.url + '"><span class="live-fallback">N</span><span>' + esc(n.title) + '</span><small>' + esc(n.date) + '</small></a>';
                });
            }

            if (!html) {
                html = '<div class="live-empty">No results found</div>';
            }

            panel.innerHTML = html;
            panel.classList.add('open');
        }

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);
            if (q.length < 2) {
                panel.classList.remove('open');
                panel.innerHTML = '';
                return;
            }
            timer = setTimeout(function () {
                fetch('/search/live?q=' + encodeURIComponent(q), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (r) { return r.json(); })
                    .then(render)
                    .catch(function () {});
            }, 250);
        });

        input.addEventListener('focus', function () {
            if (panel.innerHTML) { panel.classList.add('open'); }
        });

        input.addEventListener('blur', function () {
            setTimeout(function () { panel.classList.remove('open'); }, 180);
        });

        input.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { panel.classList.remove('open'); }
        });
    });
});
