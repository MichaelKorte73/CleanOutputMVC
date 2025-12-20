document.addEventListener("DOMContentLoaded", () => {
    setupModals();
    //setupFontSizeToggle();
   // setupHighContrastToggle();
//  setupThemeButton();
  //  setupMenuButton();
  //  setupTopbarPin();
  //  initParallax();
});

/* ===============================
   MODALS
=============================== */
function setupModals() {
    const openLinks = document.querySelectorAll("[data-modal]");

    openLinks.forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();
            openModal(document.getElementById(link.dataset.modal));
        });
    });

    document.querySelectorAll(".modal").forEach(modal => {
        modal.querySelectorAll("[data-close]").forEach(btn =>
            btn.addEventListener("click", () => closeModal(modal))
        );

        modal.addEventListener("click", e => {
            if (e.target.hasAttribute("data-close")) closeModal(modal);
        });
    });

    document.addEventListener("keydown", e => {
        if (e.key === "Escape") {
            const open = document.querySelector(".modal:not([hidden])");
            if (open) closeModal(open);
        }
    });
}

function openModal(modal) {
    modal.hidden = false;
    document.body.classList.add("modal-open");

    const dialog = modal.querySelector(".modal-dialog");
    dialog.setAttribute("tabindex", "-1");
    dialog.focus();
}

function closeModal(modal) {
    modal.hidden = true;
    document.body.classList.remove("modal-open");
}

/* ===============================
   FONT SIZE
=============================== */
function setupFontSizeToggle() {
    const btn = document.querySelector('[aria-label="Schriftgrößen-Einstellungen"]');
    if (!btn) return;

    const STATES = ["", "fs-large", "fs-xlarge"];
    const body = document.body;

    btn.addEventListener("click", () => {
        let next = ((+body.dataset.fsState || 0) + 1) % STATES.length;
        body.classList.remove("fs-large", "fs-xlarge");
        if (STATES[next]) body.classList.add(STATES[next]);
        body.dataset.fsState = next;
    });
}

/* ===============================
   HIGH CONTRAST
=============================== */
function setupHighContrastToggle() {
    const btn = document.querySelector('[aria-label="Kontrastmodus aktivieren"]');
    if (!btn) return;
    btn.addEventListener("click", () => document.body.classList.toggle("hc-mode"));
}

/* ===============================
   THEME (später)
=============================== */
function setupThemeButton() {
    const btn = document.querySelector('[aria-label="Farbschema ändern"]');
    if (!btn) return;
    btn.addEventListener("click", () => alert("Themes kommen später ✨"));
}

/* ===============================
   MENU (später)
=============================== */
function setupMenuButton() {
    const btn = document.querySelector('[aria-label="Menü öffnen"]');
    if (!btn) return;
    btn.addEventListener("click", () => alert("Navigation folgt 🚀"));
}

/* ===============================
   TOPBAR PIN
=============================== */
function setupTopbarPin() {
    const btn = document.querySelector('[aria-label="Top-Bar anpinnen"]');
    if (!btn) return;
    btn.addEventListener("click", () =>
        document.body.classList.toggle("topbar-fixed")
    );
}

/* ===============================
   PARALLAX
=============================== */
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