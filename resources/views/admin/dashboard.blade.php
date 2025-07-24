<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Admin Dashboard — User Management
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ✅ Success Message --}}
            {{-- @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded dark:bg-green-800 dark:text-green-100">
                    {{ session('status') }}
                </div>
            @endif --}}

            {{-- ✅ Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <div class="text-gray-500 dark:text-gray-300 text-sm">Total Users</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <div class="text-gray-500 dark:text-gray-300 text-sm">Active Users</div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $activeUsers }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <div class="text-gray-500 dark:text-gray-300 text-sm">Suspended Users</div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $suspendedUsers }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-center">
                    <div class="text-gray-500 dark:text-gray-300 text-sm">Admin Users</div>
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $adminUsers }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded shadow text-left">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200">🌐 Hosting Info</h3>
                        <a href="{{ route('hosting.index') }}"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Manage</a>
                    </div>

                    <ul class="space-y-2 text-sm">
                        @forelse($hostings as $hosting)
                            <li
                                class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-1">
                                <div class="text-gray-800 dark:text-gray-100">
                                    <span class="font-medium">{{ $hosting->domain }}</span><br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Status:
                                        {{ ucfirst($hosting->cyberpanel_status) }}</span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 text-right">
                                    Exp: {{ $hosting->expiry_formatted ?? 'N/A' }}
                                </div>
                            </li>
                        @empty
                            <li class="text-gray-500 dark:text-gray-400">No hosting records yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- ✅ User Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">All Users</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">#</th>
                                    <th class="px-4 py-2 text-left font-semibold">Name</th>
                                    <th class="px-4 py-2 text-left font-semibold">Email</th>
                                    <th class="px-4 py-2 text-left font-semibold">Role</th>
                                    <th class="px-4 py-2 text-left font-semibold">Status</th>
                                    <th class="px-4 py-2 text-left font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2">{{ $user->name }}</td>
                                        <td class="px-4 py-2">{{ $user->email }}</td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="px-2 py-1 text-xs rounded 
                                                {{ $user->is_admin ? 'bg-blue-600 text-white' : 'bg-gray-400 text-white' }}">
                                                {{ $user->is_admin ? 'Admin' : 'User' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="px-2 py-1 text-xs rounded 
                                                {{ $user->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                                {{ $user->is_active ? 'Active' : 'Suspended' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <form method="POST"
                                                action="{{ route('admin.users.toggle-status', $user->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 text-sm rounded shadow font-medium
                                                    {{ $user->is_active ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' }}"
                                                    onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'suspend' : 'reactivate' }} this user?')">
                                                    {{ $user->is_active ? 'Suspend' : 'Reactivate' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 🔄 Future Pagination --}}
                    {{-- <div class="mt-4">
                        {{ $users->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
