{{-- Champs d'une publication. Variable : $item (News|null) --}}
@php
    $item = $item ?? null;
    $type = old('events_news', $item->events_news ?? (request('type') === 'event' ? 'event' : 'news'));
    $dateValue = old('date', $item && $item->date ? \Carbon\Carbon::parse($item->date)->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i'));
@endphp

<div class="form-section">
    <div>
        <h2 class="form-section-title">Type de publication</h2>
        <p class="form-section-text">Une actualité informe ; un événement est daté et apparaît dans l’agenda.</p>
    </div>
    <fieldset>
        <legend class="sr-only">Type de publication</legend>
        <div class="segmented">
            <input type="radio" name="events_news" id="type-news" value="news" @checked($type !== 'event') data-type-toggle>
            <label for="type-news"><i class="bx bx-news"></i><span><strong>Actualité</strong><small>Information, annonce, nouveauté</small></span></label>
            <input type="radio" name="events_news" id="type-event" value="event" @checked($type === 'event') data-type-toggle>
            <label for="type-event"><i class="bx bx-calendar-event"></i><span><strong>Événement</strong><small>Séminaire, réunion, soutenance…</small></span></label>
        </div>
        @error('events_news')<div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>@enderror
    </fieldset>
</div>

<div class="form-section">
    <div>
        <h2 class="form-section-title">Contenu</h2>
        <p class="form-section-text">Un titre court et explicite, puis le détail de l’information.</p>
    </div>
    <div>
        <x-field name="title" label="Titre" :value="$item->title ?? null" required maxlength="255" placeholder="Ex. Séminaire de rentrée" />
        <div class="row">
            <div class="col-md-7">
                <x-field name="date" type="datetime-local" :value="$dateValue" required
                    :label="$type === 'event' ? 'Date et heure de l’événement' : 'Date de publication'" data-date-label />
            </div>
        </div>
        <x-textarea-field name="content" label="Texte" :value="$item->content ?? null" required rows="8" rich
            placeholder="Rédigez le contenu de la publication…" />
    </div>
</div>

<div class="form-section">
    <div>
        <h2 class="form-section-title">Image</h2>
        <p class="form-section-text">Facultative. JPG, PNG ou GIF, 2 Mo maximum.</p>
    </div>
    <div>
        @if ($item && $item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="Image actuelle" class="article-cover" style="max-height: 200px; width: auto;">
        @endif
        <x-dropzone name="image" accept="image/png,image/jpeg,image/gif"
            :hint="$item && $item->image ? 'Choisissez une image pour remplacer l’actuelle.' : 'JPG, PNG ou GIF · 2 Mo max.'" />
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('change', function (e) {
            if (!e.target.matches('[data-type-toggle]')) return;
            const label = document.querySelector('label[for="date"]');
            if (!label) return;
            const req = label.querySelector('.req');
            label.textContent = e.target.value === 'event' ? 'Date et heure de l’événement' : 'Date de publication';
            if (req) label.appendChild(req);
        });
    </script>
@endpush
