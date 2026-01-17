<x-admin-layout
    title="Users"
    page-title="Users"
    page-desc="Role/Approval এবং Reseller Tree ম্যানেজ করুন।"
>
    <x-slot name="actions">
        <a href="{{ route('dashboard') }}"
           class="px-3 py-2 rounded-lg bg-white border text-sm hover:bg-gray-50">
            Dashboard
        </a>
    </x-slot>

    @php
        $roleBadge = [
            'super_admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'reseller'    => 'bg-blue-50 text-blue-700 border-blue-200',
            'client'      => 'bg-slate-50 text-slate-700 border-slate-200',
        ];

        $approvalBadge = [
            'approved'   => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
            'rejected'   => 'bg-rose-50 text-rose-700 border-rose-200',
            'suspended'  => 'bg-red-50 text-red-700 border-red-200',
        ];

        $activeChips = [];

        if (!empty($q)) $activeChips[] = ['label' => 'Search: '.$q, 'key' => 'q'];
        if (!empty($role)) $activeChips[] = ['label' => 'Role: '.$role, 'key' => 'role'];
        if (!empty($approval)) $activeChips[] = ['label' => 'Approval: '.$approval, 'key' => 'approval_status'];
    @endphp

    {{-- Top Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div class="bg-white border rounded-2xl p-4">
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($users->total()) }}</div>
            <div class="mt-2 text-xs text-gray-500">
                Showing <span class="font-medium text-gray-700">{{ $users->firstItem() ?? 0 }}</span>–<span class="font-medium text-gray-700">{{ $users->lastItem() ?? 0 }}</span>
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-4">
            <div class="text-sm text-gray-500">Current Filters</div>
            <div class="mt-2 flex flex-wrap gap-2">
                @if(count($activeChips) === 0)
                    <span class="text-sm text-gray-700">No filters applied</span>
                @else
                    @foreach($activeChips as $chip)
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border bg-gray-50 text-gray-700 text-xs">
                            {{ $chip['label'] }}
                        </span>
                    @endforeach
                @endif
            </div>
            @if(count($activeChips) > 0)
                <div class="mt-3">
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-600 hover:text-gray-900 underline">
                        Clear all filters
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-4 text-white border border-black/10">
            <div class="text-sm text-white/80">Tip</div>
            <div class="mt-2 text-sm leading-relaxed text-white/90">
                Reseller হলে Parent Reseller + Profile Status সেট করুন। Suspended করলে user login বাধা দিতে পারবেন (policy অনুযায়ী)।
            </div>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white border rounded-2xl p-4 mb-5">
        <form class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-5">
                <label class="text-sm font-medium text-gray-700">Search</label>
                <input
                    name="q"
                    value="{{ $q ?? '' }}"
                    class="w-full mt-1 px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400"
                    placeholder="Name or Email"
                >
            </div>

            <div class="md:col-span-3">
                <label class="text-sm font-medium text-gray-700">Role</label>
                <select name="role"
                        class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                    <option value="">All</option>
                    <option value="super_admin" @selected(($role ?? '') === 'super_admin')>Super Admin</option>
                    <option value="reseller" @selected(($role ?? '') === 'reseller')>Reseller</option>
                    <option value="client" @selected(($role ?? '') === 'client')>Client</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="text-sm font-medium text-gray-700">Approval</label>
                <select name="approval_status"
                        class="w-full mt-1 px-3 py-2.5 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-gray-900/10 focus:border-gray-400">
                    <option value="">All</option>
                    <option value="approved" @selected(($approval ?? '') === 'approved')>Approved</option>
                    <option value="pending" @selected(($approval ?? '') === 'pending')>Pending</option>
                    <option value="rejected" @selected(($approval ?? '') === 'rejected')>Rejected</option>
                    <option value="suspended" @selected(($approval ?? '') === 'suspended')>Suspended</option>
                </select>
            </div>

            <div class="md:col-span-1 flex gap-2 md:justify-end">
                <button class="w-full md:w-auto px-4 py-2.5 rounded-xl bg-gray-900 text-white text-sm hover:bg-gray-800">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white border rounded-2xl overflow-hidden">
        <div class="px-4 py-3 border-b flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Users List
            </div>
            <div class="text-xs text-gray-500">
                Last updated: {{ now()->format('d M, Y h:i A') }}
            </div>
        </div>

        @if ($users->count() === 0)
            <div class="p-10 text-center">
                <div class="mx-auto w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-600">
                    <span class="text-xl">👤</span>
                </div>
                <div class="mt-3 font-medium text-gray-900">কোনো ইউজার পাওয়া যায়নি</div>
                <div class="mt-1 text-sm text-gray-500">Search/Filter পরিবর্তন করে আবার চেষ্টা করুন।</div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm">
                        Reset Filters
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-5 py-3 font-medium">User</th>
                            <th class="text-left px-5 py-3 font-medium">Role</th>
                            <th class="text-left px-5 py-3 font-medium">Approval</th>
                            <th class="text-left px-5 py-3 font-medium">Parent Reseller</th>
                            <th class="text-right px-5 py-3 font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($users as $u)
                            @php
                                $initials = strtoupper(mb_substr($u->name, 0, 1));
                                $rClass = $roleBadge[$u->role] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                $aClass = $approvalBadge[$u->approval_status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                            @endphp

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 border flex items-center justify-center text-gray-700 font-semibold">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $u->name }}</div>
                                            <div class="text-gray-500 text-xs">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs {{ $rClass }}">
                                        {{ $u->role }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full border text-xs {{ $aClass }}">
                                        {{ $u->approval_status }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    @if($u->parent_reseller_id)
                                        <span class="inline-flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            #{{ $u->parent_reseller_id }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.users.edit', $u) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-white border hover:bg-gray-50 text-sm">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
