(function () {
    const viewport = document.getElementById('carousel');
    const slides = Array.from(viewport.querySelectorAll('.slide'));
    const prev = document.querySelector('.carousel__btn.prev');
    const next = document.querySelector('.carousel__btn.next');
    const dotsWrap = document.getElementById('carousel-dots');

    let i = 0;
    let timer;

    function go(n) {
        i = (n + slides.length) % slides.length;

        slides.forEach((s, idx) => {
            s.classList.toggle('is-active', idx === i);
        });

        dotsWrap.querySelectorAll('button').forEach((d, idx) => {
            d.classList.toggle('active', idx === i);
        });
    }

    function auto() {
        timer = setInterval(() => go(i + 1), 8000);
    }

    function stop() {
        clearInterval(timer);
    }


    slides.forEach((_, idx) => {
        const b = document.createElement('button');
        b.dataset.index = idx;
        b.setAttribute('aria-label', `Go to slide ${idx + 1}`);

        b.addEventListener('click', () => {
            stop();
            go(idx);  
            auto();
        });

        dotsWrap.appendChild(b);
    });
// Make each slide clickable using its data-href
slides.forEach(slide => {
    slide.addEventListener('click', (e) => {
        if (e.target.closest('button')) return;

        const href = slide.dataset.href; // ALWAYS the clicked slide
        window.location.href = href;
    });
});

    prev.addEventListener('click', () => {
        stop();
        go(i - 1);
        auto();
    });

    next.addEventListener('click', () => {
        stop();
        go(i + 1);
        auto();
    });

    go(0);
    auto();
})();