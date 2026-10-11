@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
    <x-page-header title="Messagerie" subtitle="Échangez en temps réel avec les membres de la plateforme." />

    <div class="card chat-shell">
        {{-- Membres en ligne --}}
        <aside class="chat-sidebar" aria-label="Membres en ligne">
            <div class="chat-sidebar-header">
                <h2 class="card-title">En ligne</h2>
                <span class="badge badge-success badge-dot">{{ $connectedUsers->count() }}</span>
            </div>
            <ul class="chat-sidebar-list">
                @forelse ($connectedUsers as $user)
                    <li>
                        <x-avatar :user="$user" size="sm" online />
                        <span class="text-truncate">{{ $user->first_name }} {{ $user->last_name }}</span>
                        @if ($user->id === $userId)<span class="text-muted text-xs ms-auto">Vous</span>@endif
                    </li>
                @empty
                    <li class="text-muted">Personne n’est connecté pour le moment.</li>
                @endforelse
            </ul>
        </aside>

        {{-- Conversation --}}
        <section class="chat-main" aria-label="Conversation de groupe">
            <header class="chat-header">
                <div class="d-flex align-items-center gap-2 min-w-0">
                    <span class="stat-icon badge-primary" style="width:36px;height:36px;font-size:1.1rem;"><i class="bx bx-chat"></i></span>
                    <div class="min-w-0">
                        <h2 class="card-title">Discussion générale</h2>
                        <p class="card-subtitle d-lg-none">{{ $connectedUsers->count() }} membre(s) en ligne</p>
                    </div>
                </div>
                <span class="text-muted text-xs d-inline-flex align-items-center gap-1" id="chat-sync" aria-live="polite">
                    <i class="bx bx-refresh"></i>Synchronisation…
                </span>
            </header>

            <div class="chat-messages" id="message-list" role="log" aria-live="polite" aria-relevant="additions">
                <div class="m-auto text-center text-muted small" id="chat-loading">
                    <span class="spinner" aria-hidden="true"></span>
                    <div class="mt-2">Chargement des messages…</div>
                </div>
            </div>

            <form class="chat-composer" id="chat-form" data-no-loading novalidate>
                <div class="chat-file-chip d-none" id="file-chip">
                    <i class="bx bx-paperclip"></i><span id="file-name"></span>
                    <button type="button" id="remove-file" aria-label="Retirer la pièce jointe"><i class="bx bx-x"></i></button>
                </div>
                <div class="chat-composer-row">
                    <label for="file" class="btn btn-secondary btn-icon mb-0" title="Joindre un fichier" aria-label="Joindre un fichier">
                        <i class="bx bx-paperclip"></i>
                    </label>
                    <input type="file" id="file" accept=".jpg,.jpeg,.png,.pdf,.docx,.txt" hidden>
                    <label for="content" class="sr-only">Votre message</label>
                    <textarea id="content" class="form-control" rows="1" placeholder="Écrire un message…" aria-describedby="composer-hint"></textarea>
                    <button type="submit" id="send" class="btn btn-primary btn-icon" aria-label="Envoyer" disabled>
                        <i class="bx bxs-send"></i>
                    </button>
                </div>
                <p class="form-hint mb-0 mt-1 d-none d-md-block" id="composer-hint">Entrée pour envoyer · Maj + Entrée pour aller à la ligne</p>
            </form>
        </section>
    </div>

    {{-- Aperçu d'image --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-label="Aperçu de l’image" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0 shadow-none" style="border: 0 !important; box-shadow: none !important;">
                <div class="text-end mb-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bx bx-x"></i>Fermer</button>
                </div>
                <img id="imagePreviewModalSrc" src="" alt="" class="img-fluid rounded mx-auto d-block" style="max-height: 85vh;">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ENDPOINT = @json(url('/messages'));
    const STORAGE = @json(asset('storage')) + '/';
    const ME = {{ (int) $userId }};
    const TONES = 6;

    const list = document.getElementById('message-list');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('content');
    const fileInput = document.getElementById('file');
    const chip = document.getElementById('file-chip');
    const chipName = document.getElementById('file-name');
    const send = document.getElementById('send');
    const sync = document.getElementById('chat-sync');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let lastSignature = '';
    let failures = 0;

    /* ---------- helpers DOM (aucune donnée utilisateur injectée en HTML) ---------- */
    function el(tag, cls, text) {
        const n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text !== undefined && text !== null) n.textContent = text;
        return n;
    }

    function avatar(user) {
        const a = el('span', 'ui-avatar is-sm tone-' + ((user && user.id) || 0) % TONES);
        if (user && user.image) {
            const img = el('img');
            img.src = STORAGE + user.image;
            img.alt = '';
            img.loading = 'lazy';
            a.appendChild(img);
        } else {
            const initials = ((user && user.first_name || '?')[0] + (user && user.last_name ? user.last_name[0] : '')).toUpperCase();
            a.appendChild(el('span', null, initials));
        }
        return a;
    }

    function isImage(m) {
        if (m.file_type === 'image') return true;
        return /\.(jpe?g|png|gif|webp)$/i.test(m.file_name || '');
    }

    function dayLabel(d) {
        const today = new Date(); today.setHours(0, 0, 0, 0);
        const day = new Date(d); day.setHours(0, 0, 0, 0);
        const diff = Math.round((today - day) / 86400000);
        if (diff === 0) return 'Aujourd’hui';
        if (diff === 1) return 'Hier';
        return d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
    }

    function render(messages) {
        const nearBottom = list.scrollHeight - list.clientHeight - list.scrollTop < 80;
        const frag = document.createDocumentFragment();

        if (!messages.length) {
            const empty = el('div', 'empty-state m-auto');
            const icon = el('div', 'empty-state-icon'); icon.appendChild(el('i', 'bx bx-message-rounded-dots'));
            empty.append(icon, el('p', 'empty-state-title', 'Aucun message'), el('p', 'empty-state-text', 'Lancez la discussion en envoyant le premier message.'));
            frag.appendChild(empty);
        }

        let lastDay = '';
        messages.forEach(m => {
            const date = new Date(m.created_at);
            const day = date.toDateString();
            if (day !== lastDay) { frag.appendChild(el('div', 'chat-day', dayLabel(date))); lastDay = day; }

            const mine = Number(m.user_id) === ME;
            const row = el('div', 'chat-msg' + (mine ? ' is-mine' : ''));
            const bubble = el('div', 'chat-bubble');
            const name = m.user ? (m.user.first_name + ' ' + (m.user.last_name || '')).trim() : 'Membre';
            bubble.appendChild(el('div', 'chat-author', name));

            if (m.content && m.content !== 'null') bubble.appendChild(el('p', 'chat-text', m.content));

            if (m.file_path) {
                const url = STORAGE + m.file_path;
                if (isImage(m)) {
                    const img = el('img', 'chat-attachment-img');
                    img.src = url; img.alt = m.file_name || 'Image jointe'; img.loading = 'lazy';
                    img.addEventListener('click', () => openImage(url));
                    bubble.appendChild(img);
                } else {
                    const a = el('a', 'chat-attachment-file');
                    a.href = url; a.target = '_blank'; a.rel = 'noopener';
                    a.append(el('i', 'bx bx-file'), el('span', null, (m.file_name || 'Fichier').replace(/^\d+_/, '')), el('i', 'bx bx-download ms-auto'));
                    bubble.appendChild(a);
                }
            }

            bubble.appendChild(el('time', 'chat-time', date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })));
            row.append(avatar(m.user), bubble);
            frag.appendChild(row);
        });

        list.replaceChildren(frag);
        if (nearBottom || !lastSignature) list.scrollTop = list.scrollHeight;
    }

    function setSync(state) {
        const map = {
            ok: ['bx-check-circle', 'À jour', ''],
            error: ['bx-wifi-off', 'Hors ligne — nouvelle tentative…', 'text-danger'],
        };
        const [icon, label, cls] = map[state];
        sync.className = 'text-xs d-inline-flex align-items-center gap-1 ' + (cls || 'text-muted');
        sync.replaceChildren(el('i', 'bx ' + icon), document.createTextNode(label));
    }

    function fetchMessages() {
        return fetch(ENDPOINT, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(r => {
                if (r.status === 401 || r.redirected) { window.location.reload(); throw new Error('auth'); }
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(data => {
                failures = 0;
                setSync('ok');
                const last = data[data.length - 1];
                const signature = data.length + ':' + (last ? last.id : 0);
                if (signature !== lastSignature) { render(data); lastSignature = signature; }
            })
            .catch(() => {
                failures++;
                setSync('error');
                if (!lastSignature) {
                    list.replaceChildren(el('div', 'm-auto text-center text-muted small', 'Impossible de charger les messages.'));
                }
            });
    }

    function openImage(src) {
        document.getElementById('imagePreviewModalSrc').src = src;
        bootstrap.Modal.getOrCreateInstance(document.getElementById('imagePreviewModal')).show();
    }

    /* ---------- composition ---------- */
    function refreshSendState() {
        send.disabled = !input.value.trim() && !(fileInput.files && fileInput.files.length);
    }

    function autosize() {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 140) + 'px';
    }

    function clearFile() {
        fileInput.value = '';
        chip.classList.add('d-none');
        refreshSendState();
    }

    input.addEventListener('input', () => { autosize(); refreshSendState(); });
    input.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.requestSubmit(); }
    });

    fileInput.addEventListener('change', () => {
        const f = fileInput.files[0];
        if (f && f.size > 2 * 1024 * 1024) {
            UI.toast('error', 'La pièce jointe ne doit pas dépasser 2 Mo.');
            clearFile();
            return;
        }
        chip.classList.toggle('d-none', !f);
        chipName.textContent = f ? f.name + ' · ' + UI.formatSize(f.size) : '';
        refreshSendState();
    });
    document.getElementById('remove-file').addEventListener('click', clearFile);

    form.addEventListener('submit', e => {
        e.preventDefault();
        if (send.disabled) return;

        const fd = new FormData();
        if (input.value.trim()) fd.append('content', input.value.trim());
        if (fileInput.files[0]) fd.append('file', fileInput.files[0]);

        send.classList.add('is-loading');
        send.disabled = true;

        fetch(ENDPOINT, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(r => r.json().then(body => ({ ok: r.ok, body })))
            .then(({ ok, body }) => {
                if (!ok) {
                    const errors = body.errors ? Object.values(body.errors).flat() : [];
                    throw new Error(errors[0] || body.message || 'Envoi impossible.');
                }
                input.value = '';
                autosize();
                clearFile();
                return fetchMessages().then(() => { list.scrollTop = list.scrollHeight; });
            })
            .catch(err => UI.toast('error', err.message || 'Le message n’a pas pu être envoyé. Réessayez.'))
            .finally(() => { send.classList.remove('is-loading'); refreshSendState(); input.focus(); });
    });

    fetchMessages();
    setInterval(() => { if (!document.hidden) fetchMessages(); }, 5000);
});
</script>
@endsection
