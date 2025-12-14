App.register('modals', initModals);
/* ===============================
   MODALS
=============================== */
function initModals() {

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