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
