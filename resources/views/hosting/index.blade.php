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

            @if(session('error'))
                <div class="mb-4 text-red-700 dark:text-red-200 bg-red-100 dark:bg-red-700 p-4 rounded-md shadow-sm">
                    {{ session('error') }}
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

                        <div class="flex flex-wrap gap-2 mt-4">
                            <!-- Edit Button -->
                            <a href="{{ route('hosting.edit', $hosting) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700">
                                ✏️ Edit
                            </a>

                            <!-- Refresh Status -->
                            <form method="POST" action="{{ route('hosting.refresh', $hosting) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-600">
                                    🔄 Refresh
                                </button>
                            </form>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('hosting.destroy', $hosting) }}" onsubmit="return confirm('Are you sure you want to delete this hosting?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">
                                    🗑 Delete
                                </button>
                            </form>

                            <!-- Logs Button (Optional placeholder) -->
                            {{-- <a href="{{ route('hosting.logs', $hosting) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded hover:bg-gray-700">
                                📄 Logs
                            </a> --}}
                        </div>
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
