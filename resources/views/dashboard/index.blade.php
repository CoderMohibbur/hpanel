<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            🖥️ User Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Hosting Info Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                <x-dashboard-card title="🌐 Domain" :value="$hosting->domain" />
                <x-dashboard-card title="📦 Package" :value="$hosting->package ?? 'N/A'" />
                <x-dashboard-card title="📅 Expiry" :value="optional($hosting->expiry_date)->format('M d, Y') ?? 'Not set'" />
                <x-dashboard-card title="🧩 Template" :value="$hosting->template_id ?? 'Default'" />
                <x-dashboard-card title="⚙️ PHP Version" :value="$hosting->php ?? '8.2'" />
                <x-dashboard-card title="🟢 Status" :value="ucfirst($hosting->status)" />
            </div>

            {{-- Quick Actions --}}
            @if ($hosting->status === 'active')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">⚡ Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="http://{{ $hosting->domain }}/wp-admin" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 shadow">
                            🔐 WP Login
                        </a>
                        <a href="{{ route('dns.manage', $hosting) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 shadow">
                            🌐 DNS Manage
                        </a>
                        <a href="{{ route('ssl.renew', $hosting) }}"
                           class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 shadow">
                            🔒 Renew SSL
                        </a>
                        {{-- Optional future actions --}}
                        @if(config('features.ai_tools'))
                            <a href="{{ route('tools.ai', $hosting) }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 shadow">
                                🧠 AI Writer
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-100 p-4 rounded shadow">
                    ⚠️ Your hosting is currently inactive or suspended. Please contact support.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
