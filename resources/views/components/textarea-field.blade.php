@props(['name', 'label' => null, 'value' => null, 'required' => false, 'hint' => null, 'rows' => 6, 'rich' => false])

@php $invalid = $errors->has($name); @endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif</label>
    @endif
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" @if ($required) required @endif
        @if ($rich) data-rich-text data-rich-label="{{ $label }}" @endif
        {{ $attributes->class(['form-control', 'is-invalid' => $invalid]) }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
    @elseif ($hint)
        <div class="form-hint">{{ $hint }}</div>
    @enderror
</div>

@if ($rich)
    @once
        @push('styles')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
        @endpush
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
            <script src="{{ asset('assets/js/rich-text.js') }}?v={{ filemtime(public_path('assets/js/rich-text.js')) }}"></script>
        @endpush
    @endonce
@endif
