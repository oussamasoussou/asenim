@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'icon' => null,
    'hint' => null,
    'id' => null,
])

@php
    $id = $id ?? $name;
    $invalid = $errors->has($name);
    $isPassword = $type === 'password';
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $id }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif</label>
    @endif
    <div class="{{ $icon || $isPassword ? 'input-icon' : '' }}">
        @if ($icon)<i class="bx {{ $icon }}" aria-hidden="true"></i>@endif
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            @unless ($isPassword) value="{{ old($name, $value) }}" @endunless
            @if ($required) required @endif
            @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            {{ $attributes->class(['form-control', 'is-invalid' => $invalid, 'has-action' => $isPassword]) }}>
        @if ($isPassword)
            <button type="button" class="input-action" data-toggle-password aria-label="Afficher le mot de passe"><i class="bx bx-show"></i></button>
        @endif
    </div>
    @error($name)
        <div class="invalid-feedback d-flex" id="{{ $id }}-error"><i class="bx bx-error-circle"></i>{{ $message }}</div>
    @elseif ($hint)
        <div class="form-hint">{{ $hint }}</div>
    @enderror
</div>
