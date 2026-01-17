@props([
    'href' => '#',
    'active' => false,
    'soon' => false,
])

@php
    $base = 'group flex items-center gap-3 px-3 py-2 rounded-xl text-sm border transition';
    $activeCls = 'bg-gray-900 text-white border-gray-900';
    $idleCls = 'bg-white text-gray-700 border-transparent hover:border-gray-200 hover:bg-gray-50';
@endphp

<a href="{{ $soon ? '#' : $href }}"
   class="{{ $base }} {{ $active ? $activeCls : $idleCls }} {{ $soon ? 'opacity-60 cursor-not-allowed' : '' }}"
   @if($active) aria-current="page" @endif
>
    <div class="w-5 h-5 flex items-center justify-center">
        {{ $icon ?? '' }}
    </div>

    <div class="flex-1">
        <div class="font-medium">{{ $slot }}</div>
    </div>

    @if($soon)
        <span class="text-[10px] px-2 py-1 rounded-full border {{ $active ? 'border-white/20 text-white/90' : 'border-gray-200 text-gray-500' }}">
            Soon
        </span>
    @endif
</a>
