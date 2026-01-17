@props([
    'title' => 'Reseller',
    'pageTitle' => 'Reseller',
    'pageDesc' => null,
])

<x-panel-layout panel="reseller" :title="$title" :pageTitle="$pageTitle" :pageDesc="$pageDesc">
    <x-slot name="actions">
        {{ $actions ?? '' }}
    </x-slot>

    {{ $slot }}
</x-panel-layout>
