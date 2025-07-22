<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Create New User
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if(session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 p-3 rounded dark:bg-green-800 dark:text-green-100">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded dark:bg-red-800 dark:text-red-100">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input id="password" name="password" type="password" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            {{-- Role (is_admin) --}}
                            <div>
                                <label for="is_admin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                <select id="is_admin" name="is_admin"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="0" {{ old('is_admin') == 0 ? 'selected' : '' }}>User</option>
                                    <option value="1" {{ old('is_admin') == 1 ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            {{-- Active Status --}}
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select id="is_active" name="is_active"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="mt-6 flex justify-end space-x-2">
                            <a href="{{ route('admin.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 dark:text-white text-sm font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-700">
                                Cancel
                            </a>
                            <button type="submit"
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 shadow-sm">
                                Create User
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
