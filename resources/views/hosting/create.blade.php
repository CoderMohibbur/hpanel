<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            🖥️ Create Hosting
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-600 dark:text-red-400">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('hosting.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Domain Name</label>
                    <input type="text" name="domain" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring focus:ring-indigo-300" placeholder="example.com" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Package</label>
                    <input type="text" name="package" value="default" readonly class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Plan</label>
                    <select name="plan" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="Free">Free</option>
                        <option value="Pro">Pro</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition shadow-md">
                        🚀 Provision Hosting
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
