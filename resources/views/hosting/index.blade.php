<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            🌐 Your Hostings
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 text-green-700 dark:text-green-200 bg-green-100 dark:bg-green-700 p-4 rounded-md shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($hostings as $hosting)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 space-y-2 border dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            🌐 {{ $hosting->domain }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Status: <span class="font-medium">{{ ucfirst($hosting->cyberpanel_status) }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Plan: <span class="font-medium">{{ $hosting->plan }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            SSL: {!! $hosting->ssl ? '✅ Enabled' : '❌ Not Enabled' !!}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Expiry: {{ $hosting->expiry_formatted ?? 'N/A' }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-300">No hostings provisioned yet.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $hostings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
