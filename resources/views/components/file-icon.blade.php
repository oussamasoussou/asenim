@props(['path' => ''])

@php
    $ext = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
    [$kind, $icon] = match (true) {
        $ext === 'pdf' => ['pdf', 'bxs-file-pdf'],
        in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) => ['image', 'bxs-file-image'],
        in_array($ext, ['doc', 'docx', 'odt', 'txt']) => ['doc', 'bxs-file-doc'],
        in_array($ext, ['xls', 'xlsx', 'csv', 'ods']) => ['sheet', 'bx-spreadsheet'],
        default => ['other', 'bxs-file-blank'],
    };
@endphp

<span {{ $attributes->merge(['class' => 'file-icon is-' . $kind]) }} aria-hidden="true"><i class="bx {{ $icon }}"></i></span>
