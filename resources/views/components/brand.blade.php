{{-- Logo : utilise public/assets/img/brand/logo.svg s'il existe, sinon le monogramme. --}}
@props(['href' => null, 'inverse' => false])

@php $logo = file_exists(public_path('assets/img/brand/logo.svg')) ? asset('assets/img/brand/logo.svg') : null; @endphp

<a href="{{ $href ?? (auth()->check() ? route('index') : route('login')) }}"
   {{ $attributes->merge(['class' => 'brand']) }} @if ($inverse) style="color:#fff;" @endif aria-label="Asenim — accueil">
    @if ($logo)
        <img src="{{ $logo }}" alt="Asenim">
    @else
        <span class="brand-mark" aria-hidden="true">A</span><span class="brand-name">Asenim</span>
    @endif
</a>
