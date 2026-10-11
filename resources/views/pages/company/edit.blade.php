@extends('layouts.app')

@section('title', 'Coordonnées de l’entreprise')

@section('content')
    <x-page-header title="Coordonnées de l’entreprise"
        subtitle="Adresse, téléphone et e-mail de référence de l’entreprise. Visible et modifiable par les administrateurs." />

    <div class="row g-4 align-items-start">
        {{-- Formulaire --}}
        <div class="col-xl-8">
            <form method="POST" action="{{ route('company.update') }}" class="card" novalidate>
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div>
                        <h2 class="form-section-title">Adresse</h2>
                        <p class="form-section-text">Adresse postale complète : rue, code postal, ville, pays.</p>
                    </div>
                    <div>
                        <x-textarea-field name="address" label="Adresse" :value="$company->address" rows="3" maxlength="500"
                            placeholder="12 rue de la République, 69002 Lyon, France" autocomplete="street-address" />
                    </div>
                </div>

                <div class="form-section">
                    <div>
                        <h2 class="form-section-title">Contact</h2>
                        <p class="form-section-text">Numéro et adresse e-mail principaux de l’entreprise.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <x-field name="phone" type="tel" label="Téléphone" icon="bx-phone" :value="$company->phone"
                                maxlength="30" placeholder="04 78 00 00 00" autocomplete="tel" />
                        </div>
                        <div class="col-md-6">
                            <x-field name="email" type="email" label="Adresse e-mail" icon="bx-envelope" :value="$company->email"
                                maxlength="255" placeholder="contact@entreprise.fr" autocomplete="email" />
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    @if ($company->exists && $company->updated_at)
                        <span class="text-muted small me-auto d-none d-sm-inline">
                            Dernière mise à jour {{ $company->updated_at->diffForHumans() }}
                            @if ($company->editor) par {{ $company->editor->first_name }} {{ $company->editor->last_name }} @endif
                        </span>
                    @endif
                    <button type="submit" class="btn btn-primary"><span><i class="bx bx-check"></i> Enregistrer</span></button>
                </div>
            </form>
        </div>

        {{-- Aperçu de la fiche enregistrée --}}
        <div class="col-xl-4">
            <section class="card" aria-labelledby="company-preview-title">
                <div class="card-header">
                    <div>
                        <h2 class="card-title" id="company-preview-title">Fiche actuelle</h2>
                        <p class="card-subtitle">Informations enregistrées</p>
                    </div>
                </div>

                @if ($company->isEmpty())
                    <x-empty-state icon="bx-buildings" title="Aucune coordonnée renseignée"
                        text="Complétez le formulaire puis enregistrez pour créer la fiche." compact />
                @else
                    <ul class="list-plain contact-list">
                        <li>
                            <span class="stat-icon badge-primary"><i class="bx bx-map"></i></span>
                            <div class="min-w-0">
                                <p class="contact-label">Adresse</p>
                                @if ($company->address)
                                    <p class="contact-value" style="white-space: pre-line;">{{ $company->address }}</p>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($company->address) }}"
                                        target="_blank" rel="noopener" class="small fw-semibold">Voir sur la carte <i class="bx bx-link-external"></i></a>
                                @else
                                    <p class="contact-value text-muted">Non renseignée</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <span class="stat-icon badge-success"><i class="bx bx-phone"></i></span>
                            <div class="min-w-0">
                                <p class="contact-label">Téléphone</p>
                                @if ($company->phone)
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $company->phone) }}" class="contact-value d-block">{{ $company->phone }}</a>
                                @else
                                    <p class="contact-value text-muted">Non renseigné</p>
                                @endif
                            </div>
                        </li>
                        <li>
                            <span class="stat-icon badge-violet"><i class="bx bx-envelope"></i></span>
                            <div class="min-w-0">
                                <p class="contact-label">E-mail</p>
                                @if ($company->email)
                                    <a href="mailto:{{ $company->email }}" class="contact-value d-block text-truncate">{{ $company->email }}</a>
                                @else
                                    <p class="contact-value text-muted">Non renseigné</p>
                                @endif
                            </div>
                        </li>
                    </ul>
                @endif
            </section>
        </div>
    </div>
@endsection
