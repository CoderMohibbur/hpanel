@props([
    'href' => '#',
    'active' => false,
    'disabled' => false,
])

@php
    $base = 'nav-item flex items-center p-2 rounded-lg transition-colors';
    $colors = $active
        ? 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white'
        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700';

    $disabledCls = $disabled ? 'opacity-50 pointer-events-none cursor-not-allowed' : '';
@endphp

<a href="{{ $disabled ? '#' : $href }}"
   class="{{ $base }} {{ $colors }} {{ $disabledCls }}">
    <span class="nav-icon flex-shrink-0 w-5 h-5 text-gray-500 dark:text-gray-400 mr-3">
        {{ $icon ?? '' }}
    </span>

    <span class="sidebar-label flex-1 whitespace-nowrap">
        {{ $slot }}
    </span>
</a>
