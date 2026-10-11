@extends('front.layout')

@section('title', 'Créer un compte')
@section('body_class', 'registration-page inner-page')

@section('content')


<main class="main">

    <!-- =====================================================
         PAGE INSCRIPTION ASENIM
         ===================================================== -->
    <section class="registration-shell" aria-labelledby="registration-title">
      <div class="registration-card">

        <!-- Présentation -->
        <aside class="registration-intro">
          <span class="registration-kicker">ESPACE MEMBRE</span>

          <h1>
            Rejoignez la
            <span>communauté ASENIM</span>
          </h1>

          <p>
            Créez votre compte pour rejoindre le réseau des diplômés,
            échanger avec la communauté et accéder à votre espace membre.
          </p>

          <div class="registration-benefits">
            <div class="registration-benefit">
              <span class="registration-benefit__icon">
                <i class="bi bi-people-fill" aria-hidden="true"></i>
              </span>
              <div>
                <strong>Une communauté engagée</strong>
                <span>Connectez-vous aux alumni et à l'ENIM.</span>
              </div>
            </div>

            <div class="registration-benefit">
              <span class="registration-benefit__icon">
                <i class="bi bi-diagram-3-fill" aria-hidden="true"></i>
              </span>
              <div>
                <strong>Un réseau solide</strong>
                <span>Développez vos échanges et opportunités.</span>
              </div>
            </div>

            <div class="registration-benefit">
              <span class="registration-benefit__icon">
                <i class="bi bi-shield-check" aria-hidden="true"></i>
              </span>
              <div>
                <strong>Un espace sécurisé</strong>
                <span>Vos informations restent protégées.</span>
              </div>
            </div>
          </div>

         
        </aside>

        <!-- Formulaire -->
        <section class="registration-form-panel">
          <div class="registration-form-header">
            <span class="registration-form-eyebrow">BIENVENUE</span>
            <h2 id="registration-title">Créer un compte</h2>
            <p>
              Remplissez le formulaire pour rejoindre l’espace membre ASENIM.
            </p>
          </div>

          <form id="registrationForm"
                class="registration-form"
                action="{{ route('front.register.store') }}"
                method="post"
                novalidate>
            @csrf

            <div class="registration-field registration-field--full">
              <label for="registrationName">
                Nom et prénom <span aria-hidden="true">*</span>
              </label>
              <div class="registration-input-wrap">
                <i class="bi bi-person" aria-hidden="true"></i>
                <input id="registrationName"
                       name="name"
                       type="text"
                       autocomplete="name"
                       placeholder="Votre nom et prénom"
                       required>
              </div>
            </div>

            <div class="registration-field registration-field--full">
              <label for="registrationEmail">
                Adresse e-mail <span aria-hidden="true">*</span>
              </label>
              <div class="registration-input-wrap">
                <i class="bi bi-envelope" aria-hidden="true"></i>
                <input id="registrationEmail"
                       name="email"
                       type="email"
                       autocomplete="email"
                       placeholder="exemple@email.com"
                       required>
              </div>
            </div>

            <div class="registration-field">
              <label for="registrationPassword">
                Mot de passe <span aria-hidden="true">*</span>
              </label>
              <div class="registration-input-wrap registration-password">
                <i class="bi bi-lock" aria-hidden="true"></i>
                <input id="registrationPassword"
                       name="password"
                       type="password"
                       autocomplete="new-password"
                       placeholder="8 caractères minimum"
                       minlength="8"
                       required>
                <button type="button"
                        class="registration-password-toggle"
                        data-target="registrationPassword"
                        aria-label="Afficher le mot de passe">
                  <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <div class="registration-field">
              <label for="registrationPasswordConfirm">
                Confirmation <span aria-hidden="true">*</span>
              </label>
              <div class="registration-input-wrap registration-password">
                <i class="bi bi-lock-fill" aria-hidden="true"></i>
                <input id="registrationPasswordConfirm"
                       name="password_confirmation"
                       type="password"
                       autocomplete="new-password"
                       placeholder="Confirmez le mot de passe"
                       minlength="8"
                       required>
                <button type="button"
                        class="registration-password-toggle"
                        data-target="registrationPasswordConfirm"
                        aria-label="Afficher le mot de passe">
                  <i class="fa-solid fa-eye" aria-hidden="true"></i>
                </button>
              </div>
            </div>

            <label class="registration-terms" for="registrationTerms">
              <input type="checkbox" id="registrationTerms" name="terms" required>
              <span>
                J'accepte les conditions d'utilisation et la politique de
                confidentialité de l'ASENIM.
              </span>
            </label>

            <div id="registrationStatus"
                 class="registration-status"
                 role="status"
                 aria-live="polite"></div>

            <button type="submit" class="registration-submit">
              <span>CRÉER MON COMPTE</span>
              <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </button>

            <p class="registration-required">
              <span>*</span> Champs obligatoires
            </p>
          </form>

          
        </section>

      </div>
    </section>

</main>

  <!-- =====================================================
       FOOTER
       ===================================================== -->
@endsection
