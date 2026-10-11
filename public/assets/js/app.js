/**
 * Asenim — comportements d'interface partagés.
 *  - Toasts (messages flash + API window.UI.toast)
 *  - Modale de confirmation unique ([data-confirm])
 *  - Listes à recherche/pagination dynamiques ([data-live-search], [data-list-region])
 *  - Filtre client ([data-filter-input])
 *  - États de chargement des formulaires
 *  - Champs fichier (dropzone, aperçu d'avatar), affichage du mot de passe
 */
(function () {
    'use strict';

    const UI = (window.UI = window.UI || {});

    /* ------------------------------------------------------------------ Toasts */
    const ICONS = { success: 'bx-check-circle', error: 'bx-error-circle', info: 'bx-info-circle' };

    function toastStack() {
        let stack = document.querySelector('.toast-stack');
        if (!stack) {
            stack = document.createElement('div');
            stack.className = 'toast-stack';
            stack.setAttribute('role', 'region');
            stack.setAttribute('aria-label', 'Notifications');
            document.body.appendChild(stack);
        }
        return stack;
    }

    UI.toast = function (type, message, title) {
        if (!message) return;
        type = ICONS[type] ? type : 'info';
        const el = document.createElement('div');
        el.className = 'ui-toast is-' + type;
        el.setAttribute('role', type === 'error' ? 'alert' : 'status');

        const icon = document.createElement('i');
        icon.className = 'bx ' + ICONS[type] + ' ui-toast-icon';
        const body = document.createElement('div');
        body.className = 'ui-toast-body';
        if (title) {
            const t = document.createElement('span');
            t.className = 'ui-toast-title';
            t.textContent = title;
            body.appendChild(t);
        }
        body.appendChild(document.createTextNode(message));
        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'ui-toast-close';
        close.setAttribute('aria-label', 'Fermer');
        close.innerHTML = '<i class="bx bx-x"></i>';

        el.append(icon, body, close);
        toastStack().appendChild(el);

        const dismiss = () => {
            el.classList.add('is-leaving');
            setTimeout(() => el.remove(), 200);
        };
        close.addEventListener('click', dismiss);
        setTimeout(dismiss, type === 'error' ? 7000 : 4500);
    };

    function flushFlash() {
        const node = document.getElementById('ui-flash');
        if (!node) return;
        try {
            const data = JSON.parse(node.textContent || '{}');
            if (data.success) UI.toast('success', data.success);
            if (data.status) UI.toast('success', data.status);
            if (data.error) UI.toast('error', data.error);
        } catch (e) { /* données flash invalides : on ignore */ }
    }

    /* ------------------------------------------------------- Modale de confirmation */
    function initConfirm() {
        const modalEl = document.getElementById('confirmModal');
        if (!modalEl || !window.bootstrap) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        const form = modalEl.querySelector('form');
        const methodInput = form.querySelector('input[name="_method"]');
        const titleEl = modalEl.querySelector('[data-confirm-title]');
        const textEl = modalEl.querySelector('[data-confirm-text]');
        const iconWrap = modalEl.querySelector('[data-confirm-icon]');
        const submit = form.querySelector('button[type="submit"]');

        document.addEventListener('click', function (e) {
            const trigger = e.target.closest('[data-confirm]');
            if (!trigger) return;
            e.preventDefault();

            const variant = trigger.dataset.variant || 'danger';
            const method = (trigger.dataset.method || 'POST').toUpperCase();

            form.action = trigger.dataset.action;
            methodInput.value = method;
            titleEl.textContent = trigger.dataset.title || 'Confirmer l’action';
            textEl.textContent = trigger.dataset.confirm || 'Voulez-vous vraiment continuer ?';
            iconWrap.className = 'confirm-icon is-' + variant;
            iconWrap.innerHTML = '<i class="bx ' + (trigger.dataset.icon || 'bx-error') + '"></i>';
            submit.className = 'btn btn-' + (variant === 'primary' ? 'primary' : variant);
            submit.textContent = trigger.dataset.confirmLabel || 'Confirmer';
            submit.classList.remove('is-loading');
            submit.disabled = false;
            modal.show();
        });

        modalEl.addEventListener('shown.bs.modal', () => submit.focus());
    }

    /* ---------------------------------------------------------- Listes dynamiques */
    function initLiveLists() {
        let controller = null;

        function regionFor(el) {
            const sel = el && el.dataset ? el.dataset.target : null;
            return document.querySelector(sel || '[data-list-region]');
        }

        function load(url, region, searchField) {
            if (!region) { window.location.href = url; return; }
            if (controller) controller.abort();
            controller = new AbortController();
            region.setAttribute('aria-busy', 'true');
            if (searchField) searchField.classList.add('is-busy');

            fetch(url, { headers: { Accept: 'text/html' }, signal: controller.signal, credentials: 'same-origin' })
                .then(r => {
                    if (r.redirected && new URL(r.url).pathname !== new URL(url, location.href).pathname) {
                        window.location.href = r.url;
                        throw new DOMException('redirect', 'AbortError');
                    }
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.text();
                })
                .then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const selector = region.id ? '#' + region.id : '[data-list-region]';
                    const fresh = doc.querySelector(selector);
                    if (!fresh) throw new Error('Région introuvable');
                    region.innerHTML = fresh.innerHTML;
                    history.replaceState(null, '', url);
                })
                .catch(err => {
                    if (err.name !== 'AbortError') UI.toast('error', 'Impossible de charger les résultats. Vérifiez votre connexion puis réessayez.');
                })
                .finally(() => {
                    region.removeAttribute('aria-busy');
                    if (searchField) searchField.classList.remove('is-busy');
                });
        }

        function urlFromForm(form) {
            const url = new URL(form.action || location.href, location.href);
            const params = new URLSearchParams(location.search);
            new FormData(form).forEach((v, k) => {
                if (v === '' || v === null) params.delete(k); else params.set(k, v);
            });
            params.delete('page');
            url.search = params.toString();
            return url.toString();
        }

        document.querySelectorAll('form[data-live-search]').forEach(form => {
            let timer = null;
            const field = form.querySelector('.search-field');
            const run = () => load(urlFromForm(form), regionFor(form), field);
            form.addEventListener('input', e => {
                if (e.target.matches('input[type="search"], input[type="text"]')) {
                    clearTimeout(timer);
                    timer = setTimeout(run, 300);
                }
            });
            form.addEventListener('change', e => { if (e.target.matches('select')) run(); });
            form.addEventListener('submit', e => { e.preventDefault(); clearTimeout(timer); run(); });
        });

        document.addEventListener('click', e => {
            const link = e.target.closest('a[data-list-link]');
            if (!link || e.metaKey || e.ctrlKey || e.shiftKey) return;
            const region = link.closest('[data-list-region]');
            if (!region) return;
            e.preventDefault();
            load(link.href, region);
        });

        document.addEventListener('change', e => {
            const select = e.target.closest('select[data-list-param]');
            if (!select) return;
            const region = select.closest('[data-list-region]');
            const url = new URL(location.href);
            url.searchParams.set(select.dataset.listParam, select.value);
            url.searchParams.delete('page');
            load(url.toString(), region);
        });
    }

    /* ------------------------------------------------------------- Filtre client */
    function initClientFilter() {
        document.querySelectorAll('[data-filter-input]').forEach(input => {
            const scope = document.querySelector(input.dataset.filterInput) || document;
            const apply = () => {
                const q = input.value.trim().toLocaleLowerCase('fr');
                scope.querySelectorAll('[data-filter-group]').forEach(group => {
                    let visible = 0;
                    group.querySelectorAll('[data-filter-item]').forEach(item => {
                        const match = !q || (item.dataset.filterItem || item.textContent).toLocaleLowerCase('fr').includes(q);
                        item.hidden = !match;
                        if (match) visible++;
                    });
                    const empty = group.querySelector('[data-filter-empty]');
                    if (empty) empty.hidden = visible !== 0 || !q;
                });
            };
            input.addEventListener('input', apply);
        });
    }

    /* --------------------------------------------------------- Formulaires */
    function initForms() {
        document.addEventListener('submit', e => {
            const form = e.target;
            if (e.defaultPrevented || form.hasAttribute('data-no-loading') || form.method.toLowerCase() === 'get') return;
            if (form.dataset.submitting) { e.preventDefault(); return; }
            form.dataset.submitting = '1';
            const btn = e.submitter || form.querySelector('button[type="submit"]');
            if (btn) {
                // Laisse le navigateur prendre en compte le bouton avant de le désactiver
                setTimeout(() => { btn.classList.add('is-loading'); btn.disabled = true; }, 0);
            }
        });

        // Bascule d'affichage du mot de passe
        document.addEventListener('click', e => {
            const toggle = e.target.closest('[data-toggle-password]');
            if (!toggle) return;
            const input = toggle.parentElement.querySelector('input');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            toggle.innerHTML = '<i class="bx ' + (show ? 'bx-hide' : 'bx-show') + '"></i>';
        });

        // Dropzones
        document.querySelectorAll('.dropzone').forEach(zone => {
            const input = zone.querySelector('input[type="file"]');
            const label = zone.querySelector('.dropzone-file');
            if (!input) return;
            const update = () => {
                const f = input.files && input.files[0];
                if (label) {
                    label.classList.toggle('is-visible', !!f);
                    const span = label.querySelector('span');
                    if (span) span.textContent = f ? f.name + ' · ' + formatSize(f.size) : '';
                }
            };
            input.addEventListener('change', update);
            ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, () => zone.classList.add('is-dragover')));
            ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, () => zone.classList.remove('is-dragover')));
        });

        // Aperçu d'avatar
        document.querySelectorAll('[data-avatar-input]').forEach(input => {
            const preview = document.querySelector(input.dataset.avatarInput);
            if (!preview) return;
            const original = preview.getAttribute('src');
            input.addEventListener('change', () => {
                const f = input.files && input.files[0];
                if (!f) return;
                if (!/^image\//.test(f.type)) { UI.toast('error', 'Le fichier sélectionné n’est pas une image.'); input.value = ''; return; }
                if (f.size > 2 * 1024 * 1024) { UI.toast('error', 'L’image ne doit pas dépasser 2 Mo.'); input.value = ''; return; }
                preview.src = URL.createObjectURL(f);
            });
            const reset = document.querySelector('[data-avatar-reset="' + input.id + '"]');
            if (reset) reset.addEventListener('click', () => { input.value = ''; preview.src = original; });
        });
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' o';
        if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' Ko';
        return (bytes / 1024 / 1024).toFixed(1).replace('.', ',') + ' Mo';
    }
    UI.formatSize = formatSize;

    /* ------------------------------------------------- Lecture d'une actualité */
    function initArticles() {
        const modalEl = document.getElementById('articleModal');
        if (!modalEl || !window.bootstrap) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        document.addEventListener('click', e => {
            const trigger = e.target.closest('[data-open-article]');
            if (!trigger) return;
            const tpl = document.querySelector('template[data-article="' + trigger.dataset.openArticle + '"]');
            if (!tpl) return;
            e.preventDefault();
            modalEl.querySelector('[data-article-title]').textContent = tpl.dataset.title || '';
            const body = modalEl.querySelector('[data-article-body]');
            body.innerHTML = '';
            body.appendChild(tpl.content.cloneNode(true));
            modal.show();
        });
    }

    /* --------------------------------------------------------- Divers */
    function initTooltips() {
        if (!window.bootstrap) return;
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => bootstrap.Tooltip.getOrCreateInstance(el));
    }

    document.addEventListener('DOMContentLoaded', function () {
        flushFlash();
        initConfirm();
        initLiveLists();
        initClientFilter();
        initForms();
        initArticles();
        initTooltips();
    });
})();
