@props(['name', 'label' => null, 'value' => null, 'required' => false, 'hint' => null, 'rows' => 6])

@php $invalid = $errors->has($name); @endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif</label>
    @endif
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @if ($required) required @endif
        {{ $attributes->class(['form-control', 'is-invalid' => $invalid]) }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
    @elseif ($hint)
        <div class="form-hint">{{ $hint }}</div>
    @enderror
</div>
