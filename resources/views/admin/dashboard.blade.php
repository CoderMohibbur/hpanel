<x-admin-layout title="Admin Dashboard" page-title="Admin Dashboard" page-desc="System overview (Phase-0)">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Users</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">—</div>
            <div class="mt-3">
                <a class="text-sm text-gray-700 dark:text-gray-200 underline" href="{{ route('admin.users.index') }}">Manage Users</a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Servers</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">—</div>
            <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">Coming in Phase-1</div>
        </div>

        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5">
            <div class="text-xs text-gray-500 dark:text-gray-400">Orders</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">—</div>
            <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">Coming in Phase-3</div>
        </div>
    </div>
</x-admin-layout>
