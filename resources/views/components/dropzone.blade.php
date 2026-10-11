@props(['name', 'accept' => null, 'hint' => null, 'required' => false, 'current' => null])

@php $invalid = $errors->has($name); @endphp

<label class="dropzone {{ $invalid ? 'is-invalid' : '' }}" for="{{ $name }}">
    <input type="file" id="{{ $name }}" name="{{ $name }}" @if ($accept) accept="{{ $accept }}" @endif @if ($required) required @endif {{ $attributes }}>
    <span class="dropzone-icon"><i class="bx bx-cloud-upload"></i></span>
    <span class="dropzone-title"><span>Cliquez pour choisir</span> ou glissez un fichier ici</span>
    @if ($hint)<span class="dropzone-hint">{{ $hint }}</span>@endif
    @if ($current)<span class="dropzone-hint">Fichier actuel : <strong>{{ $current }}</strong></span>@endif
    <span class="dropzone-file"><i class="bx bx-file"></i><span></span></span>
</label>
@error($name)
    <div class="invalid-feedback d-flex"><i class="bx bx-error-circle"></i>{{ $message }}</div>
@enderror
