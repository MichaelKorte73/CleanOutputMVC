/**
 * Clean Output MVC
 *
 * Modals Module
 *
 * UI-Modul zur Steuerung von Modals.
 *
 * Eigenschaften:
 * - Initialisierung über App.register
 * - Kein eigenes Lifecycle-Handling
 * - Progressive Enhancement
 *
 * ❗ WICHTIG:
 * - Erwartet korrektes Markup
 * - Kein Rendering, nur Verhalten
 */

function initModals() {

    const openLinks = document.querySelectorAll('[data-modal]');

    openLinks.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            const modal = document.getElementById(link.dataset.modal);
            if (modal) {
                openModal(modal);
            }
        });
    });

    document.querySelectorAll('.modal').forEach(modal => {
        modal.querySelectorAll('[data-close]').forEach(btn =>
            btn.addEventListener('click', () => closeModal(modal))
        );

        modal.addEventListener('click', e => {
            if (e.target.hasAttribute('data-close')) {
                closeModal(modal);
            }
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            const open = document.querySelector('.modal:not([hidden])');
            if (open) {
                closeModal(open);
            }
        }
    });
}

function openModal(modal) {
    if (!modal) return;

    modal.hidden = false;
    document.body.classList.add('modal-open');

    const dialog = modal.querySelector('.modal-dialog');
    if (dialog) {
        dialog.setAttribute('tabindex', '-1');
        dialog.focus();
    }
}

function closeModal(modal) {
    if (!modal) return;

    modal.hidden = true;
    document.body.classList.remove('modal-open');
}

// Registrierung beim JS-Core
App.register('modals', initModals);