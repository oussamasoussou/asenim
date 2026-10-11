/**
 * Barre d'outils de mise en forme pour les champs <textarea data-rich-text>.
 * Le <textarea> reste la source envoyée avec le formulaire (fonctionne aussi sans JavaScript) :
 * l'éditeur Quill est inséré juste avant et recopie son HTML dans le textarea à chaque modification.
 * Le HTML est de toute façon nettoyé côté serveur (App\Support\RichText).
 */
(function () {
    'use strict';

    if (!window.Quill) return; // CDN indisponible : on garde le textarea classique

    var TOOLBAR = [
        [{ header: [2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'link'],
        ['clean'],
    ];

    // Libellés français des boutons (infobulles + lecteurs d'écran)
    var LABELS = {
        'ql-header': 'Style de titre',
        'ql-bold': 'Gras',
        'ql-italic': 'Italique',
        'ql-underline': 'Souligné',
        'ql-strike': 'Barré',
        'ql-list[value="ordered"]': 'Liste numérotée',
        'ql-list[value="bullet"]': 'Liste à puces',
        'ql-blockquote': 'Citation',
        'ql-link': 'Insérer un lien',
        'ql-clean': 'Effacer la mise en forme',
    };

    function escapeHtml(text) {
        return text.replace(/[&<>"]/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
        });
    }

    // Anciens textes sans balise : un paragraphe par ligne
    function toHtml(value) {
        if (!value.trim()) return '';
        if (/<\s*\/?\s*[a-z][a-z0-9]*\b[^>]*>/i.test(value)) return value;
        return value.split(/\r?\n/).filter(function (l) { return l.trim(); })
            .map(function (l) { return '<p>' + escapeHtml(l.trim()) + '</p>'; }).join('');
    }

    function labelToolbar(toolbar) {
        Object.keys(LABELS).forEach(function (selector) {
            var cls = selector.split('[')[0];
            var attr = selector.indexOf('[') > -1 ? '[' + selector.split('[')[1] : '';
            toolbar.querySelectorAll('button.' + cls + attr + ', .' + cls + ' .ql-picker-label').forEach(function (el) {
                el.setAttribute('title', LABELS[selector]);
                el.setAttribute('aria-label', LABELS[selector]);
            });
        });
    }

    function enhance(textarea) {
        if (textarea.dataset.richReady) return;
        textarea.dataset.richReady = '1';

        var wrapper = document.createElement('div');
        wrapper.className = 'rich-editor' + (textarea.classList.contains('is-invalid') ? ' is-invalid' : '');
        var editorEl = document.createElement('div');
        wrapper.appendChild(editorEl);
        textarea.parentNode.insertBefore(wrapper, textarea);

        var rows = parseInt(textarea.getAttribute('rows') || '6', 10);
        editorEl.style.minHeight = Math.max(140, rows * 26) + 'px';

        var quill = new Quill(editorEl, {
            theme: 'snow',
            placeholder: textarea.getAttribute('placeholder') || '',
            modules: { toolbar: TOOLBAR, clipboard: { matchVisual: false } },
        });

        var initial = toHtml(textarea.value);
        if (initial) {
            quill.setContents(quill.clipboard.convert({ html: initial }), 'silent');
        }

        // Accessibilité : la zone d'édition remplace le textarea pour les lecteurs d'écran
        var editable = quill.root;
        editable.setAttribute('role', 'textbox');
        editable.setAttribute('aria-multiline', 'true');
        editable.setAttribute('aria-label', textarea.dataset.richLabel || textarea.name);
        if (textarea.getAttribute('aria-describedby')) editable.setAttribute('aria-describedby', textarea.getAttribute('aria-describedby'));
        document.querySelector('label[for="' + textarea.id + '"]')?.addEventListener('click', function (e) {
            e.preventDefault();
            quill.focus();
        });

        labelToolbar(wrapper.querySelector('.ql-toolbar'));

        function sync() {
            var empty = quill.getText().trim() === '';
            textarea.value = empty ? '' : quill.getSemanticHTML().replace(/&nbsp;/g, ' ');
        }
        quill.on('text-change', sync);
        sync();

        // Le textarea est masqué mais reste dans le formulaire (validation "required" côté serveur)
        textarea.classList.add('rich-source');
        textarea.removeAttribute('required');
        textarea.setAttribute('tabindex', '-1');
        textarea.setAttribute('aria-hidden', 'true');

        textarea.form?.addEventListener('submit', sync);
    }

    function init() {
        document.querySelectorAll('textarea[data-rich-text]').forEach(enhance);
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
