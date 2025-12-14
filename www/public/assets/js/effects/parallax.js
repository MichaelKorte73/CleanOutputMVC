(function (window, document) {
    'use strict';

    function initParallax() {
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    const sections = document.querySelectorAll(".parallax-bg");
    if (!sections.length) return;

    const update = () => {
        const vh = window.innerHeight;
        sections.forEach(sec => {
            const img = sec.querySelector(".parallax-img");
            if (!img) return;

            const rect = sec.getBoundingClientRect();
            const speed = parseFloat(sec.dataset.parallaxSpeed || 0.3);

            if (rect.bottom > 0 && rect.top < vh) {
                img.style.transform =
                    `translate3d(0, ${(vh - rect.top) * speed * -0.2}px, 0)`;
            }
        });
    };

    addEventListener("scroll", update, { passive: true });
    addEventListener("load", update);
    update();
}

    App.onReady(initParallax);

})(window, document);