@props(['name' => 'code'])
<svg {{ $attributes->merge(['viewBox' => '0 0 24 24', 'fill' => 'none', 'stroke' => 'currentColor', 'stroke-width' => '1.75', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round', 'aria-hidden' => 'true']) }}>
    @switch($name)
        @case('network')
            <rect x="9" y="3" width="6" height="5" rx="1"/><rect x="3" y="16" width="6" height="5" rx="1"/><rect x="15" y="16" width="6" height="5" rx="1"/><path d="M12 8v4M6 16v-2.5h12V16"/>
            @break
        @case('shield')
            <path d="M12 3 5 6v6c0 4.5 3 7.5 7 9 4-1.5 7-4.5 7-9V6z"/><path d="m9 12 2 2 4-4"/>
            @break
        @case('server')
            <rect x="4" y="4" width="16" height="7" rx="1.5"/><rect x="4" y="13" width="16" height="7" rx="1.5"/><path d="M8 7.5h.01M8 16.5h.01"/>
            @break
        @case('design')
            <path d="M12 20a8 8 0 1 1 8-8c0 2-1.5 3-3 3h-2a2 2 0 0 0-1 3.7A1.5 1.5 0 0 1 12 20z"/><circle cx="8" cy="10" r=".5"/><circle cx="12" cy="7.5" r=".5"/><circle cx="16" cy="10" r=".5"/>
            @break
        @case('support')
            <path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/>
            @break
        @default
            <path d="m8 8-4 4 4 4M16 8l4 4-4 4M14 5l-4 14"/>
    @endswitch
</svg>
