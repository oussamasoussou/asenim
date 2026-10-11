{{-- Étapes de la première connexion. Variable : $step (1, 2 ou 3) --}}
@php
    $steps = [1 => 'Informations personnelles', 2 => 'Parcours professionnel', 3 => 'Biographie'];
@endphp

<x-page-header title="Bienvenue sur Asenim 👋"
    subtitle="Complétez votre profil en trois étapes pour que les autres membres puissent vous identifier." />

<ol class="stepper list-plain" aria-label="Progression">
    @foreach ($steps as $n => $label)
        @if ($n > 1)
            <li class="stepper-line {{ $n <= $step ? 'is-done' : '' }}" aria-hidden="true"></li>
        @endif
        <li class="stepper-item {{ $n < $step ? 'is-done' : ($n === $step ? 'is-current' : '') }}" @if ($n === $step) aria-current="step" @endif>
            <span class="step-num">@if ($n < $step)<i class="bx bx-check"></i>@else{{ $n }}@endif</span>
            <span class="step-label">{{ $label }}</span>
        </li>
    @endforeach
</ol>
