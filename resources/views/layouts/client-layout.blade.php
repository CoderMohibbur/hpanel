@props([
    'title' => 'Client',
    'pageTitle' => 'Client',
    'pageDesc' => null,
])

<x-panel-layout panel="client" :title="$title" :pageTitle="$pageTitle" :pageDesc="$pageDesc">
    <x-slot name="actions">
        {{ $actions ?? '' }}
    </x-slot>

    {{ $slot }}
</x-panel-layout>
