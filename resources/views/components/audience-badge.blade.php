@props(['type'])

@switch($type)
    @case('permanent')
        <span class="badge badge-violet">Permanents (GDI)</span>
        @break
    @case('non_permanent')
        <span class="badge badge-info">Non permanents</span>
        @break
    @default
        <span class="badge badge-neutral">Tous les membres</span>
@endswitch
