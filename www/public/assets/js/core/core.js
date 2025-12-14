/* =========================================================
   Clean Output MVC – JS Core (FINAL)
   ========================================================= */

(function (window, document) {
    'use strict';

    // -------------------------------------------------
    // Bootstrap & Guard
    // -------------------------------------------------
    const App = window.App = window.App || {};

    if (App.__coreLoaded) {
        console.warn('App core loaded twice');
        return;
    }
    App.__coreLoaded = true;

    // -------------------------------------------------
    // Internal state
    // -------------------------------------------------
    App._domReady   = document.readyState !== 'loading';
    App._appReady   = false;
    App._modules    = new Set();   // debug only
    App._readyQueue = [];

    // -------------------------------------------------
    // DOM ready handling
    // -------------------------------------------------
    if (!App._domReady) {
        document.addEventListener('DOMContentLoaded', () => {
            App._domReady = true;
            App._checkReady();
        });
    }

    // -------------------------------------------------
    // Internal helpers (debug only)
    // -------------------------------------------------
    App._require = function (name) {
        App._modules.add(name);
    };

    App._moduleReady = function (name) {
        App._modules.delete(name);
    };

    App._checkReady = function () {
        if (App._appReady) return;
        if (!App._domReady) return;

        App._appReady = true;

        App._readyQueue.forEach(fn => {
            try {
                fn();
            } catch (e) {
                console.error('App.runWhenReady error', e);
            }
        });

        App._readyQueue.length = 0;
    };

    // -------------------------------------------------
    // Public API
    // -------------------------------------------------

    /**
     * Register a module with a single init callback.
     * Core handles lifecycle and timing.
     */
    App.register = function (name, initFn) {
        if (!name || typeof initFn !== 'function') {
            console.error('App.register(name, initFn) required');
            return;
        }

        // debug only
        App._require(name);

        App.runWhenReady(() => {
            try {
                initFn();
            } catch (e) {
                console.error('Module init failed:', name, e);
            } finally {
                // debug only
                App._moduleReady(name);
            }
        });
    };

    /**
     * Execute callback when app lifecycle is ready.
     * Safe for late-loaded scripts.
     */
    App.runWhenReady = function (fn) {
        if (App._appReady) {
            fn();
        } else {
            App._readyQueue.push(fn);
        }
    };

    // Alias (DX)
    App.onReady = App.runWhenReady;

    // -------------------------------------------------
    // Optional tiny DOM helpers (non-magical)
    // -------------------------------------------------
    App.q = (sel, root) =>
        (root || document).querySelector(sel);

    App.qa = (sel, root) =>
        Array.from((root || document).querySelectorAll(sel));

    App.on = (el, evt, fn, opts) =>
        (el || document).addEventListener(evt, fn, opts || false);

})(window, document);