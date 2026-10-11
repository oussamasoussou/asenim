@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

@php
    $invalid = $errors->has($name);
    $current = (string) old($name, $value);
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" @if ($required) required @endif
        {{ $attributes->class(['form-select', 'is-invalid' => $invalid]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected($current === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
    @elseif ($hint)
        <div class="form-hint">{{ $hint }}</div>
    @enderror
</div>
