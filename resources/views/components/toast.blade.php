@props([
    'message' => session('status'),
    'type' => session('toast_type', 'success') // success, error, warning, info
])

@if ($message)
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 translate-y-2" 
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full shadow-lg rounded-lg px-4 py-3 text-sm flex items-start space-x-2
            @if($type === 'success') bg-green-100 text-green-800 dark:bg-green-800 dark:text-white
            @elseif($type === 'error') bg-red-100 text-red-800 dark:bg-red-800 dark:text-white
            @elseif($type === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-white
            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white @endif"
    >
        {{-- Icon --}}
        <div class="flex-shrink-0 mt-0.5">
            @if($type === 'success')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            @elseif($type === 'error')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            @elseif($type === 'warning')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856C18.07 20 20 18.07 20 15.938V8.062C20 5.93 18.07 4 15.938 4H8.062C5.93 4 4 5.93 4 8.062v7.876C4 18.07 5.93 20 8.062 20z"/>
                </svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"/>
                </svg>
            @endif
        </div>

        {{-- Message --}}
        <div class="flex-1">
            {{ $message }}
        </div>

        {{-- Close --}}
        <button @click="show = false" class="ml-2 text-lg font-bold leading-none">&times;</button>
    </div>
@endif
