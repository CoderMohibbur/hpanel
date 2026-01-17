@props([
    'panel' => 'admin', // admin|reseller|client
    'title' => 'Panel',
    'pageTitle' => null,
    'pageDesc' => null,
])

<x-panel-layout panel="reseller" :title="$title" :pageTitle="$pageTitle" :pageDesc="$pageDesc">
    <x-slot name="actions">
        {{ $actions ?? '' }}
    </x-slot>

    {{ $slot }}
</x-panel-layout>
