<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            User Details — {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Profile Overview --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">Profile Information</h3>
                        <div class="mt-4 space-y-4 text-sm">
                            <div><strong>Name:</strong> {{ $user->name }}</div>
                            <div><strong>Email:</strong> {{ $user->email }}</div>
                            <div><strong>Email Verified:</strong>
                                {{ $user->email_verified_at ? $user->email_verified_at->format('d M, Y h:i A') : '❌ Not Verified' }}
                            </div>
                            <div>
                                <strong>Role:</strong>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                    {{ $user->is_admin ? 'bg-blue-600 text-white' : 'bg-gray-500 text-white' }}">
                                    {{ $user->is_admin ? 'Admin' : 'User' }}
                                </span>
                            </div>
                            <div>
                                <strong>Status:</strong>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                    {{ $user->is_active ? 'bg-green-600 text-white' : 'bg-red-600 text-white' }}">
                                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </div>
                            <div>
                                <strong>Created At:</strong> {{ $user->created_at->format('d M, Y h:i A') }}
                            </div>
                            <div>
                                <strong>Last Updated:</strong> {{ $user->updated_at->format('d M, Y h:i A') }}
                            </div>
                        </div>
                    </div>

                    {{-- Future Profile Fields (optional) --}}
                    {{-- 
                    <div class="mt-6">
                        <h3 class="text-lg font-medium">Additional Info</h3>
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Phone: {{ $user->phone ?? 'N/A' }}<br>
                            Avatar: <img src="{{ $user->avatar }}" class="w-16 h-16 rounded mt-2" alt="avatar">
                        </div>
                    </div>
                    --}}

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('admin.edit', $user->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 shadow-sm">
                            Edit User
                        </a>
                        <a href="{{ route('admin.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 dark:text-white text-sm font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-700">
                            Back to List
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
