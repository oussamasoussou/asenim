@extends('front.layout')

@section('title', 'Contact')
@section('body_class', 'contact-design-page inner-page')

@section('content')


<main class="main">
  <section class="contact-design-shell" aria-labelledby="contact-title">
    <div class="contact-design-card">
      <aside class="contact-design-intro">
        <span class="contact-design-kicker">RESTONS EN CONTACT</span>
        <h1>Échangeons <span>ensemble.</span></h1>
        <p>Une question, une proposition ou une idée de collaboration ? L'équipe ASENIM est à votre écoute.</p>
        <div class="contact-design-details">
          <a href="mailto:{{ $contact['email'] }}" class="contact-design-detail"><span class="contact-design-icon"><i class="bi bi-envelope" aria-hidden="true"></i></span><span><strong>Adresse e-mail</strong><small>{{ $contact['email'] }}</small></span></a>
          <a href="tel:{{ $contact['phone_href'] }}" class="contact-design-detail"><span class="contact-design-icon"><i class="bi bi-telephone" aria-hidden="true"></i></span><span><strong>Téléphone</strong><small>{{ $contact['phone'] }}</small></span></a>
          <div class="contact-design-detail"><span class="contact-design-icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span><span><strong>Notre adresse</strong><small>{{ $contact['address_inline'] }}</small></span></div>
        </div>
        @if (count($contact['socials']))
          <div class="contact-design-social-block">
            <span class="contact-design-social-label">SUIVEZ-NOUS</span>
            <div class="contact-design-socials" aria-label="Réseaux sociaux ASENIM">
              @foreach ($contact['socials'] as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }} (nouvel onglet)" title="{{ $social['label'] }}"><i class="{{ $social['icon'] }}" aria-hidden="true"></i></a>
              @endforeach
            </div>
          </div>
        @endif
      </aside>
      <section class="contact-design-map-panel" aria-label="Localisation et prise de contact">
        <div id="map" class="contact-design-main-map">
          <iframe title="Localisation de l’ENIM Monastir" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3237.4075363136444!2d10.804279099999999!3d35.7653643!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x130212a893b893ef%3A0x3697457a7a81fcaf!2s%C3%89cole%20nationale%20d'ing%C3%A9nieurs%20de%20Monastir!5e0!3m2!1sfr!2stn!4v1762865955044!5m2!1sfr!2stn" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </div>
        <button class="contact-btn contact-design-mail-button" id="openForm" type="button" aria-label="Ouvrir le formulaire de contact" aria-haspopup="dialog" aria-controls="contactModal">
          <i class="bi bi-envelope mail-icon" aria-hidden="true"></i>
        </button>
      </section>
    </div>
  </section>
</main>
<div id="contactModal" class="contact-design-modal" role="dialog" aria-modal="true" aria-labelledby="contact-title" aria-hidden="true" hidden>
  <div class="contact-design-modal-backdrop" data-contact-close></div>
  <div class="contact-design-modal-window" role="document">
    <button type="button" id="closeModal" class="contact-design-modal-close" aria-label="Fermer le formulaire"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
      <section class="contact-design-form-panel">
        <div class="contact-design-form-header"><span class="contact-design-eyebrow">NOUS ÉCRIRE</span><h2 id="contact-title">Contactez-nous</h2><p>Remplissez le formulaire ci-dessous. Nous serons ravis de vous lire.</p></div>
        <form id="contactDesignForm" class="contact-design-form" action="{{ route('front.contact.send') }}" method="POST" novalidate>
          @csrf
          <div class="visually-hidden" aria-hidden="true"><label for="cdWebsite">Ne pas remplir</label><input id="cdWebsite" name="website" type="text" tabindex="-1" autocomplete="off"></div>
          <div class="contact-design-field"><label for="cdName">Nom complet <span>*</span></label><div class="contact-design-input"><input id="cdName" name="name" type="text" placeholder="Votre nom et prénom" autocomplete="name" required></div></div>
          <div class="contact-design-field"><label for="cdEmail">Adresse e-mail <span>*</span></label><div class="contact-design-input"><input id="cdEmail" name="email" type="email" placeholder="exemple@email.com" autocomplete="email" required></div></div>
          <div class="contact-design-field"><label for="cdSubject">Objet <span>*</span></label><div class="contact-design-input"><input id="cdSubject" name="subject" type="text" placeholder="Objet de votre message" required></div></div>
          <div class="contact-design-field"><label for="cdMessage">Votre message <span>*</span></label><div class="contact-design-input contact-design-textarea"><textarea id="cdMessage" name="message" rows="5" placeholder="Écrivez votre message ici…" required></textarea></div></div>
          <p id="contactDesignStatus" class="contact-design-status" role="status" aria-live="polite"></p>
          <button class="contact-design-submit" type="submit">ENVOYER MON MESSAGE <i class="bi bi-arrow-right" aria-hidden="true"></i></button>
          <p class="contact-design-note"> <span>*</span> Champs obligatoires</p>
        </form>
      </section>
  </div>
</div>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contactDesignForm');
  const status = document.getElementById('contactDesignStatus');
  const modal = document.getElementById('contactModal');
  const openButton = document.getElementById('openForm');
  const closeButton = document.getElementById('closeModal');
  document.querySelectorAll('.contact-design-socials a[href="#"]').forEach(a => {
    a.addEventListener('click', e => e.preventDefault());
  });
  let previousFocus;
  function openContactModal() {
    if (!modal) return;
    previousFocus = document.activeElement;
    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('contact-design-modal-open');
    closeButton?.focus();
  }
  function closeContactModal() {
    if (!modal) return;
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('contact-design-modal-open');
    previousFocus?.focus();
  }
  openButton?.addEventListener('click', openContactModal);
  closeButton?.addEventListener('click', closeContactModal);
  modal?.querySelector('[data-contact-close]')?.addEventListener('click', closeContactModal);
  document.addEventListener('keydown', e => {
    if (!modal || modal.hidden) return;
    if (e.key === 'Escape') closeContactModal();
    if (e.key === 'Tab') {
      const focusables = [...modal.querySelectorAll('button, input, textarea, a[href]')].filter(el => !el.disabled);
      if (!focusables.length) return;
      if (e.shiftKey && document.activeElement === focusables[0]) { e.preventDefault(); focusables[focusables.length - 1].focus(); }
      else if (!e.shiftKey && document.activeElement === focusables[focusables.length - 1]) { e.preventDefault(); focusables[0].focus(); }
    }
  });
  if (!form) return;
  form.addEventListener('submit', (event) => {
    event.preventDefault();
    if (!form.reportValidity()) return;
    const submit = form.querySelector('button[type="submit"]');
    status.className = 'contact-design-status';
    status.textContent = 'Envoi en cours…';
    submit.disabled = true;
    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    })
      .then(r => r.json().then(data => ({ ok: r.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          const errors = data.errors ? Object.values(data.errors).flat() : [];
          throw new Error(errors[0] || data.message || 'Le message n’a pas pu être envoyé.');
        }
        status.classList.add('is-success');
        status.textContent = data.message;
        form.reset();
      })
      .catch(err => {
        status.classList.add('is-error');
        status.textContent = err.message || 'Le message n’a pas pu être envoyé. Réessayez dans un instant.';
      })
      .finally(() => { submit.disabled = false; });
  });
});
</script>
@endpush
